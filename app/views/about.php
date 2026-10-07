<?php
require __DIR__ . '/layout.php';
$lang = $GLOBALS['lang'];
$p = photo(setting('about_photo'));
page_start(txt('about_title') . ' – ' . txt('about_kicker'), txt('about_short'), [
    'page' => 'about', 'canonical' => url('about'), 'alt' => url('about', $lang === 'fi' ? 'en' : 'fi'), 'image' => $p,
    'jsonld' => ['@context' => 'https://schema.org', '@type' => 'Person', 'name' => 'Prasath Sivakathiramalei',
        'jobTitle' => 'Cinematographer & Photographer', 'worksFor' => ['@type' => 'Organization', 'name' => 'Kuvadoo'],
        'sameAs' => array_values(array_filter([setting('youtube')]))],
]);
?>
<div class="wrap about">
  <div class="about-media">
<?php if ($p): ?>
    <?= img($p, '(min-width: 900px) 40vw, 100vw', '', false) ?>
<?php else: ?>
    <div class="about-mark"><?= k_mark('about-k') ?></div>
<?php endif; ?>
  </div>
  <div class="about-text">
    <p class="kicker"><?= e(txt('about_kicker')) ?></p>
    <h1 class="page-title"><?= e(txt('about_title')) ?></h1>
    <?= paras(txt('about_body')) ?>
    <p class="muted"><?= e(txt('about_name_note')) ?></p>
    <ul class="skill-list">
<?php foreach (['svc_video_title', 'svc_photo_title', 'svc_aerial_title', 'svc_edit_title'] as $k): ?>
      <li><?= e(txt($k)) ?></li>
<?php endforeach; ?>
    </ul>
  </div>
</div>
<?php cta_band(txt('cta_title'), txt('cta_text')); page_end();
