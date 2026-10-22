<?php
require __DIR__ . '/includes/bootstrap.php';

$activeNav    = 'home';
$pageTitle    = setting('meta_title', site_name());
$features     = q('SELECT * FROM features ORDER BY sort_order, id')->fetchAll();
$testimonials = q('SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
$team         = q('SELECT * FROM team WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
$offices      = q('SELECT * FROM offices ORDER BY sort_order, id')->fetchAll();

require ROOT . '/partials/header.php';
?>

<section class="hero">
  <div class="hero-bg" aria-hidden="true"></div>
  <div class="container hero-inner">
    <div class="hero-text">
      <span class="kicker kicker-light"><?= e(t('home_hero_kicker')) ?></span>
      <h1><?= e(setting('hero_title')) ?></h1>
      <p><?= e(setting('hero_subtitle')) ?></p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="<?= e(url('services.php')) ?>"><?= e(t('our_services')) ?><?= icon('arrow', 'icon flip-rtl') ?></a>
        <a class="btn btn-outline-light" href="<?= e(url('contact.php')) ?>"><?= e(t('free_consultation')) ?></a>
      </div>
    </div>
    <div class="hero-visual" aria-hidden="true">
      <?php foreach (array_slice(active_services(), 0, 5) as $i => $s): ?>
        <div class="orbit-card orbit-<?= $i ?>"><?= icon($s['icon']) ?><span><?= e(f($s, 'title')) ?></span></div>
      <?php endforeach; ?>
      <div class="orbit-core"><img src="<?= e(asset('img/logo.svg')) ?>" alt=""></div>
    </div>
  </div>
</section>

<?php if ($testimonials): ?>
<section class="section section-light">
  <div class="container">
    <h2 class="section-title center"><?= e(t('testimonials')) ?></h2>
    <div class="slider" data-slider>
      <div class="slides">
        <?php foreach ($testimonials as $i => $tm): ?>
          <figure class="testimonial slide<?= $i === 0 ? ' is-active' : '' ?>">
            <?= icon('quote', 'icon quote-icon') ?>
            <blockquote><?= e(f($tm, 'quote')) ?></blockquote>
            <figcaption><strong><?= e(f($tm, 'name')) ?></strong><span><?= e(f($tm, 'role')) ?></span></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
      <?php if (count($testimonials) > 1): ?>
      <div class="dots">
        <?php foreach ($testimonials as $i => $tm): ?>
          <button class="dot<?= $i === 0 ? ' is-active' : '' ?>" aria-label="<?= $i + 1 ?>"></button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section about-home">
  <div class="container two-col">
    <div>
      <span class="kicker"><?= e(t('who_we_are')) ?></span>
      <h2 class="section-title"><?= e(setting('about_title')) ?></h2>
      <p class="lead"><?= e(setting('about_text')) ?></p>
      <a class="btn btn-primary" href="<?= e(url('about.php')) ?>"><?= e(t('know_more')) ?><?= icon('arrow', 'icon flip-rtl') ?></a>
    </div>
    <div class="about-card">
      <?php require ROOT . '/partials/stats.php'; ?>
    </div>
  </div>
</section>

<section class="section section-light">
  <div class="container">
    <span class="kicker center-block"><?= e(t('our_services')) ?></span>
    <h2 class="section-title center"><?= e(t('featured_services')) ?></h2>
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

<?php if ($team): ?>
<section class="section">
  <div class="container">
    <h2 class="section-title center"><?= e(t('our_leadership')) ?></h2>
    <div class="grid team-grid">
      <?php foreach ($team as $m): ?>
        <div class="team-card">
          <div class="avatar">
            <?php if ($m['photo']): ?><img src="<?= e(upload_url($m['photo'])) ?>" alt="<?= e(f($m, 'name')) ?>" loading="lazy">
            <?php else: ?><span><?= e(mb_substr(f($m, 'name'), 0, 1)) ?></span><?php endif; ?>
          </div>
          <h3><?= e(f($m, 'name')) ?></h3>
          <p><?= e(f($m, 'position')) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section-dark why-section">
  <div class="container">
    <div class="why-head">
      <h2 class="section-title"><?= e(t('why_us')) ?></h2>
      <p><?= e(setting('why_text')) ?></p>
    </div>
    <div class="grid features-grid">
      <?php foreach ($features as $ft): ?>
        <div class="feature">
          <span class="icon-badge"><?= icon($ft['icon']) ?></span>
          <span class="feature-label"><?= e(f($ft, 'label')) ?></span>
          <h3><?= e(f($ft, 'title')) ?></h3>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="value-box">
      <h3><?= e(t('value_props')) ?></h3>
      <p><?= e(setting('value_text')) ?></p>
    </div>
  </div>
</section>

<?php if ($offices): ?>
<section class="section">
  <div class="container">
    <h2 class="section-title center"><?= e(t('our_offices')) ?></h2>
    <div class="grid offices-grid">
      <?php foreach ($offices as $o): ?>
        <div class="card office-card">
          <span class="icon-badge"><?= icon('map') ?></span>
          <h3><?= e(f($o, 'title')) ?></h3>
          <p><?= nl2br(e(f($o, 'address'))) ?></p>
          <?php if ($o['phone']): ?><a href="<?= e(phone_href($o['phone'])) ?>" dir="ltr"><?= e($o['phone']) ?></a><?php endif; ?>
          <?php if ($o['email']): ?><a href="mailto:<?= e($o['email']) ?>"><?= e($o['email']) ?></a><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require ROOT . '/partials/partners.php'; ?>
<?php require ROOT . '/partials/footer.php'; ?>
