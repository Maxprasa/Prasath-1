<?php
require __DIR__ . '/layout.php';
$lang = $GLOBALS['lang'];
$mail = 'mailto:' . setting('email') . '?subject=' . rawurlencode(t('mail_subject'));
page_start(txt('contact_title'), txt('contact_lead'), [
    'page' => 'contact', 'canonical' => url('contact'), 'alt' => url('contact', $lang === 'fi' ? 'en' : 'fi'),
]);
?>
<header class="page-head wrap">
  <p class="kicker">Kuvadoo · Hämeen Films</p>
  <h1 class="page-title"><?= e(txt('contact_title')) ?></h1>
  <p class="lead"><?= e(txt('contact_lead')) ?></p>
</header>
<div class="wrap contact-grid">
  <a class="contact-card contact-wa reveal" href="<?= e(whatsapp_link()) ?>" rel="noopener">
    <?= wa_icon() ?><span class="contact-label">WhatsApp</span><span class="contact-value"><?= e(phone_display()) ?></span>
  </a>
  <a class="contact-card reveal" href="<?= e($mail) ?>">
    <?= mail_icon() ?><span class="contact-label"><?= e(t('cta_email')) ?></span><span class="contact-value"><?= e(setting('email')) ?></span>
  </a>
</div>
<div class="wrap section">
  <ol class="steps">
<?php for ($i = 1; $i <= 4; $i++): ?>
    <li class="step reveal"><span class="step-num" aria-hidden="true"><?= $i ?></span><p><?= e(txt("step$i")) ?></p></li>
<?php endfor; ?>
  </ol>
  <dl class="facts reveal">
    <div><dt><?= $lang === 'fi' ? 'Yritys' : 'Business' ?></dt><dd>Kuvadoo (<?= e(t('sole_trader')) ?>)</dd></div>
    <div><dt><?= e(t('ytunnus')) ?></dt><dd><?= e(setting('ytunnus')) ?></dd></div>
    <div><dt><?= $lang === 'fi' ? 'Kotipaikka' : 'Based in' ?></dt><dd><?= e(setting('town')) ?>, <?= $lang === 'fi' ? 'Suomi' : 'Finland' ?></dd></div>
    <div><dt><?= $lang === 'fi' ? 'Videopalvelu' : 'Video service' ?></dt><dd><span lang="fi">Hämeen Films</span> – <?= e(t('films_tagline')) ?></dd></div>
  </dl>
  <p class="muted"><?= $lang === 'fi' ? 'Kun lähetät viestin, käsittelemme tietojasi tietosuojaselosteen mukaisesti.' : 'When you send a message, we handle your data as described in the privacy notice.' ?> <a href="<?= e(url('privacy')) ?>"><?= e(t('footer_privacy')) ?></a></p>
</div>
<?php page_end();
