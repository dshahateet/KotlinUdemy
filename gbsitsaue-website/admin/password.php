<?php
require __DIR__ . '/_init.php';
require_admin();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_csrf();
    $user = q('SELECT * FROM admins WHERE id = ?', [$_SESSION['admin_id']])->fetch();
    $current = (string)($_POST['current'] ?? '');
    $new     = (string)($_POST['new'] ?? '');
    $confirm = (string)($_POST['confirm'] ?? '');

    if (!$user || !password_verify($current, $user['password_hash'])) {
        $errors[] = 'Current password is incorrect.';
    }
    if (strlen($new) < 10) {
        $errors[] = 'New password must be at least 10 characters.';
    }
    if ($new !== $confirm) {
        $errors[] = 'New passwords do not match.';
    }
    if (!$errors) {
        q('UPDATE admins SET password_hash = ? WHERE id = ?', [password_hash($new, PASSWORD_DEFAULT), $user['id']]);
        flash('admin_ok', 'Password changed.');
        redirect(url('admin/password.php'));
    }
}

admin_header('Change Password', 'password');
foreach ($errors as $err): ?><div class="alert err"><?= e($err) ?></div><?php endforeach; ?>
<form method="post" class="card narrow-form">
  <?= csrf_field() ?>
  <label>Current password<input type="password" name="current" required autocomplete="current-password"></label>
  <label>New password (min. 10 characters)<input type="password" name="new" required minlength="10" autocomplete="new-password"></label>
  <label>Confirm new password<input type="password" name="confirm" required minlength="10" autocomplete="new-password"></label>
  <button class="btn" type="submit">Update password</button>
</form>
<?php admin_footer();
