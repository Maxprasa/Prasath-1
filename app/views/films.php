<?php
require __DIR__ . '/layout.php';
require __DIR__ . '/_prices.php';
$lang = $GLOBALS['lang'];
$videos = array_values(array_filter(data_get('videos'), fn($v) => !empty($v['visible'])));
$top = array_values(array_filter($videos, fn($v) => !empty($v['featured'])));
$first = $top[0] ?? ($videos[0] ?? null);
$rest = array_values(array_filter($videos, fn($v) => $v !== $first));
$videoGroup = array_values(array_filter(data_get('prices')['groups'] ?? [], fn($g) => $g['id'] === 'video'))[0] ?? null;
page_start('Hämeen Films – ' . t('films_tagline'), txt('films_lead'), [
    'page' => 'films', 'theme' => 'film', 'canonical' => url('films'), 'alt' => url('films', $lang === 'fi' ? 'en' : 'fi'),
]);
?>
<header class="film-hero">
  <div class="wrap">
    <p class="kicker"><?= e(t('films_tagline')) ?></p>
    <h1 class="films-wordmark film-title" lang="fi">Hämeen Films</h1>
    <p class="lead"><?= hf(e(txt('films_lead'))) ?></p>
    <p class="muted"><?= e(txt('films_intro')) ?></p>
  </div>
  <div class="film-strip" aria-hidden="true"></div>
</header>
<?php if ($first): ?>
<section class="wrap section" aria-label="<?= e(tr($first['title'])) ?>">
  <?= video_card($first, 'large') ?>
</section>
<?php endif; ?>
<?php if ($rest): ?>
<section class="wrap section" aria-labelledby="all-v">
  <h2 id="all-v" class="section-title reveal"><?= e(t('all_videos')) ?></h2>
  <div class="video-grid">
<?php foreach ($rest as $v): ?>
    <?= video_card($v) ?>
<?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
<p class="wrap yt-note"><?= e(t('yt_note')) ?></p>
<?php if ($videoGroup): ?>
<div class="wrap section"><?php price_group($videoGroup); ?>
  <p class="muted"><a href="<?= e(url('prices')) ?>"><?= e(t('nav_prices')) ?></a> · <?= e(txt('prices_lead')) ?></p>
</div>
<?php endif; ?>
<?php cta_band(txt('cta_title'), txt('cta_text')); page_end();
