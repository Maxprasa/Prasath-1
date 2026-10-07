<?php
require __DIR__ . '/layout.php';

$hero = photo(setting('hero_photo'));
$lang = $GLOBALS['lang'];
$featured = array_values(array_filter(albums(), fn($a) => !empty($a['featured'])));
$videos = array_values(array_filter(data_get('videos'), fn($v) => !empty($v['visible'])));
$topVideos = array_slice(array_values(array_filter($videos, fn($v) => !empty($v['featured']))) ?: $videos, 0, 3);
$about = photo(setting('about_photo'));

page_start('', txt('hero_lead'), [
    'page' => 'home', 'canonical' => url('home'), 'alt' => url('home', $lang === 'fi' ? 'en' : 'fi'),
    'image' => $hero, 'preload' => $hero,
    'jsonld' => [
        '@context' => 'https://schema.org', '@type' => 'ProfessionalService', 'name' => 'Kuvadoo',
        'url' => SITE_URL . '/', 'email' => setting('email'), 'telephone' => setting('whatsapp'),
        'image' => $hero ? SITE_URL . photo_url($hero, 1600) : null,
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => setting('town'), 'addressCountry' => 'FI'],
        'areaServed' => 'FI', 'identifier' => setting('ytunnus'),
        'founder' => ['@type' => 'Person', 'name' => 'Prasath Sivakathiramalei'],
        'sameAs' => array_values(array_filter([setting('instagram'), setting('facebook'), setting('youtube')])),
        'knowsAbout' => ['Photography', 'Videography', 'Aerial photography', 'Video editing'],
    ],
]);
?>
<section class="hero">
  <div class="hero-media"><?= img($hero, '100vw', 'hero-img', false, 1600) ?></div>
  <div class="hero-shade" aria-hidden="true"></div>
  <div class="wrap hero-content">
    <p class="kicker hero-kicker"><?= e(txt('hero_kicker')) ?></p>
    <h1 class="hero-title"><?= e(txt('hero_title')) ?></h1>
    <p class="hero-lead"><?= e(txt('hero_lead')) ?></p>
    <p class="btn-row hero-ctas">
      <a class="btn btn-primary" href="<?= e(url('photo')) ?>"><?= e(t('cta_work')) ?></a>
      <a class="btn btn-light" href="<?= e(url('films')) ?>"><?= e(t('cta_films')) ?></a>
    </p>
  </div>
</section>

<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
<?php for ($i = 0; $i < 2; $i++): ?>
    <span><?= e(txt('svc_photo_title')) ?></span><span>Hämeen Films</span><span><?= e(txt('svc_aerial_title')) ?></span><span><?= e(txt('svc_edit_title')) ?></span><span><?= e(tr(categories()['events']['name'])) ?></span><span><?= e(tr(categories()['confirmation']['name'])) ?></span><span><?= e(tr(categories()['weddings']['name'])) ?></span><span><?= e(tr(categories()['business']['name'])) ?></span>
<?php endfor; ?>
  </div>
</div>

<section class="section wrap" aria-labelledby="svc-h">
  <h2 id="svc-h" class="section-title reveal"><?= e(txt('services_title')) ?></h2>
  <ul class="services">
<?php foreach ([['photo', url('photo'), '01'], ['video', url('films'), '02'], ['aerial', url('films'), '03'], ['edit', url('films'), '04']] as [$k, $href, $num]): ?>
    <li class="service reveal">
      <span class="service-num" aria-hidden="true"><?= $num ?></span>
      <h3><a href="<?= e($href) ?>"><?= e(txt("svc_{$k}_title")) ?></a></h3>
      <p><?= e(txt("svc_{$k}_text")) ?></p>
    </li>
<?php endforeach; ?>
  </ul>
</section>

<?php if ($featured): ?>
<section class="section wrap" aria-labelledby="work-h">
  <div class="section-head reveal">
    <h2 id="work-h" class="section-title"><?= e(txt('work_title')) ?></h2>
    <a class="link-arrow" href="<?= e(url('photo')) ?>"><?= e(t('cta_all_photos')) ?></a>
  </div>
  <ul class="album-grid">
<?php foreach ($featured as $i => $a): $cover = photo($a['cover']) ?? photo($a['photos'][0]); ?>
    <li class="album-card reveal<?= $i === 0 ? ' album-card-wide' : '' ?>">
      <a href="<?= e(url('album', null, ['cat' => $a['category'], 'album' => $a['slug']])) ?>">
        <span class="album-img"><?= img($cover, $i === 0 ? '(min-width: 900px) 66vw, 100vw' : '(min-width: 900px) 33vw, (min-width: 600px) 50vw, 100vw') ?></span>
        <span class="album-meta">
          <span class="album-cat"><?= e(tr(categories()[$a['category']]['name'])) ?></span>
          <span class="album-title"><?= e(tr($a['title'])) ?></span>
        </span>
      </a>
    </li>
<?php endforeach; ?>
  </ul>
</section>
<?php endif; ?>

<section class="films-teaser theme-film-block" aria-labelledby="films-h">
  <div class="wrap">
    <div class="films-head reveal">
      <p class="kicker"><?= e(t('films_tagline')) ?></p>
      <h2 id="films-h" class="films-wordmark">Hämeen Films</h2>
      <p class="lead"><?= e(txt('films_lead')) ?></p>
    </div>
<?php if ($topVideos): ?>
    <div class="video-grid">
<?php foreach ($topVideos as $i => $v): ?>
      <?= video_card($v, $i === 0 ? 'large' : 'normal') ?>
<?php endforeach; ?>
    </div>
    <p class="yt-note"><?= e(t('yt_note')) ?></p>
<?php endif; ?>
    <p><a class="btn btn-gold" href="<?= e(url('films')) ?>"><?= e(t('cta_films')) ?></a></p>
  </div>
</section>

<section class="section wrap about-teaser" aria-labelledby="about-h">
  <div class="about-teaser-media reveal">
<?php if ($about): ?>
    <?= img($about, '(min-width: 900px) 40vw, 100vw') ?>
<?php else: ?>
    <div class="about-mark"><?= k_mark('about-k') ?></div>
<?php endif; ?>
  </div>
  <div class="about-teaser-text reveal">
    <p class="kicker"><?= e(txt('about_kicker')) ?></p>
    <h2 id="about-h" class="section-title"><?= e(txt('about_title')) ?></h2>
    <p class="lead"><?= e(txt('about_short')) ?></p>
    <p><a class="link-arrow" href="<?= e(url('about')) ?>"><?= e(t('cta_more')) ?></a></p>
  </div>
</section>

<section class="section wrap" aria-labelledby="steps-h">
  <h2 id="steps-h" class="section-title reveal"><?= e(txt('steps_title')) ?></h2>
  <ol class="steps">
<?php for ($i = 1; $i <= 4; $i++): ?>
    <li class="step reveal"><span class="step-num" aria-hidden="true"><?= $i ?></span><p><?= e(txt("step$i")) ?></p></li>
<?php endfor; ?>
  </ol>
  <p class="reveal"><a class="link-arrow" href="<?= e(url('prices')) ?>"><?= e(t('cta_prices')) ?></a></p>
</section>

<?php cta_band(txt('cta_title'), txt('cta_text')); page_end();
