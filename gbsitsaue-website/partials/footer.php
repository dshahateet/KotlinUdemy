<?php
$phone   = setting('phone');
$address = setting('address');
$wa      = whatsapp_href();
?>
</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col footer-brand">
      <a class="logo logo-light" href="<?= e(url('')) ?>">
        <img src="<?= e(asset('img/logo.svg')) ?>" alt="" width="44" height="44">
        <span class="logo-text"><strong><?= e(site_name()) ?></strong><small><?= e(setting('logo_tagline')) ?></small></span>
      </a>
      <p><?= e(setting('footer_about')) ?></p>
      <div class="social">
        <?php if (setting('linkedin')): ?><a href="<?= e(setting('linkedin')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?= icon('linkedin') ?></a><?php endif; ?>
        <?php if (setting('facebook')): ?><a href="<?= e(setting('facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a><?php endif; ?>
        <?php if ($wa): ?><a href="<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?= icon('whatsapp') ?></a><?php endif; ?>
      </div>
    </div>

    <div class="footer-col">
      <h2><?= e(t('footer_discover')) ?></h2>
      <ul>
        <?php foreach (active_services() as $s): ?>
          <li><a href="<?= e(service_url($s)) ?>"><?= e(f($s, 'title')) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="footer-col">
      <h2><?= e(t('footer_more')) ?></h2>
      <ul>
        <li><a href="<?= e(url('blog.php')) ?>"><?= e(t('thought_leadership')) ?></a></li>
        <li><a href="<?= e(url('case-studies.php')) ?>"><?= e(t('case_study')) ?></a></li>
      </ul>
      <h2 class="mt"><?= e(t('footer_about')) ?></h2>
      <ul>
        <li><a href="<?= e(url('about.php')) ?>"><?= e(t('nav_about')) ?></a></li>
        <li><a href="<?= e(url('about.php')) ?>#team"><?= e(t('our_team')) ?></a></li>
        <li><a href="<?= e(url('contact.php')) ?>"><?= e(t('our_office')) ?></a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h2><?= e(t('footer_connect')) ?></h2>
      <ul class="contact-list">
        <li><?= icon('mail') ?><a href="mailto:<?= e(INFO_EMAIL) ?>"><?= e(INFO_EMAIL) ?></a></li>
        <?php if ($phone): ?><li><?= icon('phone') ?><a href="<?= e(phone_href($phone)) ?>" dir="ltr"><?= e($phone) ?></a></li><?php endif; ?>
        <?php if ($address): ?><li><?= icon('map') ?><span><?= nl2br(e($address)) ?></span></li><?php endif; ?>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <p>&copy; <?= date('Y') ?> <?= e(site_name()) ?> &mdash; <?= e(SITE_DOMAIN) ?>. <?= e(t('all_rights')) ?></p>
    </div>
  </div>
</footer>

<?php if ($wa): ?>
<a class="whatsapp-float" href="<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="<?= e(t('chat_with_us')) ?>"><?= icon('whatsapp') ?><span><?= e(t('chat_with_us')) ?></span></a>
<?php endif; ?>
<button class="scroll-top" id="scrollTop" aria-label="<?= e(t('scroll_top')) ?>"><?= icon('up') ?></button>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
</body>
</html>
