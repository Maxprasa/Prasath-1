<?php
require __DIR__ . '/layout.php';

$lang = $GLOBALS['lang'];
$hero = photo(setting('hero_photo'));
$featured = array_values(array_filter(albums(), fn($a) => !empty($a['featured'])));
$videos = array_values(array_filter(data_get('videos'), fn($v) => !empty($v['visible'])));
$topVideos = array_slice(array_values(array_filter($videos, fn($v) => !empty($v['featured']))) ?: $videos, 0, 3);
$about = photo(setting('about_photo'));
$icons = [
    'photo' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" d="M4 7h3l2-3h6l2 3h3v13H4z"/><circle cx="12" cy="13" r="4" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
    'video' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="6" width="13" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" d="M16 10l5-3v10l-5-3z"/></svg>',
    'aerial' => '<svg viewBox="0 0 24 24" aria-hidden="true"><g fill="none" stroke="currentColor" stroke-width="2"><circle cx="5" cy="5" r="2.5"/><circle cx="19" cy="5" r="2.5"/><circle cx="5" cy="19" r="2.5"/><circle cx="19" cy="19" r="2.5"/><path d="M7 7l3 3m4 0l3-3M7 17l3-3m4 0l3 3"/><rect x="9.5" y="9.5" width="5" height="5" rx="1"/></g></svg>',
    'edit' => '<svg viewBox="0 0 24 24" aria-hidden="true"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h10M4 12h16M4 18h7"/><circle cx="17" cy="6" r="2"/><circle cx="14" cy="18" r="2"/></g></svg>',
];

page_start('', txt('hero_lead'), [
    'page' => 'home', 'canonical' => url('home'), 'alt' => url('home', $lang === 'fi' ? 'en' : 'fi'),
    'image' => $hero, 'preload' => $hero,
    'jsonld' => array_filter([
        '@context' => 'https://schema.org', '@type' => 'ProfessionalService', 'name' => 'Kuvadoo',
        'url' => SITE_URL . '/', 'email' => setting('email'), 'telephone' => setting('whatsapp'),
        'image' => $hero ? SITE_URL . photo_url($hero, 1600) : null,
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => setting('town'), 'addressCountry' => 'FI'],
        'areaServed' => 'FI', 'identifier' => setting('ytunnus'),
        'founder' => ['@type' => 'Person', 'name' => 'Prasath Sivakathiramalei'],
        'sameAs' => array_values(array_filter([setting('instagram'), setting('facebook'), setting('youtube')])),
    ]),
]);
?>
<section class="hero hero--cover" data-scrub>
  <div class="scrub-pin">
  <div class="hero-media" aria-hidden="true"><?= img($hero, '100vw', 'hero-img', false, 1600, '') ?></div>
  <div class="hero-shade" aria-hidden="true"></div>
  <div class="wrap hero-content">
    <p class="kicker rise d0"><?= e(txt('hero_kicker')) ?></p>
    <h1 class="rise d1"><?= hl_text(txt('hero_title')) ?></h1>
    <p class="lead rise d2"><?= e(txt('hero_lead')) ?></p>
    <p class="btn-row rise d3">
      <a class="btn btn-primary" href="<?= e(url('photo')) ?>"><?= e(t('cta_work')) ?></a>
      <a class="btn btn-outline-light" href="<?= e(url('films')) ?>"><?= e(t('cta_films')) ?></a>
    </p>
  </div>
  </div>
  <div class="band-wave" aria-hidden="true"><svg viewBox="0 0 1440 90" preserveAspectRatio="none"><path fill="currentColor" d="M0 90V40C240 0 480 0 720 30s480 50 720 10v50z"/></svg></div>
</section>

<section class="stats wrap" aria-label="Kuvadoo">
  <ul class="tiles">
<?php for ($i = 1; $i <= 4; $i++): ?>
    <li class="tile reveal"><strong><?= e(t("stat{$i}_n")) ?></strong><p><?= e(t("stat{$i}_t")) ?></p></li>
<?php endfor; ?>
  </ul>
</section>

<section class="section depth" aria-labelledby="svc-h">
  <div class="ghost" aria-hidden="true">Kuvadoo · Hämeen Films · Kuvadoo · Hämeen Films ·</div>
  <div class="wrap">
  <div class="section-head reveal"><div><p class="kicker"><?= e(t('brand_tagline')) ?></p><h2 id="svc-h" class="section-title"><?= e(txt('services_title')) ?></h2></div></div>
  <ul class="services">
<?php foreach ([['photo', url('photo')], ['video', url('films')], ['aerial', url('films')], ['edit', url('films')]] as [$k, $href]): ?>
    <li class="service tilt reveal">
      <span class="icon-tile"><?= $icons[$k] ?></span>
      <h3><a href="<?= e($href) ?>"><?= hf(e(txt("svc_{$k}_title"))) ?></a></h3>
      <p><?= e(txt("svc_{$k}_text")) ?></p>
    </li>
<?php endforeach; ?>
  </ul>
</div>
</section>

<?php
// 3D photo ring: covers + a few photos of each featured album (decorative; the album grid below is the accessible version)
$ringPhotos = [];
foreach ($featured as $a) {
    foreach (array_slice(array_values(array_unique(array_merge([$a['cover']], $a['photos']))), 0, 3) as $pid) {
        if (($p = photo($pid)) && count($ringPhotos) < 12) {
            $ringPhotos[] = [$p, $a];
        }
    }
}
if (count($ringPhotos) >= 6): ?>
<section class="ring-sec" aria-labelledby="ring-h">
  <div class="wrap section-head reveal"><div><p class="kicker"><?= e(t('galleries')) ?></p><h2 id="ring-h" class="section-title"><?= e(t('ring_title')) ?></h2></div><p class="muted"><?= e(t('ring_hint')) ?></p></div>
  <div class="stage" aria-hidden="true">
    <div class="ring">
<?php foreach ($ringPhotos as [$p, $a]): ?>
      <a class="ring-item" tabindex="-1" href="<?= e(url('album', null, ['cat' => $a['category'], 'album' => $a['slug']])) ?>"><?= img($p, '24rem', '', true, 960, '') ?><span><?= e(tr($a['title'])) ?></span></a>
<?php endforeach; ?>
    </div>
  </div>
  <div class="ring-ctrl" aria-hidden="true"><button class="round" type="button" tabindex="-1" data-ring="1">‹</button><button class="round" type="button" tabindex="-1" data-ring="-1">›</button></div>
</section>
<?php endif; ?>

<?php if ($featured): ?>
<section class="section wrap" aria-labelledby="work-h">
  <div class="section-head reveal">
    <div><p class="kicker"><?= e(t('galleries')) ?></p><h2 id="work-h" class="section-title"><?= e(txt('work_title')) ?></h2></div>
    <a class="link-arrow" href="<?= e(url('photo')) ?>"><?= e(t('cta_all_photos')) ?></a>
  </div>
  <ul class="album-grid">
<?php foreach ($featured as $i => $a): $cover = photo($a['cover']) ?? photo($a['photos'][0]); ?>
    <li class="album-card reveal<?= $i === 0 ? ' album-card-wide' : '' ?>">
      <a href="<?= e(url('album', null, ['cat' => $a['category'], 'album' => $a['slug']])) ?>">
        <span class="album-img" data-inner><?= img($cover, $i === 0 ? '(min-width: 56rem) 66vw, 100vw' : '(min-width: 56rem) 33vw, 100vw', '', true, 960, '') ?></span>
        <span class="album-meta"><span class="album-cat"><?= e(tr(categories()[$a['category']]['name'])) ?></span><span class="album-title"><?= e(tr($a['title'])) ?></span></span>
      </a>
    </li>
<?php endforeach; ?>
  </ul>
</section>
<?php endif; ?>

<section class="films-teaser" aria-labelledby="films-h">
  <div class="wrap">
    <div class="films-head reveal">
      <p class="kicker"><?= e(t('films_tagline')) ?></p>
      <h2 id="films-h" class="films-wordmark" lang="fi">Hämeen <span class="hl">Films</span></h2>
      <p class="lead"><?= hf(e(txt('films_lead'))) ?></p>
    </div>
<?php if ($topVideos): ?>
    <div class="video-grid">
<?php foreach ($topVideos as $i => $v): ?>
      <?= video_card($v, $i === 0 ? 'large' : 'normal') ?>
<?php endforeach; ?>
    </div>
    <p class="yt-note"><?= e(t('yt_note')) ?></p>
<?php endif; ?>
    <p><a class="btn btn-light" href="<?= e(url('films')) ?>"><?= e(t('cta_films')) ?></a></p>
  </div>
</section>

<section class="section wrap feature" aria-labelledby="about-h">
  <div class="feature-media reveal">
<?php if ($about): ?>
    <div class="print"><?= img($about, '20rem') ?></div>
<?php else: ?>
    <div class="about-mark"><?= k_mark('about-k') ?></div>
<?php endif; ?>
  </div>
  <div class="reveal">
    <p class="kicker"><?= e(txt('about_kicker')) ?></p>
    <h2 id="about-h" class="section-title"><?= e(txt('about_title')) ?></h2>
    <p class="lead"><?= e(txt('about_short')) ?></p>
    <ul class="checklist">
<?php foreach (['stat1', 'stat3', 'stat4'] as $k): ?>
      <li><?= e(t("{$k}_n")) ?> – <?= e(t("{$k}_t")) ?></li>
<?php endforeach; ?>
    </ul>
    <a class="btn btn-primary" href="<?= e(url('about')) ?>"><?= e(t('cta_more')) ?></a>
  </div>
</section>

<section class="section wrap" aria-labelledby="steps-h">
  <div class="section-head reveal"><div><h2 id="steps-h" class="section-title"><?= e(txt('steps_title')) ?></h2></div><a class="link-arrow" href="<?= e(url('prices')) ?>"><?= e(t('cta_prices')) ?></a></div>
  <ol class="steps">
<?php for ($i = 1; $i <= 4; $i++): ?>
    <li class="step reveal"><span class="step-num" aria-hidden="true"><?= $i ?></span><p><?= e(txt("step$i")) ?></p></li>
<?php endfor; ?>
  </ol>
</section>

<?php cta_band(txt('cta_title'), txt('cta_text')); page_end();
