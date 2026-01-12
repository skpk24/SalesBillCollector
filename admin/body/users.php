<?php 
require 'db.php';

// Handle create/update/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $stmt = $pdo->prepare(
            "INSERT INTO users (fullname, username, email, password_hash, is_active)
             VALUES (:f, :u, :e, :p, :a)"
        );
        $stmt->execute([
            ':f' => $_POST['fullname'],
            ':u' => $_POST['username'],
            ':e' => $_POST['email'],
            ':p' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            ':a' => isset($_POST['is_active']) ? 1 : 0,
        ]);
    } elseif ($action === 'update') {
        $params = [
            ':f' => $_POST['fullname'],
            ':u' => $_POST['username'],
            ':e' => $_POST['email'],
            ':a' => isset($_POST['is_active']) ? 1 : 0,
            ':id' => $_POST['id'],
        ];
        $sql = "UPDATE users SET fullname=:f, username=:u, email=:e, is_active=:a";
        if (!empty($_POST['password'])) {
            $sql .= ", password_hash=:p";
            $params[':p'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $sql .= " WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=:id");
        $stmt->execute([':id' => $_POST['id']]);
    }
    //header('Location: default.php?p=dXNlcnMucGhw');
    
}

// For editing
$editUser = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->execute([':id' => $_GET['edit']]);
    $editUser = $stmt->fetch();
}

// List
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<div class="row">
  <div class="col-md-3">
    <div class="card card-primary">
      <div class="card-header"><h3 class="card-title"><?= $editUser ? 'Edit' : 'Add' ?> User</h3></div>
      <form method="post">
        <div class="card-body">
          <input type="hidden" name="action" value="<?= $editUser ? 'update' : 'create' ?>">
          <?php if ($editUser): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($editUser['id']) ?>">
          <?php endif; ?>
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" class="form-control"
                   value="<?= htmlspecialchars($editUser['fullname'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?= htmlspecialchars($editUser['username'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($editUser['email'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Password <?= $editUser ? '(leave blank to keep)' : '' ?></label>
            <input type="password" name="password" class="form-control" <?= $editUser ? '' : 'required' ?>>
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="is_active"
                   name="is_active" <?= (!isset($editUser) || $editUser['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <?php if ($editUser): ?>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-9">
    <div class="card text-white mb-4">
      <div class="card-header bg-primary"><h3 class="card-title">Users</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
          <tr>
            <th>Full Name</th><th>Username</th><th>Email</th><th>Active</th><th>Actions</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= $u['fullname'] ?></td>
              <td><?= htmlspecialchars($u['username']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><span class="badge badge-<?= $u['is_active'] ? 'success' : 'secondary' ?>" style="color:#000">
                <?= $u['is_active'] ? 'Yes' : 'No' ?>
              </span></td>
              <td>
                <a href="default.php?p=dXNlcnMucGhw&edit=<?= $u['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                <form method="post" action="default.php?p=dXNlcnMucGhw" style="display:inline-block"
                      onsubmit="return confirm('Delete this user?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
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




