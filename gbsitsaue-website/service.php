<?php
require __DIR__ . '/includes/bootstrap.php';

$slug    = (string)($_GET['slug'] ?? '');
$service = q('SELECT * FROM services WHERE slug = ? AND is_active = 1', [$slug])->fetch();
if (!$service) {
    not_found();
}

$items    = q('SELECT * FROM service_items WHERE service_id = ? ORDER BY sort_order, id', [$service['id']])->fetchAll();
$benefits = q('SELECT * FROM service_benefits WHERE service_id = ? ORDER BY sort_order, id', [$service['id']])->fetchAll();
$faqs     = q('SELECT * FROM faqs WHERE service_id = ? ORDER BY sort_order, id', [$service['id']])->fetchAll();

$activeNav = 'services';
$pageTitle = f($service, 'title');
$pageDesc  = f($service, 'summary');
$preselect = $service['slug'];
$phone     = setting('phone');

require ROOT . '/partials/header.php';
?>
<section class="page-hero service-hero">
  <div class="container hero-split">
    <div>
      <span class="kicker kicker-light"><?= e(f($service, 'title')) ?></span>
      <h1><?= e(f($service, 'hero_title')) ?></h1>
      <p><?= e(f($service, 'summary')) ?></p>
      <?php if (f($service, 'tagline')): ?><p class="tagline"><?= e(f($service, 'tagline')) ?></p><?php endif; ?>
      <a class="btn btn-primary" href="#contact-form"><?= e(t('free_consultation')) ?></a>
    </div>
    <div class="hero-icon" aria-hidden="true"><?= icon($service['icon']) ?></div>
  </div>
</section>

<section class="section">
  <div class="container narrow">
    <span class="kicker"><?= e(t('our_approach')) ?></span>
    <h2 class="section-title"><?= e(f($service, 'intro_title')) ?></h2>
    <div class="prose"><?= f($service, 'body') /* trusted HTML from admin */ ?></div>
  </div>
</section>

<?php if ($items): ?>
<section class="section section-light">
  <div class="container">
    <h2 class="section-title center"><?= e(t('our_services')) ?></h2>
    <div class="grid items-grid">
      <?php foreach ($items as $it): ?>
        <div class="card item-card">
          <span class="icon-badge small"><?= icon('check') ?></span>
          <h3><?= e(f($it, 'title')) ?></h3>
          <p><?= e(f($it, 'description')) ?></p>
          <a class="link-more" href="#contact-form"><?= e(t('know_more')) ?><?= icon('arrow', 'icon flip-rtl') ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (f($service, 'why_text')): ?>
<section class="section">
  <div class="container narrow center">
    <h2 class="section-title"><?= e(t('why_choose_service')) ?></h2>
    <p class="lead"><?= e(f($service, 'why_text')) ?></p>
  </div>
</section>
<?php endif; ?>

<?php require ROOT . '/partials/contact-form.php'; ?>

<section class="section section-dark cta-band">
  <div class="container cta-inner">
    <div>
      <h2><?= e(f($service, 'cta_title')) ?></h2>
      <p><?= e(t('call_us')) ?>: <a href="<?= e(phone_href($phone)) ?>" dir="ltr"><?= e($phone) ?></a> &middot; <a href="mailto:<?= e(INFO_EMAIL) ?>"><?= e(INFO_EMAIL) ?></a></p>
    </div>
    <?php require ROOT . '/partials/stats.php'; ?>
  </div>
</section>

<?php if ($benefits): ?>
<section class="section">
  <div class="container">
    <h2 class="section-title center"><?= e(f($service, 'benefits_title')) ?></h2>
    <p class="lead center narrow"><?= e(f($service, 'benefits_intro')) ?></p>
    <div class="grid benefits-grid">
      <?php foreach ($benefits as $b): ?>
        <div class="card benefit-card">
          <span class="icon-badge"><?= icon($b['icon']) ?></span>
          <h3><?= e(f($b, 'title')) ?></h3>
          <p><?= e(f($b, 'description')) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="center mt">
      <a class="btn btn-primary" href="<?= e(phone_href($phone)) ?>"><?= icon('phone') ?><?= e(t('call_us_now')) ?></a>
      <a class="btn btn-outline" href="#contact-form"><?= e(t('free_consultation')) ?></a>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($faqs): ?>
<section class="section section-light">
  <div class="container narrow">
    <h2 class="section-title center"><?= e(t('faqs')) ?></h2>
    <div class="faqs">
      <?php foreach ($faqs as $i => $fq): ?>
        <details<?= $i === 0 ? ' open' : '' ?>>
          <summary><?= e(f($fq, 'question')) ?><?= icon('chevron') ?></summary>
          <p><?= e(f($fq, 'answer')) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
require ROOT . '/partials/partners.php';
require ROOT . '/partials/footer.php';
