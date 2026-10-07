<?php
require __DIR__ . '/layout.php';
page_start(t('notfound_title'), t('notfound_text'), ['noindex' => true]);
?>
<div class="wrap page-head notfound">
  <p class="kicker">404</p>
  <h1 class="page-title"><?= e(t('notfound_title')) ?></h1>
  <p class="lead"><?= e(t('notfound_text')) ?></p>
  <p class="btn-row"><a class="btn btn-primary" href="<?= e(url('home')) ?>"><?= e(t('notfound_home')) ?></a> <a class="btn btn-ghost" href="<?= e(url('photo')) ?>"><?= e(t('nav_photo')) ?></a></p>
</div>
<?php page_end();
