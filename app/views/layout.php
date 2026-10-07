<?php
// Shared page frame: <head>, header with menu + language switch, footer, sticky contact bar.
declare(strict_types=1);

function k_mark(string $class = 'k-mark'): string
{
    return '<svg class="' . e($class) . '" viewBox="190 155 900 900" aria-hidden="true" focusable="false"><defs><mask id="km-' . e($class) . '"><rect width="1300" height="1300" fill="#fff"/><path d="M453 100H575V612L845 330L925 410L688 656L990 965L900 1050L607 737L453 895Z" fill="#000"/></mask></defs><circle cx="638" cy="605" r="446" fill="currentColor" mask="url(#km-' . e($class) . ')"/></svg>';
}

/**
 * @param array $o page:   page key for the active menu item
 *                 alt:    URL of this page in the other language
 *                 canonical: absolute path of this page
 *                 image:  photo array for og:image
 *                 theme:  'film' for the dark Hämeen Films look
 *                 jsonld: array to print as JSON-LD
 *                 noindex: bool
 */
function page_start(string $title, string $description, array $o = []): void
{
    $lang = $GLOBALS['lang'];
    $other = $lang === 'fi' ? 'en' : 'fi';
    $canonical = SITE_URL . ($o['canonical'] ?? '/');
    $og = !empty($o['image']) ? SITE_URL . photo_url($o['image'], 1600) : SITE_URL . '/assets/img/og-default.webp';
    $fullTitle = $title === '' ? 'Kuvadoo – ' . t('brand_tagline') : $title . ' | Kuvadoo';
    $active = $o['page'] ?? '';
    $nav = [
        'photo' => [url('photo'), t('nav_photo')],
        'films' => [url('films'), t('nav_films')],
        'prices' => [url('prices'), t('nav_prices')],
        'about' => [url('about'), t('nav_about')],
        'contact' => [url('contact'), t('nav_contact')],
    ];
    ?>
<!doctype html>
<html lang="<?= $lang ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($description) ?>">
<?php if (!empty($o['noindex'])): ?><meta name="robots" content="noindex">
<?php else: ?><link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>
<?php if (!empty($o['alt'])): ?>
<link rel="alternate" hreflang="<?= $lang ?>" href="<?= e($canonical) ?>">
<link rel="alternate" hreflang="<?= $other ?>" href="<?= e(SITE_URL . $o['alt']) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e(SITE_URL . ($lang === 'fi' ? ($o['canonical'] ?? '/') : $o['alt'])) ?>">
<?php endif; ?>
<meta name="color-scheme" content="light dark">
<meta name="theme-color" content="#f5f1ea" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#0e0d0c" media="(prefers-color-scheme: dark)">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Kuvadoo">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($og) ?>">
<meta property="og:locale" content="<?= $lang === 'fi' ? 'fi_FI' : 'en_GB' ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="/favicon.ico" sizes="32x32">
<link rel="icon" href="/assets/img/k-mark.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<link rel="preload" href="/assets/fonts/instrument-serif.woff2" as="font" type="font/woff2" crossorigin>
<?php if (!empty($o['preload'])): ?><link rel="preload" as="image" imagesrcset="<?= e(photo_srcset($o['preload'])) ?>" imagesizes="100vw">
<?php endif; ?>
<link rel="stylesheet" href="/assets/style.css?v=<?= ASSET_VER ?>">
<script src="/assets/site.js?v=<?= ASSET_VER ?>" defer></script>
<?php if (!empty($o['jsonld'])): ?><script type="application/ld+json"><?= json_encode($o['jsonld'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
<?php endif; ?>
</head>
<body class="<?= e(($o['theme'] ?? '') === 'film' ? 'theme-film' : '') ?><?= $active === 'home' ? ' is-home' : '' ?>">
<a class="skip" href="#main"><?= e(t('skip')) ?></a>
<header class="site-header">
  <div class="wrap header-row">
    <a class="brand" href="<?= e(url('home')) ?>" aria-label="<?= e(t('home_label')) ?>">
      <?= k_mark('brand-mark') ?>
      <span class="brand-word">Kuvadoo</span>
    </a>
    <nav class="site-nav" aria-label="<?= e(t('nav_main')) ?>">
      <ul>
<?php foreach ($nav as $key => [$href, $label]): ?>
        <li><a href="<?= e($href) ?>"<?= $key === $active ? ' aria-current="page"' : '' ?><?= $key === 'films' ? ' class="nav-films" lang="fi"' : '' ?>><?= e($label) ?></a></li>
<?php endforeach; ?>
      </ul>
    </nav>
<?php if (!empty($o['alt'])): ?>
    <a class="lang-switch" href="<?= e($o['alt']) ?>" hreflang="<?= $other ?>" lang="<?= $other ?>" aria-label="<?= e(t('lang_switch_label')) ?>"><?= e(t('lang_switch')) ?></a>
<?php endif; ?>
  </div>
</header>
<main id="main">
<?php
}

function page_end(): void
{
    $lang = $GLOBALS['lang'];
    $mail = 'mailto:' . setting('email') . '?subject=' . rawurlencode(t('mail_subject'));
    ?>
</main>
<footer class="site-footer">
  <div class="wrap footer-grid">
    <div class="footer-brand">
      <?= k_mark('footer-mark') ?>
      <p class="footer-name">Kuvadoo</p>
      <p><?= e(t('brand_tagline')) ?> · <span lang="fi">Hämeen Films</span> – <?= e(t('films_tagline')) ?></p>
    </div>
    <div>
      <h2 class="footer-h"><?= e(t('nav_contact')) ?></h2>
      <ul class="footer-list">
        <li><a href="<?= e(whatsapp_link()) ?>" rel="noopener">WhatsApp <?= e(phone_display()) ?></a></li>
        <li><a href="<?= e($mail) ?>"><?= e(setting('email')) ?></a></li>
        <li><?= e(setting('town')) ?>, <?= $lang === 'fi' ? 'Suomi' : 'Finland' ?></li>
      </ul>
    </div>
    <div>
      <h2 class="footer-h"><?= e(t('footer_follow')) ?></h2>
      <ul class="footer-list">
<?php foreach (['instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube'] as $k => $label): if (setting($k) === '') { continue; } ?>
        <li><a href="<?= e(setting($k)) ?>" rel="noopener"><?= $label ?></a></li>
<?php endforeach; ?>
      </ul>
    </div>
    <nav aria-label="<?= e(t('footer_nav')) ?>">
      <h2 class="footer-h">Kuvadoo</h2>
      <ul class="footer-list">
        <li><a href="<?= e(url('prices')) ?>"><?= e(t('nav_prices')) ?></a></li>
        <li><a href="<?= e(url('terms')) ?>"><?= e(t('footer_terms')) ?></a></li>
        <li><a href="<?= e(url('privacy')) ?>"><?= e(t('footer_privacy')) ?></a></li>
        <li><a href="https://apps.kuvadoo.fi/"><?= e(t('footer_apps')) ?></a></li>
      </ul>
    </nav>
  </div>
  <div class="wrap footer-legal">
    <p>© <?= date('Y') ?> Kuvadoo (<?= e(t('sole_trader')) ?>) · <?= e(t('ytunnus')) ?> <?= e(setting('ytunnus')) ?> · <?= e(setting('email')) ?></p>
  </div>
</footer>
<div class="contact-bar" role="group" aria-label="<?= e(t('contact_bar')) ?>">
  <a class="btn btn-wa" href="<?= e(whatsapp_link()) ?>" rel="noopener"><?= wa_icon() ?><span>WhatsApp</span></a>
  <a class="btn btn-ghost" href="<?= e($mail) ?>"><?= mail_icon() ?><span><?= e(t('cta_email')) ?></span></a>
</div>
<dialog class="lightbox" aria-label="<?= e(t('lb_label')) ?>">
  <button class="lb-btn lb-close" type="button" aria-label="<?= e(t('lb_close')) ?>">×</button>
  <button class="lb-btn lb-prev" type="button" aria-label="<?= e(t('lb_prev')) ?>">‹</button>
  <figure class="lb-figure"><img class="lb-img" alt=""><figcaption class="lb-cap" aria-live="polite"></figcaption></figure>
  <button class="lb-btn lb-next" type="button" aria-label="<?= e(t('lb_next')) ?>">›</button>
</dialog>
</body>
</html>
<?php
}

function wa_icon(): string
{
    return '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.3-.4.7-1.4.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6a2.7 2.7 0 0 0 1.8-1.3 2.2 2.2 0 0 0 .1-1.3c0-.1-.2-.2-.4-.3Z"/></svg>';
}

function mail_icon(): string
{
    return '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="none" stroke="currentColor" stroke-width="2" d="M3 5h18v14H3z M3 6l9 7 9-7"/></svg>';
}

/** Call-to-action band used at the end of most pages. */
function cta_band(string $title, string $text): void
{
    $mail = 'mailto:' . setting('email') . '?subject=' . rawurlencode(t('mail_subject'));
    ?>
<section class="cta-band reveal">
  <div class="wrap">
    <h2><?= e($title) ?></h2>
    <p><?= e($text) ?></p>
    <p class="btn-row">
      <a class="btn btn-wa" href="<?= e(whatsapp_link()) ?>" rel="noopener"><?= wa_icon() ?><span>WhatsApp</span></a>
      <a class="btn btn-ghost" href="<?= e($mail) ?>"><?= mail_icon() ?><span><?= e(setting('email')) ?></span></a>
    </p>
  </div>
</section>
<?php
}

/** A clickable video card: local poster; YouTube loads only after a click (site.js). Without JS it is a link. */
function video_card(array $v, string $size = 'normal'): string
{
    $id = $v['youtube'];
    $title = tr($v['title']);
    $poster = '/media/videos/' . $id . '-' . ($size === 'large' ? 1280 : 640) . '.webp';
    $credit = tr($v['credit'] ?? '');
    $kind = video_kinds()[$v['kind']] ?? null;
    return '<figure class="video-card video-' . e($size) . ' reveal">'
        . '<a class="video-play" href="https://www.youtube.com/watch?v=' . e($id) . '" data-yt="' . e($id) . '" rel="noopener">'
        . '<img src="' . e($poster) . '" width="1280" height="720" alt="" loading="lazy" decoding="async">'
        . '<span class="play-btn" aria-hidden="true"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M8 5v14l11-7z"/></svg></span>'
        . '<span class="visually-hidden">' . e(t('play')) . ': ' . e($title) . '</span>'
        . '</a>'
        . '<figcaption>'
        . ($kind ? '<span class="video-kind">' . e(tr($kind)) . '</span>' : '')
        . '<span class="video-title">' . e($title) . '</span>'
        . ($credit !== '' ? '<span class="video-credit">' . e($credit) . '</span>' : '')
        . '</figcaption></figure>';
}
