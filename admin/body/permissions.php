<?php
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO permissions (name, description) VALUES (:n, :d)");
        $stmt->execute([':n' => $_POST['name'], ':d' => $_POST['description']]);
    } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE permissions SET name=:n, description=:d WHERE id=:id");
        $stmt->execute([
            ':n' => $_POST['name'],
            ':d' => $_POST['description'],
            ':id' => $_POST['id'],
        ]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM permissions WHERE id=:id");
        $stmt->execute([':id' => $_POST['id']]);
    }
    //header('Location: permissions.php'); exit;
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM permissions WHERE id=:id");
    $stmt->execute([':id' => $_GET['edit']]);
    $editItem = $stmt->fetch();
}
$permissions = $pdo->query("SELECT * FROM permissions ORDER BY id DESC")->fetchAll();
?>
<!-- same AdminLTE layout as roles.php, just change labels to Permissions -->
<div class="row">
<div class="col-md-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Roles</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($permissions as $r): ?>
            <tr>
              <td><?= $r['id'] ?></td>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= htmlspecialchars($r['description']) ?></td>
              <td>
               
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>