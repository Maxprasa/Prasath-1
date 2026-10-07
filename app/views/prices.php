<?php
require __DIR__ . '/layout.php';
require __DIR__ . '/_prices.php';
$lang = $GLOBALS['lang'];
page_start(txt('prices_title'), txt('prices_lead'), [
    'page' => 'prices', 'canonical' => url('prices'), 'alt' => url('prices', $lang === 'fi' ? 'en' : 'fi'),
]);
?>
<header class="page-head wrap">
  <p class="kicker">Kuvadoo · Hämeen Films</p>
  <h1 class="page-title"><?= e(txt('prices_title')) ?></h1>
  <p class="lead"><?= e(txt('prices_lead')) ?></p>
</header>
<div class="wrap">
<?php foreach (data_get('prices')['groups'] ?? [] as $g) { price_group($g); } ?>
  <section class="price-notes reveal" aria-label="<?= e(t('footer_terms')) ?>">
    <?= paras(txt('prices_notes')) ?>
    <p><a href="<?= e(url('terms')) ?>"><?= e(t('footer_terms')) ?></a></p>
  </section>
</div>
<?php cta_band(txt('cta_title'), txt('cta_text')); page_end();
