<?php
/** Render a legal page from [heading => [paragraphs]] for the current language. */
function legal_page(string $page, string $title, string $updated, array $sections, string $intro): void
{
    $lang = $GLOBALS['lang'];
    page_start($title, $intro, ['page' => $page, 'canonical' => url($page), 'alt' => url($page, $lang === 'fi' ? 'en' : 'fi')]);
    ?>
<article class="wrap legal">
  <header class="page-head">
    <p class="kicker">Kuvadoo · <?= e(t('ytunnus')) ?> <?= e(setting('ytunnus')) ?></p>
    <h1 class="page-title"><?= e($title) ?></h1>
    <p class="lead"><?= e($intro) ?></p>
    <p class="muted"><?= e($updated) ?></p>
  </header>
<?php foreach ($sections as $h => $ps): ?>
  <section>
    <h2><?= e($h) ?></h2>
<?php foreach ($ps as $p): ?>
<?php if (is_array($p)): ?>
    <ul>
<?php foreach ($p as $li): ?>
      <li><?= $li ?></li>
<?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><?= $p ?></p>
<?php endif; ?>
<?php endforeach; ?>
  </section>
<?php endforeach; ?>
</article>
<?php
    page_end();
}
