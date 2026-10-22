<?php
require __DIR__ . '/_init.php';

if (admin_logged_in()) {
    redirect(url('admin/dashboard.php'));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0);
    $lockedUntil = $_SESSION['login_locked_until'] ?? 0;

    if (!csrf_valid()) {
        $error = 'Session expired, please try again.';
    } elseif ($lockedUntil > time()) {
        $error = 'Too many attempts. Please wait a few minutes.';
    } else {
        $user = q('SELECT * FROM admins WHERE username = ?', [trim((string)($_POST['username'] ?? ''))])->fetch();
        if ($user && password_verify((string)($_POST['password'] ?? ''), $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$user['id'];
            $_SESSION['admin_user'] = $user['username'];
            $_SESSION['admin_seen'] = time();
            unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
            redirect(url('admin/dashboard.php'));
        }
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= 5) {
            $_SESSION['login_locked_until'] = time() + 300;
            $_SESSION['login_attempts'] = 0;
        }
        $error = 'Invalid username or password.';
    }
}
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Admin Login — <?= e(site_name()) ?></title>
<link rel="icon" href="<?= e(asset('img/favicon.svg')) ?>">
<link rel="stylesheet" href="<?= e(url('admin/admin.css')) ?>?v=<?= e(ASSET_VERSION) ?>">
</head>
<body class="login-page">
  <form class="login-box" method="post">
    <img src="<?= e(asset('img/logo.svg')) ?>" alt="" width="56" height="56">
    <h1><?= e(site_name()) ?> Admin</h1>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
    <?= csrf_field() ?>
    <label>Username<input type="text" name="username" required autofocus autocomplete="username"></label>
    <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
    <button class="btn" type="submit">Log in</button>
  </form>
</body>
</html>
