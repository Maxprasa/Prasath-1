<?php
/** Price cards for one group (photo or video). */
function price_group(array $g): void
{
    $items = array_values(array_filter($g['items'], fn($i) => !empty($i['visible'])));
    if (!$items) {
        return;
    }
    ?>
  <section class="price-group" aria-labelledby="pg-<?= e($g['id']) ?>">
    <h2 id="pg-<?= e($g['id']) ?>" class="section-title reveal"><?= hf(e(tr($g['title']))) ?></h2>
    <ul class="price-list">
<?php foreach ($items as $it): $lines = array_filter(array_map('trim', preg_split('/\R/', tr($it['includes'])))); ?>
      <li class="price-card reveal">
        <h3 class="price-name"><?= e(tr($it['name'])) ?></h3>
        <p class="price-amount"><?php if ($it['price'] === null || $it['price'] === ''): ?><?= e(t('by_offer')) ?><?php else: ?><?php if (!empty($it['from'])): ?><span class="price-from"><?= e(t('from')) ?></span> <?php endif; ?><?= e(money((int) $it['price'])) ?><?php endif; ?></p>
<?php if ($lines): ?>
        <ul class="price-includes">
<?php foreach ($lines as $l): ?>
          <li><?= e($l) ?></li>
<?php endforeach; ?>
        </ul>
<?php endif; ?>
      </li>
<?php endforeach; ?>
    </ul>
  </section>
<?php
}
