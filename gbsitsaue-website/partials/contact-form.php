<?php
/** Contact / free consultation form. Optional: $preselect (service slug). */
$preselect = $preselect ?? '';
$success   = flash('contact_success');
$error     = flash('contact_error');
$old       = $_SESSION['contact_old'] ?? [];
unset($_SESSION['contact_old']);
$return    = strtok($_SERVER['REQUEST_URI'] ?? '/', '#') . '#contact-form';
?>
<section class="section contact-section" id="contact-form">
  <div class="container contact-grid">
    <div class="contact-intro">
      <span class="kicker"><?= e(t('reach_out')) ?></span>
      <h2><?= e(t('get_free_consult')) ?></h2>
      <p><?= e(setting('contact_intro')) ?></p>
      <p class="muted"><?= e(t('form_intro')) ?></p>
      <ul class="contact-points">
        <li><?= icon('mail') ?><div><small><?= e(t('email_us')) ?></small><a href="mailto:<?= e(INFO_EMAIL) ?>"><?= e(INFO_EMAIL) ?></a></div></li>
        <?php if (setting('phone')): ?>
        <li><?= icon('phone') ?><div><small><?= e(t('call_us')) ?></small><a href="<?= e(phone_href(setting('phone'))) ?>" dir="ltr"><?= e(setting('phone')) ?></a></div></li>
        <?php endif; ?>
      </ul>
    </div>

    <form class="card form" method="post" action="<?= e(url('contact-submit.php')) ?>" novalidate>
      <?php if ($success): ?><div class="alert alert-success" role="status"><?= e($success) ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-error" role="alert"><?= e($error) ?></div><?php endif; ?>

      <?= csrf_field() ?>
      <input type="hidden" name="return" value="<?= e($return) ?>">
      <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

      <div class="form-row">
        <label><span><?= e(t('first_name')) ?> *</span><input type="text" name="first_name" required maxlength="100" value="<?= e($old['first_name'] ?? '') ?>"></label>
        <label><span><?= e(t('last_name')) ?> *</span><input type="text" name="last_name" required maxlength="100" value="<?= e($old['last_name'] ?? '') ?>"></label>
      </div>
      <div class="form-row">
        <label><span><?= e(t('company')) ?></span><input type="text" name="company" maxlength="150" value="<?= e($old['company'] ?? '') ?>"></label>
        <label><span><?= e(t('email')) ?> *</span><input type="email" name="email" required maxlength="150" value="<?= e($old['email'] ?? '') ?>"></label>
      </div>
      <div class="form-row">
        <label><span><?= e(t('phone')) ?> *</span><input type="tel" name="phone" required maxlength="40" dir="ltr" value="<?= e($old['phone'] ?? '') ?>"></label>
        <label><span><?= e(t('select_service')) ?></span>
          <select name="service">
            <option value=""><?= e(t('select_service')) ?></option>
            <?php $sel = $old['service'] ?? $preselect; ?>
            <?php foreach (active_services() as $s): ?>
              <option value="<?= e($s['slug']) ?>" <?= $sel === $s['slug'] ? 'selected' : '' ?>><?= e(f($s, 'title')) ?></option>
            <?php endforeach; ?>
            <option value="other" <?= $sel === 'other' ? 'selected' : '' ?>><?= e(t('other')) ?></option>
          </select>
        </label>
      </div>
      <label><span><?= e(t('message')) ?></span><textarea name="message" rows="5" maxlength="5000"><?= e($old['message'] ?? '') ?></textarea></label>
      <button type="submit" class="btn btn-primary btn-block"><?= e(t('submit')) ?></button>
    </form>
  </div>
</section>
