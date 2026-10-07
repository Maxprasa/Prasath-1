<?php
// Admin panel at /hallinta/. Plain forms (POST → redirect → GET), no JavaScript needed.
declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../images.php';
require __DIR__ . '/views.php';

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-Robots-Tag: noindex, nofollow');
header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self'; script-src 'none'; form-action 'self'; frame-ancestors 'none'; base-uri 'none'");

$GLOBALS['lang'] = 'fi';
admin_session_start();

$sub = trim(substr($path, strlen('hallinta')), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'POST') {
    // A POST bigger than post_max_size arrives empty: say so instead of "security check failed".
    if ($_POST === [] && $_FILES === [] && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $_SESSION['flash'] = ['error', 'The upload was too large for the server. Upload fewer or smaller photos at a time.'];
        header('Location: ' . ($_SERVER['REQUEST_URI'] ?? '/hallinta/'), true, 303);
        exit;
    }
    csrf_check();
}
// Any save error (disk full, broken data file) is shown to the owner instead of a blank page.
set_exception_handler(function (Throwable $e) {
    error_log('kuvadoo admin: ' . $e->getMessage());
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        http_response_code(500);
        exit('Error: ' . e($e->getMessage()));
    }
    $_SESSION['flash'] = ['error', 'Error: ' . $e->getMessage()];
    header('Location: /hallinta/', true, 303);
    exit;
});

function go(string $to, string $msg = '', string $kind = 'ok'): never
{
    if ($msg !== '') {
        $_SESSION['flash'] = [$kind, $msg];
    }
    header('Location: /hallinta/' . $to, true, 303);
    exit;
}

function post(string $k, string $default = ''): string
{
    $v = $_POST[$k] ?? $default;
    return is_string($v) ? trim(str_replace("\r\n", "\n", $v)) : $default;
}

function post_arr(string $k): array
{
    $v = $_POST[$k] ?? [];
    return is_array($v) ? $v : [];
}

function fi_en(array $src, string $k): array
{
    $v = $src[$k] ?? [];
    $clean = fn($s) => is_string($s) ? trim(str_replace("\r\n", "\n", $s)) : '';
    return ['fi' => $clean($v['fi'] ?? ''), 'en' => $clean($v['en'] ?? '')];
}

/** Normalised list of uploaded files for an <input name="x[]" multiple>. */
function uploaded_files(string $field): array
{
    $f = $_FILES[$field] ?? null;
    if (!$f) {
        return [];
    }
    if (!is_array($f['name'])) {
        $f = array_map(fn($v) => [$v], $f);
    }
    $out = [];
    foreach ($f['name'] as $i => $name) {
        $out[] = ['name' => (string) $name, 'tmp' => (string) $f['tmp_name'][$i], 'error' => (int) $f['error'][$i], 'size' => (int) $f['size'][$i]];
    }
    return $out;
}

/** Process uploads into photo records (slow GD work, done outside the lock). Returns [records by id, errors]. */
function handle_photo_uploads(string $field, string $nameHint): array
{
    @set_time_limit(600);
    $recs = [];
    $errors = [];
    foreach (uploaded_files($field) as $u) {
        if ($u['error'] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($u['error'] !== UPLOAD_ERR_OK) {
            $errors[] = $u['name'] . ': upload failed (code ' . $u['error'] . ', maybe too large).';
            continue;
        }
        if ($u['size'] > MAX_UPLOAD_BYTES || !is_uploaded_file($u['tmp'])) {
            $errors[] = $u['name'] . ': file is too large or invalid.';
            continue;
        }
        try {
            $recs[new_id()] = photo_process($u['tmp'], $nameHint);
        } catch (Throwable $e) {
            $errors[] = $u['name'] . ': ' . $e->getMessage();
        }
    }
    if ($recs) {
        data_update('photos', fn(array $all) => $all + $recs);
    }
    return [$recs, $errors];
}

function find_index(array $list, string $id): ?int
{
    foreach ($list as $i => $x) {
        if (($x['id'] ?? null) === $id) {
            return $i;
        }
    }
    return null;
}

/** Remove photo ids that are no longer used anywhere, with their files. */
function cleanup_photos(array $ids): void
{
    $ids = array_map('strval', $ids);
    $used = [];
    foreach (data_read_fresh('albums') as $a) {
        $used = array_merge($used, array_map('strval', $a['photos']));
    }
    $site = data_read_fresh('content')['site'] ?? [];
    $used[] = (string) ($site['hero_photo'] ?? '');
    $used[] = (string) ($site['about_photo'] ?? '');
    data_update('photos', function (array $photos) use ($ids, $used) {
        $changed = false;
        foreach ($ids as $id) {
            if (isset($photos[$id]) && !in_array($id, $used, true)) {
                photo_delete_files($photos[$id]);
                unset($photos[$id]);
                $changed = true;
            }
        }
        return $changed ? $photos : null;
    });
}

/** Unique album slug (ignoring the album itself). */
function unique_slug(string $slug, array $albums, string $selfId = ''): string
{
    $taken = array_column(array_filter($albums, fn($a) => $a['id'] !== $selfId), 'slug');
    $base = $slug;
    for ($n = 2; in_array($slug, $taken, true); $n++) {
        $slug = "$base-$n";
    }
    return $slug;
}

// ---------------------------------------------------------------- Not logged in
if (!auth_data()) {
    // First run: set the password with the setup code.
    $code = setup_code();
    $error = '';
    if ($method === 'POST' && $sub === 'setup') {
        $given = strtoupper(post('code'));
        if (!login_attempt_allowed()) {
            $error = 'Too many attempts. Wait 15 minutes.';
        } elseif (strlen($given) !== 12 || !hash_equals($code, $given)) {
            sleep(1);
            $error = 'The setup code is wrong.';
        } elseif ($p = password_problem((string) ($_POST['password'] ?? ''), (string) ($_POST['password2'] ?? ''))) {
            $error = $p;
        } else {
            auth_set_password((string) $_POST['password']);
            @unlink(DATA_DIR . '/setup-code.txt');
            login_success();
            auth_login();
            go('', 'Password saved. Welcome!');
        }
    }
    admin_setup_page($error);
    exit;
}

if (!is_logged_in()) {
    $error = '';
    if ($method === 'POST' && $sub === 'login') {
        if (!login_attempt_allowed()) {
            $error = 'Too many wrong passwords. Wait 15 minutes and try again.';
        } elseif (password_verify((string) ($_POST['password'] ?? ''), auth_data()['hash'])) {
            login_success();
            auth_login();
            go('');
        } else {
            sleep(1);
            $error = 'Wrong password.';
        }
    }
    admin_login_page($error);
    exit;
}

// ---------------------------------------------------------------- Logged in
$parts = $sub === '' ? [] : explode('/', $sub);
$section = $parts[0] ?? '';
$arg = $parts[1] ?? '';

switch ($section) {
    case '':
        admin_dashboard();
        break;

    case 'logout':
        if ($method === 'POST') {
            $_SESSION = [];
            session_destroy();
        }
        header('Location: /hallinta/', true, 303);
        break;

    // ------------------------------------------------ Albums
    case 'albums':
        if ($method === 'POST' && post('action') === 'create') {
            $title = fi_en($_POST, 'title');
            $cat = post('category');
            if ($title['fi'] === '') {
                go('albums', 'Write a Finnish title.', 'error');
            }
            if (!isset(categories()[$cat])) {
                go('albums', 'Choose a category.', 'error');
            }
            $id = new_id();
            data_update('albums', function (array $albums) use ($id, $title, $cat) {
                array_unshift($albums, ['id' => $id, 'slug' => unique_slug(slugify($title['fi']), $albums), 'category' => $cat,
                    'title' => $title, 'intro' => ['fi' => '', 'en' => ''], 'cover' => '', 'photos' => [],
                    'visible' => true, 'featured' => false, 'created' => date('c')]);
                return $albums;
            });
            go('album/' . $id, 'Album created. Now add photos.');
        }
        if ($method === 'POST' && post('action') === 'order') {
            $order = post_arr('order');
            data_update('albums', function (array $albums) use ($order) {
                usort($albums, fn($a, $b) => (float) ($order[$a['id']] ?? 999) <=> (float) ($order[$b['id']] ?? 999));
                return $albums;
            });
            go('albums', 'Order saved.');
        }
        admin_albums_page();
        break;

    case 'album':
        $albums = data_get('albums');
        $i = find_index($albums, $arg);
        if ($i === null) {
            go('albums', 'Album not found.', 'error');
        }
        if ($method !== 'POST') {
            admin_album_page($albums[$i]);
            break;
        }
        $action = post('action');

        if ($action === 'upload') {
            [$recs, $errors] = handle_photo_uploads('photos', $albums[$i]['slug']);
            $ids = array_map('strval', array_keys($recs));
            $found = false;
            data_update('albums', function (array $albums) use ($arg, $ids, &$found) {
                $i = find_index($albums, $arg);
                if ($i === null) {
                    return null; // album was deleted in another tab meanwhile
                }
                $found = true;
                $albums[$i]['photos'] = array_merge($albums[$i]['photos'], $ids);
                if ($albums[$i]['cover'] === '' && $ids) {
                    $albums[$i]['cover'] = $ids[0];
                }
                return $albums;
            });
            if (!$found) {
                cleanup_photos($ids);
                go('albums', 'The album was deleted meanwhile – the photos were not saved.', 'error');
            }
            $msg = count($ids) . ' photo(s) added.' . ($errors ? ' Problems: ' . implode(' ', $errors) : '');
            go('album/' . $arg, $msg, $errors ? 'error' : 'ok');
        }

        if ($action === 'save') {
            $title = fi_en($_POST, 'title');
            if ($title['fi'] === '') {
                go('album/' . $arg, 'The Finnish title cannot be empty.', 'error');
            }
            $intro = fi_en($_POST, 'intro');
            $cat = post('category');
            $slugIn = slugify(post('slug') ?: $title['fi']);
            $order = post_arr('porder');
            $delete = array_map('strval', array_keys(post_arr('delete')));
            $cover = post('cover');
            $hero = post('hero');
            $alt = post_arr('alt');

            $keep = [];
            data_update('albums', function (array $albums) use ($arg, $title, $intro, $cat, $slugIn, $order, $delete, $cover, &$keep) {
                $i = find_index($albums, $arg);
                if ($i === null) {
                    return null;
                }
                $a = $albums[$i];
                $a['title'] = $title;
                $a['intro'] = $intro;
                if (isset(categories()[$cat])) {
                    $a['category'] = $cat;
                }
                $a['slug'] = unique_slug($slugIn, $albums, $arg);
                $a['visible'] = isset($_POST['visible']);
                $a['featured'] = isset($_POST['featured']);
                // Photos uploaded in another tab meanwhile are kept (they are in $a['photos'] already)
                $keep = array_values(array_diff(array_map('strval', $a['photos']), $delete));
                $pos = array_flip($keep);
                usort($keep, fn($x, $y) => [(float) ($order[$x] ?? 9999), $pos[$x]] <=> [(float) ($order[$y] ?? 9999), $pos[$y]]);
                $a['photos'] = $keep;
                $a['cover'] = in_array($cover, $keep, true) ? $cover : (in_array($a['cover'], $keep, true) ? $a['cover'] : ($keep[0] ?? ''));
                $albums[$i] = $a;
                return $albums;
            });
            data_update('photos', function (array $photos) use ($alt, $keep) {
                foreach ($keep as $pid) {
                    if (isset($photos[$pid], $alt[$pid]) && is_array($alt[$pid])) {
                        $photos[$pid]['alt'] = fi_en($alt, $pid);
                    }
                }
                return $photos;
            });
            if ($hero !== '' && in_array($hero, $keep, true)) {
                data_update('content', function (array $c) use ($hero) {
                    $c['site']['hero_photo'] = $hero;
                    return $c;
                });
            }
            cleanup_photos($delete);
            go('album/' . $arg, 'Saved.' . ($delete ? ' ' . count($delete) . ' photo(s) deleted.' : ''));
        }

        if ($action === 'delete-album' && isset($_POST['confirm'])) {
            $ids = [];
            data_update('albums', function (array $albums) use ($arg, &$ids) {
                $i = find_index($albums, $arg);
                if ($i === null) {
                    return null;
                }
                $ids = $albums[$i]['photos'];
                array_splice($albums, $i, 1);
                return $albums;
            });
            cleanup_photos($ids);
            go('albums', 'Album deleted.');
        }
        go('album/' . $arg, 'Nothing changed. (To delete the album, tick the confirm box.)', 'error');

    // ------------------------------------------------ Videos
    case 'videos':
        if ($method === 'POST' && post('action') === 'add') {
            $yt = youtube_id(post('youtube'));
            if (!$yt) {
                go('videos', 'That does not look like a YouTube link.', 'error');
            }
            if (in_array($yt, array_column(data_get('videos'), 'youtube'), true)) {
                go('videos', 'This video is already on the list.', 'error');
            }
            $title = fi_en($_POST, 'title');
            if ($title['fi'] === '' && $title['en'] === '') {
                go('videos', 'Write a title.', 'error');
            }
            $kind = isset(video_kinds()[post('kind')]) ? post('kind') : 'event';
            $ok = video_poster_fetch($yt);
            $rec = ['id' => new_id(), 'youtube' => $yt, 'kind' => $kind, 'title' => $title, 'credit' => fi_en($_POST, 'credit'),
                'featured' => isset($_POST['featured']), 'visible' => true, 'poster' => $ok];
            data_update('videos', function (array $videos) use ($rec) {
                array_unshift($videos, $rec);
                return $videos;
            });
            go('videos', $ok ? 'Video added.' : 'Video added, but the cover image could not be downloaded. Try "Refresh cover" later.', $ok ? 'ok' : 'error');
        }
        if ($method === 'POST' && post('action') === 'save') {
            $in = post_arr('v');
            $refresh = [];
            $removed = [];
            data_update('videos', function (array $videos) use ($in, &$refresh, &$removed) {
                $out = [];
                foreach ($videos as $n => $v) {
                    $d = $in[$v['id']] ?? null;
                    $v['order'] = $n + 1;
                    if (is_array($d)) {
                        if (!empty($d['delete'])) {
                            $removed[] = $v['youtube'];
                            continue;
                        }
                        $v['title'] = fi_en($d, 'title');
                        $v['credit'] = fi_en($d, 'credit');
                        $v['kind'] = isset(video_kinds()[$d['kind'] ?? '']) ? $d['kind'] : $v['kind'];
                        $v['featured'] = !empty($d['featured']);
                        $v['visible'] = !empty($d['visible']);
                        $v['order'] = (float) ($d['order'] ?? $v['order']);
                        if (!empty($d['refresh'])) {
                            $refresh[] = $v['youtube'];
                        }
                    }
                    $out[] = $v;
                }
                usort($out, fn($a, $b) => $a['order'] <=> $b['order']);
                return array_map(function ($v) { unset($v['order']); return $v; }, $out);
            });
            $left = array_column(data_get('videos'), 'youtube');
            foreach (array_diff($removed, $left) as $yt) {
                foreach ([640, 1280] as $w) {
                    @unlink(MEDIA_DIR . "/videos/$yt-$w.webp");
                }
            }
            foreach ($refresh as $yt) {
                video_poster_fetch($yt);
            }
            go('videos', 'Videos saved.');
        }
        admin_videos_page();
        break;

    // ------------------------------------------------ Prices
    case 'prices':
        if ($method === 'POST') {
            $in = post_arr('p');
            data_update('prices', function (array $prices) use ($in) {
                foreach ($prices['groups'] as &$g) {
                    $items = [];
                    foreach (($in[$g['id']] ?? []) as $key => $d) {
                        $key = (string) $key;
                        if (!is_array($d) || !empty($d['delete'])) {
                            continue;
                        }
                        $name = fi_en($d, 'name');
                        if ($name['fi'] === '' && $name['en'] === '') {
                            continue; // empty "new" row
                        }
                        $isNew = str_starts_with($key, 'new');
                        $price = trim((string) ($d['price'] ?? ''));
                        $items[] = [
                            'id' => !$isNew && preg_match('/^[\w-]{1,40}$/', $key) ? $key : new_id(),
                            'visible' => $isNew || !empty($d['visible']),
                            'from' => !empty($d['from']),
                            'price' => $price === '' ? null : max(0, (int) preg_replace('/\D/', '', $price)),
                            'name' => $name,
                            'includes' => fi_en($d, 'includes'),
                            'order' => (float) ($d['order'] ?? 999),
                        ];
                    }
                    usort($items, fn($a, $b) => $a['order'] <=> $b['order']);
                    $g['items'] = array_map(function ($it) { unset($it['order']); return $it; }, $items);
                }
                unset($g);
                return $prices;
            });
            go('prices', 'Prices saved.');
        }
        admin_prices_page();
        break;

    // ------------------------------------------------ Texts & settings
    case 'texts':
        if ($method === 'POST' && post('action') === 'about-photo') {
            [$recs, $errors] = handle_photo_uploads('about_photo', 'prasath-kuvadoo');
            if (!$recs) {
                go('texts', $errors ? implode(' ', $errors) : 'Choose a photo first.', 'error');
            }
            $new = (string) array_key_first($recs);
            $old = '';
            data_update('content', function (array $c) use ($new, &$old) {
                $old = (string) ($c['site']['about_photo'] ?? '');
                $c['site']['about_photo'] = $new;
                return $c;
            });
            if ($old !== '') {
                cleanup_photos([$old]);
            }
            go('texts', 'About photo updated.');
        }
        if ($method === 'POST' && post('action') === 'about-photo-remove') {
            $old = '';
            data_update('content', function (array $c) use (&$old) {
                $old = (string) ($c['site']['about_photo'] ?? '');
                $c['site']['about_photo'] = '';
                return $c;
            });
            if ($old !== '') {
                cleanup_photos([$old]);
            }
            go('texts', 'About photo removed.');
        }
        if ($method === 'POST') {
            $t = post_arr('t');
            $s = post_arr('s');
            $site = [];
            foreach (['email', 'whatsapp', 'whatsapp_display', 'instagram', 'facebook', 'youtube', 'town', 'ytunnus'] as $k) {
                if (!isset($s[$k]) || !is_string($s[$k])) {
                    continue;
                }
                $val = trim($s[$k]);
                if (in_array($k, ['instagram', 'facebook', 'youtube'], true) && $val !== '' && !preg_match('~^https://~', $val)) {
                    go('texts', ucfirst($k) . ' link must start with https://', 'error');
                }
                if ($k === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    go('texts', 'The email address is not valid.', 'error');
                }
                if ($k === 'whatsapp' && !preg_match('/^\+?[0-9 ]{7,20}$/', $val)) {
                    go('texts', 'Write the WhatsApp number like +358 41 234 5678.', 'error');
                }
                $site[$k] = $val;
            }
            data_update('content', function (array $c) use ($t, $site) {
                foreach (array_keys($c['text']) as $k) {
                    if (isset($t[$k]) && is_array($t[$k])) {
                        $c['text'][$k] = fi_en($t, $k);
                    }
                }
                $c['site'] = array_merge($c['site'], $site);
                return $c;
            });
            go('texts', 'Texts saved.');
        }
        admin_texts_page();
        break;

    // ------------------------------------------------ Settings: password, backup
    case 'settings':
        if ($method === 'POST' && post('action') === 'password') {
            if (!password_verify((string) ($_POST['current'] ?? ''), auth_data()['hash'])) {
                sleep(1);
                go('settings', 'The current password is wrong.', 'error');
            }
            if ($p = password_problem((string) ($_POST['password'] ?? ''), (string) ($_POST['password2'] ?? ''))) {
                go('settings', $p, 'error');
            }
            auth_set_password((string) $_POST['password']);
            session_regenerate_id(true);
            go('settings', 'Password changed. Other devices are logged out.');
        }
        admin_settings_page();
        break;

    case 'backup':
        if ($method !== 'POST') {
            go('settings');
        }
        @set_time_limit(600);
        $tmp = tempnam(sys_get_temp_dir(), 'kvd');
        $zip = new ZipArchive();
        if ($tmp === false || $zip->open($tmp, ZipArchive::OVERWRITE) !== true) {
            go('settings', 'Could not create the backup (server temp folder). Try again later.', 'error');
        }
        foreach (['content', 'albums', 'photos', 'videos', 'prices'] as $n) {
            if (is_file(DATA_DIR . "/$n.json")) {
                $zip->addFile(DATA_DIR . "/$n.json", "data/$n.json");
            }
        }
        foreach (['photos', 'videos'] as $dir) {
            foreach (glob(MEDIA_DIR . "/$dir/*.webp") ?: [] as $f) {
                $zip->addFile($f, "media/$dir/" . basename($f));
                $zip->setCompressionName("media/$dir/" . basename($f), ZipArchive::CM_STORE);
            }
        }
        if (!$zip->close() || !filesize($tmp)) {
            @unlink($tmp);
            go('settings', 'The backup failed (maybe the disk is full).', 'error');
        }
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="kuvadoo-backup-' . date('Y-m-d') . '.zip"');
        header('Content-Length: ' . filesize($tmp));
        readfile($tmp);
        unlink($tmp);
        exit;

    default:
        http_response_code(404);
        admin_page_start('Not found');
        echo '<p>Page not found. <a href="/hallinta/">Back to the start</a></p>';
        admin_page_end();
}
