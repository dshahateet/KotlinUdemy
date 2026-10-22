<?php
require __DIR__ . '/_init.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_csrf();
    $mid = (int)($_POST['id'] ?? 0);
    if (($_POST['do'] ?? '') === 'delete') {
        q('DELETE FROM messages WHERE id = ?', [$mid]);
        flash('admin_ok', 'Message deleted.');
    } elseif (($_POST['do'] ?? '') === 'unread') {
        q('UPDATE messages SET is_read = 0 WHERE id = ?', [$mid]);
    }
    redirect(url('admin/messages.php'));
}

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $m = q('SELECT * FROM messages WHERE id = ?', [$id])->fetch();
    if (!$m) {
        redirect(url('admin/messages.php'));
    }
    q('UPDATE messages SET is_read = 1 WHERE id = ?', [$id]);
    admin_header('Message #' . $id, 'messages');
    ?>
    <p><a href="<?= e(url('admin/messages.php')) ?>">← All messages</a></p>
    <div class="card">
      <dl class="msg">
        <dt>Date</dt><dd><?= e($m['created_at']) ?></dd>
        <dt>Name</dt><dd><?= e($m['first_name'] . ' ' . $m['last_name']) ?></dd>
        <dt>Company</dt><dd><?= e($m['company']) ?></dd>
        <dt>Email</dt><dd><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></dd>
        <dt>Phone</dt><dd><a href="<?= e(phone_href($m['phone'])) ?>"><?= e($m['phone']) ?></a></dd>
        <dt>Service</dt><dd><?= e($m['service']) ?></dd>
        <dt>Language</dt><dd><?= e($m['lang']) ?></dd>
        <dt>Sent from</dt><dd><?= e($m['page']) ?></dd>
        <dt>IP</dt><dd><?= e($m['ip']) ?></dd>
        <dt>Message</dt><dd class="msg-body"><?= nl2br(e($m['message'])) ?></dd>
      </dl>
      <div class="row-actions">
        <a class="btn" href="mailto:<?= e($m['email']) ?>">Reply by email</a>
        <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="do" value="unread"><button class="btn ghost">Mark as unread</button></form>
        <form method="post" onsubmit="return confirm('Delete this message?');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="do" value="delete"><button class="btn danger">Delete</button></form>
      </div>
    </div>
    <?php
    admin_footer();
    exit;
}

$rows = q('SELECT * FROM messages ORDER BY created_at DESC LIMIT 500')->fetchAll();
admin_header('Messages', 'messages');
?>
<table class="table">
  <thead><tr><th>Date</th><th>Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Service</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($rows as $m): ?>
    <tr class="<?= $m['is_read'] ? '' : 'unread' ?>">
      <td><?= e($m['created_at']) ?></td>
      <td><?= e($m['first_name'] . ' ' . $m['last_name']) ?></td>
      <td><?= e($m['company']) ?></td>
      <td><?= e($m['email']) ?></td>
      <td><?= e($m['phone']) ?></td>
      <td><?= e($m['service']) ?></td>
      <td><a href="<?= e(url('admin/messages.php', ['id' => $m['id']])) ?>">Open</a></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="7" class="muted">No messages yet.</td></tr><?php endif; ?>
  </tbody>
</table>
<?php admin_footer();
