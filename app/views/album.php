<?php
require __DIR__ . '/layout.php';
require __DIR__ . '/_albums.php';
$lang = $GLOBALS['lang'];
$a = $params['data'];
$key = $a['category'];
$catName = tr(categories()[$key]['name']);
$title = tr($a['title']);
$photos = array_values(array_filter(array_map('photo', $a['photos'])));
$cover = photo($a['cover']) ?? ($photos[0] ?? null);
$here = url('album', null, ['cat' => $key, 'album' => $a['slug']]);
page_start($title, tr($a['intro']) ?: $title, [
    'page' => 'photo', 'canonical' => $here,
    'alt' => url('album', $lang === 'fi' ? 'en' : 'fi', ['cat' => $key, 'album' => $a['slug']]),
    'image' => $cover,
    'jsonld' => breadcrumbs_ld([[txt('photo_title'), url('photo')], [$catName, url('cat', null, ['cat' => $key])], [$title, $here]]),
]);
?>
<header class="page-head wrap">
  <p class="kicker"><a href="<?= e(url('photo')) ?>"><?= e(txt('photo_title')) ?></a> / <a href="<?= e(url('cat', null, ['cat' => $key])) ?>"><?= e($catName) ?></a></p>
  <h1 class="page-title"><?= e($title) ?></h1>
<?php if (tr($a['intro']) !== ''): ?>
  <p class="lead"><?= e(tr($a['intro'])) ?></p>
<?php endif; ?>
  <p class="muted"><?= count($photos) ?> <?= e(t('photos_count')) ?></p>
</header>
<section class="wrap gallery" aria-label="<?= e($title) ?>">
<?php foreach ($photos as $i => $p): $portrait = $p['h'] > $p['w']; ?>
  <a class="g-item<?= $portrait ? ' is-portrait' : '' ?> reveal" href="<?= e(photo_url($p, 2200)) ?>" data-lb="<?= e(photo_url($p, 2200)) ?>" data-lb-srcset="<?= e(photo_srcset($p)) ?>">
    <?= img($p, '(min-width: 1100px) 33vw, (min-width: 600px) 50vw, 100vw', '', $i > 2) ?>
    <span class="visually-hidden"><?= e(t('open_photo')) ?></span>
  </a>
<?php endforeach; ?>
</section>
<?php
$others = array_values(array_filter(albums(), fn($o) => $o['slug'] !== $a['slug']));
if ($others): ?>
<section class="section wrap" aria-labelledby="more-h">
  <h2 id="more-h" class="section-title"><?= e(t('galleries')) ?></h2>
  <?php album_cards(array_slice($others, 0, 3)); ?>
</section>
<?php endif;
cta_band(txt('cta_title'), txt('cta_text')); page_end();
