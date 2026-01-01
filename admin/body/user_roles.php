<?php
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $stmt = $pdo->prepare(
            "INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (:u, :r)"
        );
        $stmt->execute([':u' => $_POST['user_id'], ':r' => $_POST['role_id']]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare(
            "DELETE FROM user_roles WHERE user_id=:u AND role_id=:r"
        );
        $stmt->execute([':u' => $_POST['user_id'], ':r' => $_POST['role_id']]);
    }
    //header('Location: user_roles.php'); exit;
}

$users = $pdo->query("SELECT id, username FROM users ORDER BY username")->fetchAll();
$roles = $pdo->query("SELECT id, name FROM roles ORDER BY name")->fetchAll();

$rows = $pdo->query("
    SELECT ur.user_id, ur.role_id, u.username, r.name AS role_name
    FROM user_roles ur
    JOIN users u ON ur.user_id = u.id
    JOIN roles r ON ur.role_id = r.id
    ORDER BY u.username, r.name
")->fetchAll();
?>

<div class="row">
  <div class="col-md-4">
    <div class="card card-primary">
      <div class="card-header"><h3 class="card-title">Assign Role to User</h3></div>
      <form method="post">
        <div class="card-body">
          <input type="hidden" name="action" value="create">
          <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
              <option value="">Select user</option>
              <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
              <option value="">Select role</option>
              <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
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
      <div class="card-header"><h3 class="card-title">User Roles</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead><tr><th>User</th><th>Role</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['role_name']) ?></td>
              <td>
                <form method="post" action="user_roles.php" style="display:inline-block"
                      onsubmit="return confirm('Remove this role from user?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                  <input type="hidden" name="role_id" value="<?= $row['role_id'] ?>">
                  <button class="btn btn-sm btn-danger" type="submit">Remove</button>
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


