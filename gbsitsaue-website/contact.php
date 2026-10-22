<?php
require __DIR__ . '/includes/bootstrap.php';

$activeNav = 'contact';
$pageTitle = t('contact_title');
$heroTitle = setting('contact_title', t('contact_title'));
$heroText  = setting('contact_subtitle');
$offices   = q('SELECT * FROM offices ORDER BY sort_order, id')->fetchAll();
$phone     = setting('phone');
$wa        = whatsapp_href();

require ROOT . '/partials/header.php';
require ROOT . '/partials/page-hero.php';
require ROOT . '/partials/contact-form.php';
?>
<section class="section section-light">
  <div class="container grid contact-cards">
    <div class="card info-card">
      <span class="icon-badge"><?= icon('map') ?></span>
      <h3><?= e(t('visit_us')) ?></h3>
      <p><?= nl2br(e(setting('address'))) ?></p>
    </div>
    <div class="card info-card">
      <span class="icon-badge"><?= icon('phone') ?></span>
      <h3><?= e(t('call_us')) ?></h3>
      <p><a href="<?= e(phone_href($phone)) ?>" dir="ltr"><?= e($phone) ?></a></p>
    </div>
    <div class="card info-card">
      <span class="icon-badge"><?= icon('mail') ?></span>
      <h3><?= e(t('email_us')) ?></h3>
      <p><a href="mailto:<?= e(INFO_EMAIL) ?>"><?= e(INFO_EMAIL) ?></a></p>
    </div>
    <div class="card info-card">
      <span class="icon-badge"><?= icon('users') ?></span>
      <h3><?= e(t('follow_us')) ?></h3>
      <p class="social social-dark">
        <?php if (setting('facebook')): ?><a href="<?= e(setting('facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a><?php endif; ?>
        <?php if (setting('linkedin')): ?><a href="<?= e(setting('linkedin')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?= icon('linkedin') ?></a><?php endif; ?>
        <?php if ($wa): ?><a href="<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?= icon('whatsapp') ?></a><?php endif; ?>
      </p>
    </div>
  </div>
</section>

<?php if (setting('map_embed_url')): ?>
<section class="map-section">
  <iframe src="<?= e(setting('map_embed_url')) ?>" title="Map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
</section>
<?php endif; ?>

<?php
require ROOT . '/partials/partners.php';
require ROOT . '/partials/footer.php';
