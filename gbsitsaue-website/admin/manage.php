<?php
/** Generic list / create / edit / delete screen for every entry in entities.php. */
require __DIR__ . '/_init.php';
require_admin();

$entities = require __DIR__ . '/entities.php';
$key = (string)($_GET['e'] ?? '');
if (!isset($entities[$key])) {
    redirect(url('admin/dashboard.php'));
}
$ent    = $entities[$key];
$table  = $ent['table'];
$fields = $ent['fields'];
$action = (string)($_GET['a'] ?? 'list');
$id     = (int)($_GET['id'] ?? 0);
$self   = fn(array $p = []) => url('admin/manage.php', ['e' => $key] + $p);

const IMAGE_TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif', 'image/svg+xml' => 'svg'];

function save_upload(string $field): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Upload failed or file larger than 5 MB.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!isset(IMAGE_TYPES[$mime])) {
        throw new RuntimeException('Only JPG, PNG, WEBP, GIF or SVG images are allowed.');
    }
    if ($mime === 'image/svg+xml' && preg_match('/<script|on\w+\s*=|javascript:/i', (string)file_get_contents($f['tmp_name']))) {
        throw new RuntimeException('SVG contains scripts and was rejected.');
    }
    $name = date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . IMAGE_TYPES[$mime];
    if (!move_uploaded_file($f['tmp_name'], ROOT . '/uploads/' . $name)) {
        throw new RuntimeException('Could not save the uploaded file. Check that /uploads is writable.');
    }
    return $name;
}

function fk_options(array $def): array
{
    $rows = q('SELECT id, `' . $def['refLabel'] . '` AS label FROM `' . $def['ref'] . '` ORDER BY id')->fetchAll();
    return array_column($rows, 'label', 'id');
}

/* ---------- POST: delete ---------- */
if ($action === 'delete') {
    require_post_csrf();
    q("DELETE FROM `$table` WHERE id = ?", [$id]);
    flash('admin_ok', 'Item deleted.');
    redirect($self());
}

/* ---------- POST: save ---------- */
$errors = [];
$row = [];
if ($action === 'edit' && $id) {
    $row = q("SELECT * FROM `$table` WHERE id = ?", [$id])->fetch() ?: [];
    if (!$row) {
        redirect($self());
    }
}

if (in_array($action, ['edit', 'new'], true) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_csrf();
    $data = [];
    try {
        foreach ($fields as $name => $def) {
            $type = $def['type'];
            if ($type === 'image') {
                $uploaded = save_upload($name);
                if ($uploaded !== null) {
                    $data[$name] = $uploaded;
                } elseif (!empty($_POST[$name . '__remove'])) {
                    $data[$name] = '';
                } elseif (!$row) {
                    $data[$name] = '';
                }
                continue;
            }
            $v = $_POST[$name] ?? '';
            switch ($type) {
                case 'bool':
                    $v = isset($_POST[$name]) ? 1 : 0;
                    break;
                case 'int':
                case 'fk':
                    $v = (int)$v;
                    break;
                case 'slug':
                    $v = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string)$v), '-'));
                    break;
                case 'datetime':
                    $ts = strtotime((string)$v);
                    $v = $ts ? date('Y-m-d H:i:s', $ts) : '';
                    break;
                case 'select':
                    $v = array_key_exists((string)$v, $def['options']) ? (string)$v : '';
                    break;
                case 'url':
                    $v = trim((string)$v);
                    if ($v !== '' && !preg_match('#^https?://#i', $v)) {
                        $errors[] = $def['label'] . ' must start with http:// or https://';
                    }
                    break;
                default:
                    $v = trim((string)$v);
            }
            if (!empty($def['required']) && ($v === '' || $v === 0)) {
                $errors[] = $def['label'] . ' is required.';
            }
            $data[$name] = $v;
        }
    } catch (RuntimeException $ex) {
        $errors[] = $ex->getMessage();
    }

    if (!$errors) {
        try {
            $cols = array_keys($data);
            if ($action === 'edit') {
                $set = implode(', ', array_map(fn($c) => "`$c` = ?", $cols));
                q("UPDATE `$table` SET $set WHERE id = ?", [...array_values($data), $id]);
            } else {
                $ph = implode(', ', array_fill(0, count($cols), '?'));
                q("INSERT INTO `$table` (`" . implode('`, `', $cols) . "`) VALUES ($ph)", array_values($data));
                $id = (int)db()->lastInsertId();
            }
            flash('admin_ok', 'Saved successfully.');
            redirect($self(['a' => 'edit', 'id' => $id]));
        } catch (PDOException $ex) {
            $errors[] = $ex->getCode() === '23000' ? 'This slug is already used by another item.' : 'Database error: ' . $ex->getMessage();
        }
    }
    $row = array_merge($row, $data);
}

/* ---------- Views ---------- */
if ($action === 'list') {
    $order = $ent['order'] ?? (isset($fields['sort_order']) ? 'sort_order, id' : 'id');
    $rows  = q("SELECT * FROM `$table` ORDER BY $order")->fetchAll();
    $fkMaps = [];
    foreach ($ent['list'] as $col) {
        if (($fields[$col]['type'] ?? '') === 'fk') {
            $fkMaps[$col] = fk_options($fields[$col]);
        }
    }
    admin_header($ent['label'], $key);
    ?>
    <p><a class="btn" href="<?= e($self(['a' => 'new'])) ?>">+ Add new</a></p>
    <table class="table">
      <thead><tr><?php foreach ($ent['list'] as $col): ?><th><?= e($fields[$col]['label'] ?? $col) ?></th><?php endforeach; ?><th class="actions">Actions</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <?php foreach ($ent['list'] as $col): $type = $fields[$col]['type'] ?? 'text'; ?>
            <td>
              <?php if ($type === 'bool'): ?><span class="pill <?= $r[$col] ? 'on' : 'off' ?>"><?= $r[$col] ? 'Visible' : 'Hidden' ?></span>
              <?php elseif ($type === 'image'): ?><?= $r[$col] ? '<img class="thumb" src="' . e(upload_url($r[$col])) . '" alt="">' : '—' ?>
              <?php elseif ($type === 'fk'): ?><?= e($fkMaps[$col][$r[$col]] ?? '—') ?>
              <?php else: ?><?= e(mb_strimwidth((string)$r[$col], 0, 70, '…')) ?>
              <?php endif; ?>
            </td>
          <?php endforeach; ?>
          <td class="actions">
            <a href="<?= e($self(['a' => 'edit', 'id' => $r['id']])) ?>">Edit</a>
            <form method="post" action="<?= e($self(['a' => 'delete', 'id' => $r['id']])) ?>" onsubmit="return confirm('Delete this item?');">
              <?= csrf_field() ?><button class="link danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="<?= count($ent['list']) + 1 ?>" class="muted">Nothing here yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
    <?php
    admin_footer();
    exit;
}

// Form (new / edit)
if ($action === 'new' && !$row) {
    foreach ($fields as $name => $def) {
        $row[$name] = match ($def['type']) {
            'bool' => 1,
            'int' => 0,
            'datetime' => date('Y-m-d H:i:s'),
            default => '',
        };
    }
}
admin_header(($action === 'new' ? 'Add — ' : 'Edit — ') . $ent['label'], $key);
?>
<p><a href="<?= e($self()) ?>">← Back to list</a></p>
<?php foreach ($errors as $err): ?><div class="alert err"><?= e($err) ?></div><?php endforeach; ?>

<form class="edit-form" method="post" enctype="multipart/form-data" action="<?= e($self(['a' => $action] + ($id ? ['id' => $id] : []))) ?>">
  <?= csrf_field() ?>
  <div class="fields">
  <?php foreach ($fields as $name => $def):
      $val  = $row[$name] ?? '';
      $dir  = !empty($def['rtl']) ? ' dir="rtl"' : '';
      $req  = !empty($def['required']) ? ' required' : '';
      $wide = in_array($def['type'], ['textarea', 'html'], true) ? ' wide' : '';
  ?>
    <div class="field<?= $wide ?>">
      <label for="f_<?= e($name) ?>"><?= e($def['label']) ?><?= $req ? ' *' : '' ?></label>
      <?php switch ($def['type']):
          case 'textarea': ?>
            <textarea id="f_<?= e($name) ?>" name="<?= e($name) ?>" rows="4"<?= $dir . $req ?>><?= e($val) ?></textarea>
          <?php break; case 'html': ?>
            <textarea id="f_<?= e($name) ?>" name="<?= e($name) ?>" rows="10" class="code"<?= $dir . $req ?>><?= e($val) ?></textarea>
            <small>HTML allowed: &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;&lt;li&gt;, &lt;strong&gt;, &lt;a href&gt;, &lt;img src&gt; …</small>
          <?php break; case 'bool': ?>
            <label class="check"><input type="checkbox" id="f_<?= e($name) ?>" name="<?= e($name) ?>" value="1" <?= $val ? 'checked' : '' ?>> Yes</label>
          <?php break; case 'int': ?>
            <input type="number" id="f_<?= e($name) ?>" name="<?= e($name) ?>" value="<?= e($val) ?>"<?= $req ?>>
          <?php break; case 'datetime': ?>
            <input type="datetime-local" id="f_<?= e($name) ?>" name="<?= e($name) ?>" value="<?= e($val ? date('Y-m-d\TH:i', strtotime((string)$val)) : '') ?>"<?= $req ?>>
          <?php break; case 'icon': ?>
            <select id="f_<?= e($name) ?>" name="<?= e($name) ?>">
              <?php foreach (array_keys(icon_paths()) as $ic): ?><option value="<?= e($ic) ?>" <?= $val === $ic ? 'selected' : '' ?>><?= e($ic) ?></option><?php endforeach; ?>
            </select>
          <?php break; case 'select': ?>
            <select id="f_<?= e($name) ?>" name="<?= e($name) ?>"<?= $req ?>>
              <?php foreach ($def['options'] as $ov => $ol): ?><option value="<?= e($ov) ?>" <?= (string)$val === (string)$ov ? 'selected' : '' ?>><?= e($ol) ?></option><?php endforeach; ?>
            </select>
          <?php break; case 'fk': ?>
            <select id="f_<?= e($name) ?>" name="<?= e($name) ?>"<?= $req ?>>
              <option value="">— choose —</option>
              <?php foreach (fk_options($def) as $ov => $ol): ?><option value="<?= e($ov) ?>" <?= (int)$val === (int)$ov ? 'selected' : '' ?>><?= e($ol) ?></option><?php endforeach; ?>
            </select>
          <?php break; case 'image': ?>
            <?php if ($val): ?>
              <div class="current-img"><img src="<?= e(upload_url($val)) ?>" alt=""><label class="check"><input type="checkbox" name="<?= e($name) ?>__remove" value="1"> Remove image</label></div>
            <?php endif; ?>
            <input type="file" id="f_<?= e($name) ?>" name="<?= e($name) ?>" accept="image/*">
          <?php break; default: ?>
            <input type="<?= $def['type'] === 'url' ? 'url' : 'text' ?>" id="f_<?= e($name) ?>" name="<?= e($name) ?>" value="<?= e($val) ?>"<?= $dir . $req ?>>
            <?php if ($def['type'] === 'slug'): ?><small>Lowercase letters, numbers and dashes, e.g. <code>cloud-solutions</code></small><?php endif; ?>
      <?php endswitch; ?>
    </div>
  <?php endforeach; ?>
  </div>
  <p><button class="btn" type="submit">Save</button></p>
</form>
<?php admin_footer();
