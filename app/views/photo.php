<?php
require __DIR__ . '/layout.php';
require __DIR__ . '/_albums.php';
$lang = $GLOBALS['lang'];
$all = albums();
$first = $all ? (photo($all[0]['cover']) ?? null) : null;
page_start(txt('photo_title'), txt('photo_lead'), [
    'page' => 'photo', 'canonical' => url('photo'), 'alt' => url('photo', $lang === 'fi' ? 'en' : 'fi'), 'image' => $first,
]);
?>
<header class="page-hero"><div class="wrap page-head">
  <p class="kicker">Kuvadoo</p>
  <h1 class="page-title"><?= e(txt('photo_title')) ?></h1>
  <p class="lead"><?= e(txt('photo_lead')) ?></p>
  <?php category_chips(); ?>
</div></header>
<section class="section wrap" aria-label="<?= e(t('galleries')) ?>">
  <?php album_cards($all); ?>
</section>
<?php cta_band(txt('cta_title'), txt('cta_text')); page_end();
