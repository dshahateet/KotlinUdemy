<?php
if (!defined('ROOT')) {
    require __DIR__ . '/includes/bootstrap.php';
    http_response_code(404);
}
$pageTitle = t('not_found');
require ROOT . '/partials/header.php';
?>
<section class="section not-found">
  <div class="container narrow center">
    <p class="big-404">404</p>
    <h1><?= e(t('not_found')) ?></h1>
    <p class="lead"><?= e(t('not_found_text')) ?></p>
    <a class="btn btn-primary" href="<?= e(url('')) ?>"><?= e(t('back_home')) ?></a>
  </div>
</section>
<?php
require ROOT . '/partials/footer.php';
