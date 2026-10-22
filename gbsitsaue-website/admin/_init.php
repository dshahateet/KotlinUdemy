<?php
require dirname(__DIR__) . '/includes/bootstrap.php';

header('X-Robots-Tag: noindex, nofollow');

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (!admin_logged_in()) {
        redirect(url('admin/index.php'));
    }
    // Expire idle sessions after 2 hours.
    if (isset($_SESSION['admin_seen']) && time() - $_SESSION['admin_seen'] > 7200) {
        session_unset();
        redirect(url('admin/index.php'));
    }
    $_SESSION['admin_seen'] = time();
}

function require_post_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
        http_response_code(400);
        exit('Invalid request (CSRF).');
    }
}

function admin_header(string $title, string $active = ''): void
{
    $entities = require __DIR__ . '/entities.php';
    $unread = (int)q('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();
    ?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> — Admin</title>
<link rel="icon" href="<?= e(asset('img/favicon.svg')) ?>">
<link rel="stylesheet" href="<?= e(url('admin/admin.css')) ?>?v=<?= e(ASSET_VERSION) ?>">
</head>
<body class="admin">
<aside class="sidebar">
  <a class="brand" href="<?= e(url('admin/dashboard.php')) ?>"><img src="<?= e(asset('img/logo.svg')) ?>" alt=""><span><?= e(site_name()) ?></span></a>
  <nav>
    <a href="<?= e(url('admin/dashboard.php')) ?>" class="<?= $active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
    <a href="<?= e(url('admin/messages.php')) ?>" class="<?= $active === 'messages' ? 'active' : '' ?>">Messages<?= $unread ? ' <b class="badge">' . $unread . '</b>' : '' ?></a>
    <a href="<?= e(url('admin/settings.php')) ?>" class="<?= $active === 'settings' ? 'active' : '' ?>">Site Settings</a>
    <span class="nav-label">Content</span>
    <?php foreach ($entities as $key => $ent): ?>
      <a href="<?= e(url('admin/manage.php', ['e' => $key])) ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= e($ent['label']) ?></a>
    <?php endforeach; ?>
    <span class="nav-label">Account</span>
    <a href="<?= e(url('admin/password.php')) ?>" class="<?= $active === 'password' ? 'active' : '' ?>">Change Password</a>
    <a href="<?= e(url('')) ?>" target="_blank">View Website ↗</a>
    <a href="<?= e(url('admin/logout.php')) ?>">Log out</a>
  </nav>
</aside>
<main class="content">
  <h1><?= e($title) ?></h1>
  <?php if ($m = flash('admin_ok')): ?><div class="alert ok"><?= e($m) ?></div><?php endif; ?>
  <?php if ($m = flash('admin_err')): ?><div class="alert err"><?= e($m) ?></div><?php endif; ?>
<?php
}

function admin_footer(): void
{
    echo "</main>\n</body>\n</html>\n";
}
