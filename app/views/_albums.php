<?php
/** Grid of album cards. */
function album_cards(array $list): void
{
    ?>
  <ul class="album-grid">
<?php foreach ($list as $a): $cover = photo($a['cover']) ?? photo($a['photos'][0]); ?>
    <li class="album-card reveal">
      <a href="<?= e(url('album', null, ['cat' => $a['category'], 'album' => $a['slug']])) ?>">
        <span class="album-img"><?= img($cover, '(min-width: 900px) 33vw, (min-width: 600px) 50vw, 100vw') ?></span>
        <span class="album-meta">
          <span class="album-cat"><?= count($a['photos']) ?> <?= e(t('photos_count')) ?></span>
          <span class="album-title"><?= e(tr($a['title'])) ?></span>
        </span>
      </a>
    </li>
<?php endforeach; ?>
  </ul>
<?php
}

/** Category chips (only categories that have photos). */
function category_chips(?string $active = null): void
{
    $cats = array_filter(categories(), fn($k) => albums($k) !== [], ARRAY_FILTER_USE_KEY);
    if (!$cats) {
        return;
    }
    ?>
  <nav class="chips" aria-label="<?= e(t('galleries')) ?>">
    <a class="chip" href="<?= e(url('photo')) ?>"<?= $active === null ? ' aria-current="page"' : '' ?>><?= e(t('cta_all_photos')) ?></a>
<?php foreach ($cats as $key => $c): ?>
    <a class="chip" href="<?= e(url('cat', null, ['cat' => $key])) ?>"<?= $active === $key ? ' aria-current="page"' : '' ?>><?= e(tr($c['name'])) ?></a>
<?php endforeach; ?>
  </nav>
<?php
}

function breadcrumbs_ld(array $items): array
{
    $list = [];
    foreach ($items as $i => [$name, $path]) {
        $list[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => SITE_URL . $path];
    }
    return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list];
}
