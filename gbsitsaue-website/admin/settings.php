<?php
require __DIR__ . '/_init.php';
require_admin();

$rows = q('SELECT * FROM settings ORDER BY sort_order, skey')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_csrf();
    $st = db()->prepare('UPDATE settings SET value_en = ?, value_ar = ? WHERE skey = ?');
    foreach ($rows as $r) {
        $k = $r['skey'];
        $st->execute([trim((string)($_POST['en'][$k] ?? '')), trim((string)($_POST['ar'][$k] ?? '')), $k]);
    }
    flash('admin_ok', 'Settings saved.');
    redirect(url('admin/settings.php'));
}

admin_header('Site Settings', 'settings');
?>
<p class="muted">Contact email is <strong><?= e(INFO_EMAIL) ?></strong> (change it in <code>config.php</code>).</p>
<form method="post" class="settings-form">
  <?= csrf_field() ?>
  <table class="table">
    <thead><tr><th style="width:22%">Setting</th><th>English</th><th>العربية</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r):
        $long = mb_strlen((string)$r['value_en']) > 60 || str_contains((string)$r['value_en'], "\n"); ?>
      <tr>
        <td><strong><?= e($r['label'] ?: $r['skey']) ?></strong><br><code><?= e($r['skey']) ?></code></td>
        <?php foreach (['en', 'ar'] as $l): $v = $r['value_' . $l]; ?>
          <td>
            <?php if ($long): ?>
              <textarea name="<?= $l ?>[<?= e($r['skey']) ?>]" rows="3"<?= $l === 'ar' ? ' dir="rtl"' : '' ?>><?= e($v) ?></textarea>
            <?php else: ?>
              <input type="text" name="<?= $l ?>[<?= e($r['skey']) ?>]" value="<?= e($v) ?>"<?= $l === 'ar' ? ' dir="rtl"' : '' ?>>
            <?php endif; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p><button class="btn" type="submit">Save settings</button></p>
</form>
<?php admin_footer();
