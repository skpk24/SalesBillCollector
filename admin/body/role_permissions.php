<?php
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $stmt = $pdo->prepare(
            "INSERT IGNORE INTO role_permissions (role_id, permission_id)
             VALUES (:r, :p)"
        );
        $stmt->execute([':r' => $_POST['role_id'], ':p' => $_POST['permission_id']]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare(
            "DELETE FROM role_permissions WHERE role_id=:r AND permission_id=:p"
        );
        $stmt->execute([':r' => $_POST['role_id'], ':p' => $_POST['permission_id']]);
    }
    //header('Location: default.php?p=cm9sZV9wZXJtaXNzaW9ucy5waHA='); exit;
}

$roles = $pdo->query("SELECT id, name FROM roles ORDER BY name")->fetchAll();
$perms = $pdo->query("SELECT id, name FROM permissions ORDER BY name")->fetchAll();

$rows = $pdo->query("
    SELECT rp.role_id, rp.permission_id, r.name AS role_name, p.name AS perm_name
    FROM role_permissions rp
    JOIN roles r ON rp.role_id = r.id
    JOIN permissions p ON rp.permission_id = p.id
    ORDER BY r.name, p.name
")->fetchAll();
?>
<!-- same AdminLTE layout as user_roles.php, just change labels -->

<div class="row">
  <div class="col-md-4">
    <div class="card card-primary">
      <div class="card-header"><h3 class="card-title">Edit Role</h3></div>
      <form method="post">
        <div class="card-body">
          <input type="hidden" name="action" value="create">
          <div class="form-group">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
              <option value="">Select Roles</option>
              <?php foreach ($roles as $u): ?>
                <option value="<?= $u['role_id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Permissions</label>
            <select name="permission_id" class="form-control" required>
              <option value="">Select Permissions</option>
              <?php foreach ($perms as $r): ?>
                <option value="<?= $r['permission_id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Assign</button>
        </div>
      </form>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Roles & Permissions</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead><tr><th>Role</th><th>Permission</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['role_name']) ?></td>
              <td><?= htmlspecialchars($r['perm_name']) ?></td>
              <td>
                <a href="default.php?p=cm9sZV9wZXJtaXNzaW9ucy5waHA=&edit=<?= $r['role_id'] ?>" class="btn btn-sm btn-info">Edit</a>
                <form method="post" action="default.php?p=cm9sZXMucGhw" style="display:inline-block"
                      onsubmit="return confirm('Delete this role?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $r['role_id'] ?>">
                  <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>