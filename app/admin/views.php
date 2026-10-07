<?php
// Admin HTML. Simple English (the owner's working language here). Works on a phone.
declare(strict_types=1);

function admin_page_start(string $title, string $active = ''): void
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    $nav = ['' => 'Start', 'albums' => 'Photos', 'videos' => 'Videos', 'prices' => 'Prices', 'texts' => 'Texts', 'settings' => 'Settings'];
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> – Kuvadoo admin</title>
<link rel="icon" href="/assets/img/k-mark.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/admin.css?v=<?= ASSET_VER ?>">
</head>
<body>
<header class="a-head">
  <a class="a-brand" href="/hallinta/">Kuvadoo <span>admin</span></a>
<?php if (is_logged_in()): ?>
  <nav class="a-nav" aria-label="Admin menu">
<?php foreach ($nav as $k => $label): ?>
    <a href="/hallinta/<?= $k === '' ? '' : $k . '/' ?>"<?= $active === $k ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
<?php endforeach; ?>
    <a href="/" target="_blank" rel="noopener">View site ↗</a>
  </nav>
  <form method="post" action="/hallinta/logout/" class="a-logout"><?= csrf_field() ?><button type="submit" class="a-btn a-btn-quiet">Log out</button></form>
<?php endif; ?>
</header>
<main class="a-main">
  <h1><?= e($title) ?></h1>
<?php if ($flash): ?>
  <p class="a-flash a-flash-<?= e($flash[0]) ?>" role="status"><?= e($flash[1]) ?></p>
<?php endif; ?>
<?php
}

function admin_page_end(): void
{
    echo "</main>\n</body>\n</html>\n";
}

/** Two inputs (Finnish + English) for one bilingual value. */
function fi_en_fields(string $name, array $value, string $label, bool $textarea = false, int $rows = 3): string
{
    $out = '<fieldset class="a-fe"><legend>' . e($label) . '</legend>';
    foreach (['fi' => 'Suomi (FI)', 'en' => 'English (EN)'] as $l => $ll) {
        $id = preg_replace('/\W/', '_', $name) . '_' . $l;
        $out .= '<label for="' . $id . '">' . $ll . '</label>';
        $v = e($value[$l] ?? '');
        $out .= $textarea
            ? '<textarea id="' . $id . '" name="' . e($name) . '[' . $l . ']" rows="' . $rows . '" lang="' . $l . '">' . $v . '</textarea>'
            : '<input id="' . $id . '" type="text" name="' . e($name) . '[' . $l . ']" value="' . $v . '" lang="' . $l . '">';
    }
    return $out . '</fieldset>';
}

function upload_limits_note(): string
{
    return 'Max ' . e((string) ini_get('max_file_uploads')) . ' files at once, each up to '
        . e((string) min_ini_size()) . '. JPG, PNG or WebP. Big photos are resized automatically and location data (GPS) is removed.';
}

function min_ini_size(): string
{
    $toBytes = function (string $v): int {
        $n = (int) $v;
        return match (strtolower(substr(trim($v), -1))) { 'g' => $n << 30, 'm' => $n << 20, 'k' => $n << 10, default => $n };
    };
    $b = min($toBytes((string) ini_get('upload_max_filesize')), $toBytes((string) ini_get('post_max_size')), MAX_UPLOAD_BYTES);
    return round($b / 1048576) . ' MB';
}

function thumb(string $id, string $class = 'a-thumb'): string
{
    $p = photo($id);
    return $p ? '<img class="' . $class . '" src="' . e(photo_url($p, 480)) . '" alt="" loading="lazy" width="160" height="' . (int) round(160 * $p['h'] / $p['w']) . '">' : '';
}

// ---------------------------------------------------------------- Login / setup

function admin_setup_page(string $error): void
{
    admin_page_start('Set your admin password');
    ?>
  <div class="a-card a-narrow">
    <p>Welcome! This is the first start of the admin panel.</p>
    <ol>
      <li>Open <strong>hPanel → File Manager → public_html → data → setup-code.txt</strong> and copy the code.</li>
      <li>Paste the code below and choose a password (at least 10 characters).</li>
    </ol>
<?php if ($error): ?><p class="a-flash a-flash-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="/hallinta/setup/">
      <?= csrf_field() ?>
      <label for="code">Setup code</label>
      <input id="code" name="code" required autocomplete="off" autocapitalize="characters">
      <label for="pw">New password</label>
      <input id="pw" name="password" type="password" required minlength="10" autocomplete="new-password">
      <label for="pw2">New password again</label>
      <input id="pw2" name="password2" type="password" required minlength="10" autocomplete="new-password">
      <button class="a-btn" type="submit">Save password</button>
    </form>
  </div>
<?php
    admin_page_end();
}

function admin_login_page(string $error): void
{
    admin_page_start('Log in');
    ?>
  <div class="a-card a-narrow">
<?php if ($error): ?><p class="a-flash a-flash-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="/hallinta/login/">
      <?= csrf_field() ?>
      <label for="pw">Password</label>
      <input id="pw" name="password" type="password" required autocomplete="current-password" autofocus>
      <button class="a-btn" type="submit">Log in</button>
    </form>
    <p class="a-help">Forgot the password? In File Manager, delete <code>data/auth.json</code>. Then open this page again and set a new password with the new setup code.</p>
  </div>
<?php
    admin_page_end();
}

// ---------------------------------------------------------------- Dashboard

function admin_dashboard(): void
{
    admin_page_start('Hello, Prasath!', '');
    $albums = data_get('albums');
    $photos = count(data_get('photos'));
    $videos = count(data_get('videos'));
    ?>
  <div class="a-grid">
    <a class="a-tile" href="/hallinta/albums/"><strong>Photos</strong><span><?= count($albums) ?> albums, <?= $photos ?> photos</span><span>Add a new album or upload photos</span></a>
    <a class="a-tile" href="/hallinta/videos/"><strong>Videos</strong><span><?= $videos ?> videos</span><span>Paste a YouTube link</span></a>
    <a class="a-tile" href="/hallinta/prices/"><strong>Prices</strong><span>Change prices and packages</span></a>
    <a class="a-tile" href="/hallinta/texts/"><strong>Texts</strong><span>Home page, about, contact details, about photo</span></a>
    <a class="a-tile" href="/hallinta/settings/"><strong>Settings</strong><span>Password and backup</span></a>
  </div>
  <div class="a-card">
    <h2>Quick help</h2>
    <ul>
      <li><strong>New shoot:</strong> Photos → "New album" → choose the category → upload photos → write a short description for each photo (good for Google and for blind visitors).</li>
      <li><strong>New video:</strong> upload it to YouTube first, then Videos → paste the link.</li>
      <li><strong>Home page photo:</strong> open an album and choose "Home page photo" under a photo.</li>
      <li>Every text has a Finnish and an English box. If the English box is empty, the Finnish text is shown.</li>
    </ul>
  </div>
<?php
    admin_page_end();
}

// ---------------------------------------------------------------- Albums

function admin_albums_page(): void
{
    admin_page_start('Photos', 'albums');
    $albums = data_get('albums');
    ?>
  <details class="a-card" open>
    <summary><h2>New album</h2></summary>
    <form method="post" action="/hallinta/albums/">
      <?= csrf_field() ?><input type="hidden" name="action" value="create">
      <?= fi_en_fields('title', [], 'Album title (for example: Häät Aulangolla 2027)') ?>
      <label for="cat">Category</label>
      <select id="cat" name="category" required>
<?php foreach (categories() as $k => $c): ?>
        <option value="<?= e($k) ?>"><?= e($c['name']['fi']) ?> / <?= e($c['name']['en']) ?></option>
<?php endforeach; ?>
      </select>
      <button class="a-btn" type="submit">Create album</button>
    </form>
  </details>

  <form method="post" action="/hallinta/albums/" class="a-card">
    <?= csrf_field() ?><input type="hidden" name="action" value="order">
    <h2>Albums</h2>
    <p class="a-help">The first albums are shown first on the site. Change the numbers and press "Save order".</p>
    <ul class="a-list">
<?php foreach ($albums as $n => $a): ?>
      <li class="a-row">
        <?= thumb($a['cover']) ?>
        <div class="a-row-main">
          <a href="/hallinta/album/<?= e($a['id']) ?>/"><strong><?= e($a['title']['fi']) ?></strong></a>
          <span class="a-meta"><?= e(categories()[$a['category']]['name']['fi'] ?? $a['category']) ?> · <?= count($a['photos']) ?> photos<?= empty($a['visible']) ? ' · <b>hidden</b>' : '' ?><?= !empty($a['featured']) ? ' · on home page' : '' ?></span>
        </div>
        <label class="a-order">Order <input type="number" name="order[<?= e($a['id']) ?>]" value="<?= $n + 1 ?>" min="1" step="1"></label>
      </li>
<?php endforeach; ?>
    </ul>
    <button class="a-btn" type="submit">Save order</button>
  </form>
<?php
    admin_page_end();
}

function admin_album_page(array $a): void
{
    admin_page_start('Album: ' . $a['title']['fi'], 'albums');
    $hero = setting('hero_photo');
    $url = url('album', 'fi', ['cat' => $a['category'], 'album' => $a['slug']]);
    ?>
  <p><a href="/hallinta/albums/">← All albums</a> · <a href="<?= e($url) ?>" target="_blank" rel="noopener">View on site ↗</a></p>

  <form method="post" action="/hallinta/album/<?= e($a['id']) ?>/" enctype="multipart/form-data" class="a-card a-upload">
    <?= csrf_field() ?><input type="hidden" name="action" value="upload">
    <h2>Add photos</h2>
    <label for="up">Choose photos</label>
    <input id="up" type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" required>
    <p class="a-help"><?= upload_limits_note() ?> Uploading many photos can take a minute – keep the page open.</p>
    <button class="a-btn" type="submit">Upload</button>
  </form>

  <form method="post" action="/hallinta/album/<?= e($a['id']) ?>/" class="a-card">
    <?= csrf_field() ?><input type="hidden" name="action" value="save">
    <h2>Album details</h2>
    <?= fi_en_fields('title', $a['title'], 'Title') ?>
    <?= fi_en_fields('intro', $a['intro'], 'Short introduction (1–2 sentences)', true, 2) ?>
    <label for="cat">Category</label>
    <select id="cat" name="category">
<?php foreach (categories() as $k => $c): ?>
      <option value="<?= e($k) ?>"<?= $k === $a['category'] ? ' selected' : '' ?>><?= e($c['name']['fi']) ?> / <?= e($c['name']['en']) ?></option>
<?php endforeach; ?>
    </select>
    <label for="slug">Web address part</label>
    <input id="slug" name="slug" value="<?= e($a['slug']) ?>" pattern="[a-z0-9\-]+">
    <p class="a-help">Only small letters, numbers and dashes. If you change it, old links to this album stop working.</p>
    <label class="a-check"><input type="checkbox" name="visible"<?= !empty($a['visible']) ? ' checked' : '' ?>> Show on the website</label>
    <label class="a-check"><input type="checkbox" name="featured"<?= !empty($a['featured']) ? ' checked' : '' ?>> Show on the home page ("Recent work")</label>

    <h2>Photos (<?= count($a['photos']) ?>)</h2>
    <p class="a-help">Write what is in the photo (for example "Hääpari tanssii juhlasalissa"). Change the numbers to change the order. Tick "Delete" to remove a photo.</p>
    <ul class="a-photos">
<?php foreach ($a['photos'] as $n => $pid): $p = photo($pid); if (!$p) { continue; } ?>
      <li class="a-photo">
        <?= thumb($pid) ?>
        <div class="a-photo-fields">
          <label for="alt_<?= e($pid) ?>_fi">Description FI</label>
          <input id="alt_<?= e($pid) ?>_fi" name="alt[<?= e($pid) ?>][fi]" value="<?= e($p['alt']['fi'] ?? '') ?>" lang="fi">
          <label for="alt_<?= e($pid) ?>_en">Description EN</label>
          <input id="alt_<?= e($pid) ?>_en" name="alt[<?= e($pid) ?>][en]" value="<?= e($p['alt']['en'] ?? '') ?>" lang="en">
          <div class="a-photo-opts">
            <label class="a-order">Order <input type="number" name="porder[<?= e($pid) ?>]" value="<?= $n + 1 ?>" step="1"></label>
            <label class="a-check"><input type="radio" name="cover" value="<?= e($pid) ?>"<?= $pid === $a['cover'] ? ' checked' : '' ?>> Album cover</label>
            <label class="a-check"><input type="radio" name="hero" value="<?= e($pid) ?>"<?= $pid === $hero ? ' checked' : '' ?>> Home page photo</label>
            <label class="a-check a-danger"><input type="checkbox" name="delete[<?= e($pid) ?>]" value="1"> Delete</label>
          </div>
        </div>
      </li>
<?php endforeach; ?>
    </ul>
    <button class="a-btn a-sticky" type="submit">Save album</button>
  </form>

  <form method="post" action="/hallinta/album/<?= e($a['id']) ?>/" class="a-card a-danger-zone">
    <?= csrf_field() ?><input type="hidden" name="action" value="delete-album">
    <h2>Delete this album</h2>
    <label class="a-check"><input type="checkbox" name="confirm" value="1" required> Yes, delete the album and all its photos. This cannot be undone.</label>
    <button class="a-btn a-btn-danger" type="submit">Delete album</button>
  </form>
<?php
    admin_page_end();
}

// ---------------------------------------------------------------- Videos

function kind_select(string $name, string $value, string $id): string
{
    $o = '<select id="' . e($id) . '" name="' . e($name) . '">';
    foreach (video_kinds() as $k => $l) {
        $o .= '<option value="' . e($k) . '"' . ($k === $value ? ' selected' : '') . '>' . e($l['fi']) . ' / ' . e($l['en']) . '</option>';
    }
    return $o . '</select>';
}

function admin_videos_page(): void
{
    admin_page_start('Videos', 'videos');
    $videos = data_get('videos');
    ?>
  <details class="a-card" open>
    <summary><h2>Add a video</h2></summary>
    <form method="post" action="/hallinta/videos/">
      <?= csrf_field() ?><input type="hidden" name="action" value="add">
      <label for="yt">YouTube link</label>
      <input id="yt" name="youtube" required placeholder="https://www.youtube.com/watch?v=..." inputmode="url">
      <?= fi_en_fields('title', [], 'Title') ?>
      <label for="kind">Type</label>
      <?= kind_select('kind', 'wedding', 'kind') ?>
      <?= fi_en_fields('credit', [], 'Small note under the title (optional, e.g. client name)') ?>
      <label class="a-check"><input type="checkbox" name="featured" value="1"> Show on the home page</label>
      <button class="a-btn" type="submit">Add video</button>
      <p class="a-help">The cover image is copied from YouTube to your own site. Visitors connect to YouTube only when they press play.</p>
    </form>
  </details>

  <form method="post" action="/hallinta/videos/" class="a-card">
    <?= csrf_field() ?><input type="hidden" name="action" value="save">
    <h2>All videos</h2>
    <ul class="a-list">
<?php foreach ($videos as $n => $v): $id = $v['id']; ?>
      <li class="a-video">
        <img class="a-thumb" src="/media/videos/<?= e($v['youtube']) ?>-640.webp" alt="" width="160" height="90" loading="lazy">
        <div class="a-photo-fields">
          <p class="a-meta"><a href="https://www.youtube.com/watch?v=<?= e($v['youtube']) ?>" target="_blank" rel="noopener">youtube.com/watch?v=<?= e($v['youtube']) ?> ↗</a></p>
          <?= fi_en_fields("v[$id][title]", $v['title'], 'Title') ?>
          <?= fi_en_fields("v[$id][credit]", $v['credit'] ?? [], 'Note under the title') ?>
          <label for="k_<?= e($id) ?>">Type</label>
          <?= kind_select("v[$id][kind]", $v['kind'], 'k_' . $id) ?>
          <div class="a-photo-opts">
            <label class="a-order">Order <input type="number" name="v[<?= e($id) ?>][order]" value="<?= $n + 1 ?>" step="1"></label>
            <label class="a-check"><input type="checkbox" name="v[<?= e($id) ?>][visible]" value="1"<?= !empty($v['visible']) ? ' checked' : '' ?>> Show</label>
            <label class="a-check"><input type="checkbox" name="v[<?= e($id) ?>][featured]" value="1"<?= !empty($v['featured']) ? ' checked' : '' ?>> Home page</label>
            <label class="a-check"><input type="checkbox" name="v[<?= e($id) ?>][refresh]" value="1"> Refresh cover</label>
            <label class="a-check a-danger"><input type="checkbox" name="v[<?= e($id) ?>][delete]" value="1"> Delete</label>
          </div>
        </div>
      </li>
<?php endforeach; ?>
    </ul>
    <button class="a-btn a-sticky" type="submit">Save videos</button>
  </form>
<?php
    admin_page_end();
}

// ---------------------------------------------------------------- Prices

function admin_prices_page(): void
{
    admin_page_start('Prices', 'prices');
    $prices = data_get('prices');
    ?>
  <form method="post" action="/hallinta/prices/">
    <?= csrf_field() ?>
    <p class="a-help">Leave the price empty for "by quote". "From" shows "alk." before the price. One line per item in "What is included". The VAT and travel text is under Texts → Prices page.</p>
<?php foreach ($prices['groups'] as $g):
        $rows = $g['items'];
        $rows[] = ['id' => 'new1', 'visible' => true, 'from' => true, 'price' => null, 'name' => ['fi' => '', 'en' => ''], 'includes' => ['fi' => '', 'en' => '']];
    ?>
    <section class="a-card">
      <h2><?= e($g['title']['fi']) ?> / <?= e($g['title']['en']) ?></h2>
<?php foreach ($rows as $n => $it): $base = 'p[' . $g['id'] . '][' . $it['id'] . ']'; $isNew = str_starts_with($it['id'], 'new'); ?>
      <fieldset class="a-price<?= $isNew ? ' a-new' : '' ?>">
        <legend><?= $isNew ? 'Add a new package (fill in to add)' : e($it['name']['fi']) ?></legend>
        <?= fi_en_fields($base . '[name]', $it['name'], 'Name') ?>
        <div class="a-inline">
          <label>Price € <input name="<?= e($base) ?>[price]" value="<?= e($it['price'] === null ? '' : (string) $it['price']) ?>" inputmode="numeric" pattern="[0-9 ]*" size="7"></label>
          <label class="a-check"><input type="checkbox" name="<?= e($base) ?>[from]" value="1"<?= !empty($it['from']) ? ' checked' : '' ?>> From (alk.)</label>
<?php if (!$isNew): ?>
          <label class="a-check"><input type="checkbox" name="<?= e($base) ?>[visible]" value="1"<?= !empty($it['visible']) ? ' checked' : '' ?>> Show</label>
          <label class="a-order">Order <input type="number" name="<?= e($base) ?>[order]" value="<?= $n + 1 ?>" step="1"></label>
          <label class="a-check a-danger"><input type="checkbox" name="<?= e($base) ?>[delete]" value="1"> Delete</label>
<?php else: ?>
          <input type="hidden" name="<?= e($base) ?>[order]" value="999">
<?php endif; ?>
        </div>
        <?= fi_en_fields($base . '[includes]', $it['includes'], 'What is included (one per line)', true, 4) ?>
      </fieldset>
<?php endforeach; ?>
    </section>
<?php endforeach; ?>
    <button class="a-btn a-sticky" type="submit">Save prices</button>
  </form>
<?php
    admin_page_end();
}

// ---------------------------------------------------------------- Texts

function text_groups(): array
{
    return [
        'Home page – top' => ['hero_kicker' => 'Small text above the title', 'hero_title' => 'Big title', 'hero_lead' => 'Text under the title'],
        'Home page – services' => ['services_title' => 'Heading', 'svc_photo_title' => 'Photography: title', 'svc_photo_text' => 'Photography: text', 'svc_video_title' => 'Video: title', 'svc_video_text' => 'Video: text', 'svc_aerial_title' => 'Aerial: title', 'svc_aerial_text' => 'Aerial: text', 'svc_edit_title' => 'Editing: title', 'svc_edit_text' => 'Editing: text', 'work_title' => '"Recent work" heading'],
        'Hämeen Films' => ['films_lead' => 'Main text', 'films_intro' => 'Second text (films page)'],
        'About' => ['about_kicker' => 'Small text above the title', 'about_title' => 'Title', 'about_short' => 'Short text (home page)', 'about_body' => 'Full text (About page). Empty line = new paragraph.', 'about_name_note' => 'Note about the name'],
        'How booking works' => ['steps_title' => 'Heading', 'step1' => 'Step 1', 'step2' => 'Step 2', 'step3' => 'Step 3', 'step4' => 'Step 4'],
        'Photography pages' => ['photo_title' => 'Title', 'photo_lead' => 'Text', 'cat_events' => 'Events: text', 'cat_portraits' => 'Portraits: text', 'cat_confirmation' => 'Confirmation photos: text', 'cat_weddings' => 'Weddings: text', 'cat_business' => 'Business: text', 'cat_aerial' => 'Aerial: text'],
        'Prices page' => ['prices_title' => 'Title', 'prices_lead' => 'Text at the top', 'prices_notes' => 'VAT, travel and other notes. Empty line = new paragraph.'],
        'Contact' => ['contact_title' => 'Title', 'contact_lead' => 'Text', 'cta_title' => 'Big call-to-action title (end of pages)', 'cta_text' => 'Call-to-action text'],
    ];
}

function admin_texts_page(): void
{
    admin_page_start('Texts and contact details', 'texts');
    $c = data_get('content');
    $about = setting('about_photo');
    ?>
  <form method="post" action="/hallinta/texts/" enctype="multipart/form-data" class="a-card">
    <?= csrf_field() ?><input type="hidden" name="action" value="about-photo">
    <h2>About photo (a photo of you)</h2>
    <?= $about ? thumb($about) : '<p class="a-help">No photo yet – the K logo is shown instead.</p>' ?>
    <label for="ap">Choose a photo</label>
    <input id="ap" type="file" name="about_photo" accept="image/jpeg,image/png,image/webp" required>
    <button class="a-btn" type="submit">Upload</button>
  </form>
<?php if ($about): ?>
  <form method="post" action="/hallinta/texts/" class="a-card">
    <?= csrf_field() ?><input type="hidden" name="action" value="about-photo-remove">
    <button class="a-btn a-btn-quiet" type="submit">Remove the about photo</button>
  </form>
<?php endif; ?>

  <form method="post" action="/hallinta/texts/">
    <?= csrf_field() ?><input type="hidden" name="action" value="texts">
    <section class="a-card">
      <h2>Contact details</h2>
<?php foreach (['email' => 'Email', 'whatsapp' => 'WhatsApp number in international form (+358…)', 'whatsapp_display' => 'Phone number as shown on the site', 'town' => 'Town (shown in the footer and contact page)', 'ytunnus' => 'Y-tunnus', 'instagram' => 'Instagram link', 'facebook' => 'Facebook link', 'youtube' => 'YouTube link'] as $k => $label): ?>
      <label for="s_<?= $k ?>"><?= e($label) ?></label>
      <input id="s_<?= $k ?>" name="s[<?= $k ?>]" value="<?= e($c['site'][$k] ?? '') ?>">
<?php endforeach; ?>
    </section>
<?php foreach (text_groups() as $group => $keys): ?>
    <details class="a-card">
      <summary><h2><?= e($group) ?></h2></summary>
<?php foreach ($keys as $k => $label): $long = mb_strlen(($c['text'][$k]['fi'] ?? '')) > 70; ?>
      <?= fi_en_fields("t[$k]", $c['text'][$k] ?? [], $label, $long, $k === 'about_body' || $k === 'prices_notes' ? 10 : 3) ?>
<?php endforeach; ?>
    </details>
<?php endforeach; ?>
    <button class="a-btn a-sticky" type="submit">Save texts</button>
  </form>
<?php
    admin_page_end();
}

// ---------------------------------------------------------------- Settings

function admin_settings_page(): void
{
    admin_page_start('Settings', 'settings');
    ?>
  <form method="post" action="/hallinta/settings/" class="a-card a-narrow">
    <?= csrf_field() ?><input type="hidden" name="action" value="password">
    <h2>Change password</h2>
    <label for="cur">Current password</label>
    <input id="cur" name="current" type="password" required autocomplete="current-password">
    <label for="pw">New password (at least 10 characters)</label>
    <input id="pw" name="password" type="password" required minlength="10" autocomplete="new-password">
    <label for="pw2">New password again</label>
    <input id="pw2" name="password2" type="password" required minlength="10" autocomplete="new-password">
    <button class="a-btn" type="submit">Change password</button>
  </form>
  <form method="post" action="/hallinta/backup/" class="a-card a-narrow">
    <?= csrf_field() ?>
    <h2>Backup</h2>
    <p>Download all texts, prices, photos and videos as one zip file. Keep it on your computer. Do this after big changes.</p>
    <button class="a-btn" type="submit">Download backup</button>
  </form>
<?php
    admin_page_end();
}
