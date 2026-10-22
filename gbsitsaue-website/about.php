<?php
require __DIR__ . '/includes/bootstrap.php';

$activeNav = 'about';
$pageTitle = t('nav_about');
$heroTitle = setting('about_page_title', t('nav_about'));
$heroText  = setting('about_page_subtitle');
$team      = q('SELECT * FROM team WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
$features  = q('SELECT * FROM features ORDER BY sort_order, id')->fetchAll();

require ROOT . '/partials/header.php';
require ROOT . '/partials/page-hero.php';
?>
<section class="section">
  <div class="container two-col">
    <div>
      <span class="kicker"><?= e(t('who_we_are')) ?></span>
      <h2 class="section-title"><?= e(setting('about_title')) ?></h2>
      <p class="lead"><?= e(setting('about_text')) ?></p>
      <p><?= e(setting('about_text_2')) ?></p>
    </div>
    <div class="about-card"><?php require ROOT . '/partials/stats.php'; ?></div>
  </div>
</section>

<section class="section section-light">
  <div class="container grid mvv-grid">
    <div class="card"><span class="icon-badge"><?= icon('trending') ?></span><h3><?= e(t('mission')) ?></h3><p><?= e(setting('mission')) ?></p></div>
    <div class="card"><span class="icon-badge"><?= icon('globe') ?></span><h3><?= e(t('vision')) ?></h3><p><?= e(setting('vision')) ?></p></div>
    <div class="card"><span class="icon-badge"><?= icon('star') ?></span><h3><?= e(t('values')) ?></h3><p><?= e(setting('values')) ?></p></div>
  </div>
</section>

<?php if ($team): ?>
<section class="section" id="team">
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
  </div>
</section>

<?php
require ROOT . '/partials/partners.php';
require ROOT . '/partials/contact-form.php';
require ROOT . '/partials/footer.php';
