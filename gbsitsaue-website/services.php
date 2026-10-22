<?php
require __DIR__ . '/includes/bootstrap.php';

$activeNav  = 'services';
$pageTitle  = t('our_services');
$heroTitle  = t('our_services');
$heroText   = setting('services_intro');

require ROOT . '/partials/header.php';
require ROOT . '/partials/page-hero.php';
?>
<section class="section">
  <div class="container">
    <div class="grid services-grid">
      <?php foreach (active_services() as $s): ?>
        <a class="card service-card" href="<?= e(service_url($s)) ?>">
          <span class="icon-badge"><?= icon($s['icon']) ?></span>
          <h3><?= e(f($s, 'title')) ?></h3>
          <p><?= e(f($s, 'summary')) ?></p>
          <span class="link-more"><?= e(t('know_more')) ?><?= icon('arrow', 'icon flip-rtl') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php
require ROOT . '/partials/contact-form.php';
require ROOT . '/partials/footer.php';
