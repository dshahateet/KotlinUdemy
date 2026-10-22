<?php
require __DIR__ . '/_init.php';
require_admin();

$entities = require __DIR__ . '/entities.php';
$counts = [];
foreach ($entities as $key => $ent) {
    $counts[$key] = (int)q('SELECT COUNT(*) FROM `' . $ent['table'] . '`')->fetchColumn();
}
$unread = (int)q('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();
$latest = q('SELECT * FROM messages ORDER BY created_at DESC LIMIT 5')->fetchAll();

admin_header('Dashboard', 'dashboard');
?>
<div class="tiles">
  <a class="tile accent" href="<?= e(url('admin/messages.php')) ?>"><strong><?= $unread ?></strong><span>Unread messages</span></a>
  <?php foreach ($entities as $key => $ent): ?>
    <a class="tile" href="<?= e(url('admin/manage.php', ['e' => $key])) ?>"><strong><?= $counts[$key] ?></strong><span><?= e($ent['label']) ?></span></a>
  <?php endforeach; ?>
</div>

<h2>Latest messages</h2>
<?php if (!$latest): ?>
  <p class="muted">No messages yet.</p>
<?php else: ?>
<table class="table">
  <thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Service</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($latest as $m): ?>
    <tr class="<?= $m['is_read'] ? '' : 'unread' ?>">
      <td><?= e($m['created_at']) ?></td>
      <td><?= e($m['first_name'] . ' ' . $m['last_name']) ?></td>
      <td><?= e($m['email']) ?></td>
      <td><?= e($m['service']) ?></td>
      <td><a href="<?= e(url('admin/messages.php', ['id' => $m['id']])) ?>">Open</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<div class="note">
  <strong>Tip:</strong> Testimonials, the leadership team and partners are hidden until you add your real data and tick
  <em>“Visible on website”</em>. Phone, WhatsApp, address and social links are in <a href="<?= e(url('admin/settings.php')) ?>">Site Settings</a>.
</div>
<?php admin_footer();
