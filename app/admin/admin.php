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
    csrf_check();
}

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

/** Process uploads into photo records. Returns [ids, error messages]. */
function handle_photo_uploads(string $field, string $nameHint): array
{
    @set_time_limit(600);
    $photos = data_get('photos');
    $ids = [];
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
            $rec = photo_process($u['tmp'], $nameHint);
            $id = new_id();
            $photos[$id] = $rec;
            $ids[] = $id;
        } catch (Throwable $e) {
            $errors[] = $u['name'] . ': ' . $e->getMessage();
        }
    }
    if ($ids) {
        data_put('photos', $photos);
    }
    return [$ids, $errors];
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
    $photos = data_get('photos');
    $used = [];
    foreach (data_get('albums') as $a) {
        $used = array_merge($used, $a['photos']);
    }
    $used[] = setting('hero_photo');
    $used[] = setting('about_photo');
    $changed = false;
    foreach ($ids as $id) {
        if (isset($photos[$id]) && !in_array($id, $used, true)) {
            photo_delete_files($photos[$id]);
            unset($photos[$id]);
            $changed = true;
        }
    }
    if ($changed) {
        data_put('photos', $photos);
    }
}

// ---------------------------------------------------------------- Not logged in
if (!auth_data()) {
    // First run: set the password with the setup code.
    $code = setup_code();
    $error = '';
    if ($method === 'POST' && $sub === 'setup') {
        if (login_blocked()) {
            $error = 'Too many attempts. Wait 15 minutes.';
        } elseif (!hash_equals($code, strtoupper(post('code')))) {
            login_record(false);
            sleep(1);
            $error = 'The setup code is wrong.';
        } elseif ($p = password_problem($_POST['password'] ?? '', $_POST['password2'] ?? '')) {
            $error = $p;
        } else {
            auth_set_password((string) $_POST['password']);
            @unlink(DATA_DIR . '/setup-code.txt');
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            go('', 'Password saved. Welcome!');
        }
    }
    admin_setup_page($error);
    exit;
}

if (!is_logged_in()) {
    $error = '';
    if ($method === 'POST' && $sub === 'login') {
        if (login_blocked()) {
            $error = 'Too many wrong passwords. Wait 15 minutes and try again.';
        } elseif (password_verify((string) ($_POST['password'] ?? ''), auth_data()['hash'])) {
            login_record(true);
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            go('');
        } else {
            login_record(false);
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
        if ($method === 'POST') {
            $albums = data_get('albums');
            if (post('action') === 'create') {
                $title = fi_en($_POST, 'title');
                if ($title['fi'] === '') {
                    go('albums', 'Write a Finnish title.', 'error');
                }
                $cat = post('category');
                if (!isset(categories()[$cat])) {
                    go('albums', 'Choose a category.', 'error');
                }
                $slug = slugify($title['fi']);
                $taken = array_column($albums, 'slug');
                $base = $slug;
                for ($n = 2; in_array($slug, $taken, true); $n++) {
                    $slug = "$base-$n";
                }
                $id = new_id();
                array_unshift($albums, ['id' => $id, 'slug' => $slug, 'category' => $cat, 'title' => $title, 'intro' => ['fi' => '', 'en' => ''],
                    'cover' => '', 'photos' => [], 'visible' => true, 'featured' => false, 'created' => date('c')]);
                data_put('albums', $albums);
                go('album/' . $id, 'Album created. Now add photos.');
            }
            if (post('action') === 'order') {
                $order = post_arr('order');
                usort($albums, fn($a, $b) => (int) ($order[$a['id']] ?? 999) <=> (int) ($order[$b['id']] ?? 999));
                data_put('albums', $albums);
                go('albums', 'Order saved.');
            }
        }
        admin_albums_page();
        break;

    case 'album':
        $albums = data_get('albums');
        $i = find_index($albums, $arg);
        if ($i === null) {
            go('albums', 'Album not found.', 'error');
        }
        if ($method === 'POST') {
            $a = $albums[$i];
            $action = post('action');
            if ($action === 'upload') {
                [$ids, $errors] = handle_photo_uploads('photos', $a['slug']);
                $albums = data_get('albums'); // re-read in case of a long upload
                $i = find_index($albums, $arg);
                $albums[$i]['photos'] = array_merge($albums[$i]['photos'], $ids);
                if ($albums[$i]['cover'] === '' && $ids) {
                    $albums[$i]['cover'] = $ids[0];
                }
                data_put('albums', $albums);
                $msg = count($ids) . ' photo(s) added.' . ($errors ? ' Problems: ' . implode(' ', $errors) : '');
                go('album/' . $arg, $msg, $errors ? 'error' : 'ok');
            }
            if ($action === 'save') {
                $a['title'] = fi_en($_POST, 'title');
                if ($a['title']['fi'] === '') {
                    go('album/' . $arg, 'The Finnish title cannot be empty.', 'error');
                }
                $a['intro'] = fi_en($_POST, 'intro');
                $cat = post('category');
                if (isset(categories()[$cat])) {
                    $a['category'] = $cat;
                }
                $slug = slugify(post('slug') ?: $a['title']['fi']);
                foreach ($albums as $j => $o) {
                    if ($j !== $i && $o['slug'] === $slug) {
                        $slug .= '-2';
                    }
                }
                $a['slug'] = $slug;
                $a['visible'] = isset($_POST['visible']);
                $a['featured'] = isset($_POST['featured']);

                // Photos: alt texts, order, cover, delete, home hero
                $photos = data_get('photos');
                $alt = post_arr('alt');
                $order = post_arr('porder');
                $delete = array_keys(post_arr('delete'));
                foreach ($a['photos'] as $pid) {
                    if (isset($photos[$pid], $alt[$pid]) && is_array($alt[$pid])) {
                        $photos[$pid]['alt'] = fi_en($alt, $pid);
                    }
                }
                data_put('photos', $photos);
                $keep = array_values(array_diff($a['photos'], $delete));
                usort($keep, fn($x, $y) => (float) ($order[$x] ?? 9999) <=> (float) ($order[$y] ?? 9999));
                $a['photos'] = $keep;
                $cover = post('cover');
                $a['cover'] = in_array($cover, $keep, true) ? $cover : ($keep[0] ?? '');
                $albums[$i] = $a;
                data_put('albums', $albums);

                $hero = post('hero');
                if ($hero !== '' && in_array($hero, $keep, true)) {
                    $c = data_get('content');
                    $c['site']['hero_photo'] = $hero;
                    data_put('content', $c);
                }
                cleanup_photos($delete);
                go('album/' . $arg, 'Saved.' . ($delete ? ' ' . count($delete) . ' photo(s) deleted.' : ''));
            }
            if ($action === 'delete-album' && isset($_POST['confirm'])) {
                $ids = $albums[$i]['photos'];
                array_splice($albums, $i, 1);
                data_put('albums', $albums);
                cleanup_photos($ids);
                go('albums', 'Album deleted.');
            }
            go('album/' . $arg, 'Nothing changed. (To delete the album, tick the confirm box.)', 'error');
        }
        admin_album_page($albums[$i]);
        break;

    // ------------------------------------------------ Videos
    case 'videos':
        if ($method === 'POST') {
            $videos = data_get('videos');
            if (post('action') === 'add') {
                $yt = youtube_id(post('youtube'));
                if (!$yt) {
                    go('videos', 'That does not look like a YouTube link.', 'error');
                }
                $title = fi_en($_POST, 'title');
                if ($title['fi'] === '' && $title['en'] === '') {
                    go('videos', 'Write a title.', 'error');
                }
                $kind = isset(video_kinds()[post('kind')]) ? post('kind') : 'event';
                $ok = video_poster_fetch($yt);
                array_unshift($videos, ['id' => new_id(), 'youtube' => $yt, 'kind' => $kind, 'title' => $title, 'credit' => fi_en($_POST, 'credit'),
                    'featured' => isset($_POST['featured']), 'visible' => true, 'poster' => $ok]);
                data_put('videos', $videos);
                go('videos', $ok ? 'Video added.' : 'Video added, but the cover image could not be downloaded. Try "Refresh cover" later.', $ok ? 'ok' : 'error');
            }
            if (post('action') === 'save') {
                $in = post_arr('v');
                $out = [];
                foreach ($videos as $v) {
                    $d = $in[$v['id']] ?? null;
                    if (!is_array($d)) {
                        $out[] = $v;
                        continue;
                    }
                    if (!empty($d['delete'])) {
                        foreach ([640, 1280] as $w) {
                            @unlink(MEDIA_DIR . "/videos/{$v['youtube']}-$w.webp");
                        }
                        continue;
                    }
                    $v['title'] = fi_en($d, 'title');
                    $v['credit'] = fi_en($d, 'credit');
                    $v['kind'] = isset(video_kinds()[$d['kind'] ?? '']) ? $d['kind'] : $v['kind'];
                    $v['featured'] = !empty($d['featured']);
                    $v['visible'] = !empty($d['visible']);
                    $v['order'] = (float) ($d['order'] ?? 999);
                    if (!empty($d['refresh'])) {
                        $v['poster'] = video_poster_fetch($v['youtube']);
                    }
                    $out[] = $v;
                }
                usort($out, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
                foreach ($out as &$v) {
                    unset($v['order']);
                }
                data_put('videos', $out);
                go('videos', 'Videos saved.');
            }
        }
        admin_videos_page();
        break;

    // ------------------------------------------------ Prices
    case 'prices':
        if ($method === 'POST') {
            $prices = data_get('prices');
            $in = post_arr('p');
            foreach ($prices['groups'] as &$g) {
                $rows = $in[$g['id']] ?? [];
                $items = [];
                foreach ($rows as $key => $d) {
                    if (!is_array($d) || !empty($d['delete'])) {
                        continue;
                    }
                    $name = fi_en($d, 'name');
                    if ($name['fi'] === '' && $name['en'] === '') {
                        continue; // empty "new" row
                    }
                    $price = trim((string) ($d['price'] ?? ''));
                    $items[] = [
                        'id' => is_string($key) && preg_match('/^[\w-]{1,40}$/', $key) && !str_starts_with($key, 'new') ? $key : new_id(),
                        'visible' => !empty($d['visible']) || str_starts_with((string) $key, 'new'),
                        'from' => !empty($d['from']),
                        'price' => $price === '' ? null : max(0, (int) preg_replace('/\D/', '', $price)),
                        'name' => $name,
                        'includes' => fi_en($d, 'includes'),
                        'order' => (float) ($d['order'] ?? 999),
                    ];
                }
                usort($items, fn($a, $b) => $a['order'] <=> $b['order']);
                foreach ($items as &$it) {
                    unset($it['order']);
                }
                unset($it);
                $g['items'] = $items;
            }
            unset($g);
            data_put('prices', $prices);
            go('prices', 'Prices saved.');
        }
        admin_prices_page();
        break;

    // ------------------------------------------------ Texts & settings
    case 'texts':
        if ($method === 'POST') {
            $c = data_get('content');
            if (post('action') === 'about-photo') {
                [$ids, $errors] = handle_photo_uploads('about_photo', 'prasath-kuvadoo');
                if ($ids) {
                    $old = $c['site']['about_photo'] ?? '';
                    $c['site']['about_photo'] = $ids[0];
                    data_put('content', $c);
                    if ($old) {
                        cleanup_photos([$old]);
                    }
                    go('texts', 'About photo updated.');
                }
                go('texts', $errors ? implode(' ', $errors) : 'Choose a photo first.', 'error');
            }
            if (post('action') === 'about-photo-remove') {
                $old = $c['site']['about_photo'] ?? '';
                $c['site']['about_photo'] = '';
                data_put('content', $c);
                if ($old) {
                    cleanup_photos([$old]);
                }
                go('texts', 'About photo removed.');
            }
            $t = post_arr('t');
            foreach (array_keys($c['text']) as $k) {
                if (isset($t[$k]) && is_array($t[$k])) {
                    $c['text'][$k] = fi_en($t, $k);
                }
            }
            $s = post_arr('s');
            foreach (['email', 'whatsapp', 'whatsapp_display', 'instagram', 'facebook', 'youtube', 'town', 'ytunnus'] as $k) {
                if (isset($s[$k]) && is_string($s[$k])) {
                    $val = trim($s[$k]);
                    if (in_array($k, ['instagram', 'facebook', 'youtube'], true) && $val !== '' && !preg_match('~^https://~', $val)) {
                        go('texts', ucfirst($k) . ' link must start with https://', 'error');
                    }
                    if ($k === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                        go('texts', 'The email address is not valid.', 'error');
                    }
                    $c['site'][$k] = $val;
                }
            }
            data_put('content', $c);
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
            if ($p = password_problem($_POST['password'] ?? '', $_POST['password2'] ?? '')) {
                go('settings', $p, 'error');
            }
            auth_set_password((string) $_POST['password']);
            session_regenerate_id(true);
            go('settings', 'Password changed.');
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
        $zip->open($tmp, ZipArchive::OVERWRITE);
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
        $zip->close();
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
