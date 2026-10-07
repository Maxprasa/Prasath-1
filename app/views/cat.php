<?php
require __DIR__ . '/layout.php';
require __DIR__ . '/_albums.php';
$lang = $GLOBALS['lang'];
$key = $params['cat'];
$cat = categories()[$key];
$list = albums($key);
$name = tr($cat['name']);
$intro = txt('cat_' . $key);
page_start($name, $intro, [
    'page' => 'photo', 'canonical' => url('cat', null, ['cat' => $key]),
    'alt' => url('cat', $lang === 'fi' ? 'en' : 'fi', ['cat' => $key]),
    'image' => $list ? photo($list[0]['cover']) : null,
    'jsonld' => breadcrumbs_ld([[txt('photo_title'), url('photo')], [$name, url('cat', null, ['cat' => $key])]]),
    'noindex' => $list === [],
]);
?>
<header class="page-hero"><div class="wrap page-head">
  <p class="kicker"><a href="<?= e(url('photo')) ?>"><?= e(txt('photo_title')) ?></a></p>
  <h1 class="page-title"><?= e($name) ?></h1>
  <p class="lead"><?= e($intro) ?></p>
  <?php category_chips($key); ?>
</div></header>
<section class="section wrap" aria-label="<?= e(t('galleries')) ?>">
<?php if ($list): album_cards($list); else: ?>
  <p><?= e(t('empty_cat')) ?></p>
<?php endif; ?>
</section>
<?php cta_band(txt('cta_title'), txt('cta_text')); page_end();
