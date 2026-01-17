<?php
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO roles (name, description) VALUES (:n, :d)");
        $stmt->execute([':n' => $_POST['name'], ':d' => $_POST['description']]);
    } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE roles SET name=:n, description=:d WHERE id=:id");
        $stmt->execute([
            ':n' => $_POST['name'],
            ':d' => $_POST['description'],
            ':id' => $_POST['id'],
        ]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM roles WHERE id=:id");
        $stmt->execute([':id' => $_POST['id']]);
    }
    //header('Location: default.php?p=cm9sZXMucGhw'); 
}

$editRole = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM roles WHERE id=:id");
    $stmt->execute([':id' => $_GET['edit']]);
    $editRole = $stmt->fetch();
}
$roles = $pdo->query("SELECT * FROM roles ORDER BY id DESC")->fetchAll();
?>

<div class="row">
  <div class="col-md-4">
    <div class="card card-primary">
      <div class="card-header"><h3 class="card-title"><?= $editRole ? 'Edit' : 'Add' ?> Role</h3></div>
      <form method="post" action="default.php?p=cm9sZXMucGhw">
        <div class="card-body">
          <input type="hidden" name="action" value="<?= $editRole ? 'update' : 'create' ?>">
          <?php if ($editRole): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($editRole['id']) ?>">
          <?php endif; ?>
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($editRole['name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($editRole['description'] ?? '') ?></textarea>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <?php if ($editRole): ?><a href="roles.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card text-white mb-4">
      <div class="card-header bg-primary"><h3 class="card-title">Roles</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($roles as $r): ?>
            <tr>
              <td><?= $r['id'] ?></td>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= htmlspecialchars($r['description']) ?></td>
              <td>
                <a href="default.php?p=cm9sZXMucGhw&edit=<?= $r['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                <form method="post" action="default.php?p=cm9sZXMucGhw" style="display:inline-block"
                      onsubmit="return confirm('Delete this role?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>">
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


