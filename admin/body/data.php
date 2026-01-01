<?php
require 'db.php';


$bills = $pdo->query("SELECT * FROM sales_bills ORDER BY id ASC")->fetchAll();
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Sales Bills</h3></div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead><tr><th>﻿Bill Number</th><th>Bill Date</th><th>Retailer Name</th><th>Beat Name</th><th>Salesman</th><th>Bill Amount</th><th>Status</th></tr></thead>
          <tbody>
          <?php    
            $is_cash = true;
            foreach ($bills as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['bill_number']) ?></td>
              <td><?= htmlspecialchars($r['bill_date']) ?></td>
              <td><?= htmlspecialchars($r['retailer_name']) ?></td>
              <td><?= htmlspecialchars($r['beat_name']) ?></td>
              <td><?= htmlspecialchars($r['salesman']) ?></td>
              <td><?= htmlspecialchars($r['bill_amount']) ?></td>
              <td>
                  <?php if($is_cash) {?>
                <a href="default.php?p=ZGF0YS5waHA=&edit=<?= $r['id'] ?>" class="btn btn-sm btn-info">UPI</a>
                <?php  $is_cash = false;
                 }else {?>
                <form method="post" action="default.php?p=ZGF0YS5waHA=" style="display:inline-block"
                      onsubmit="return confirm('Delete this role?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>">
                  <button class="btn btn-sm btn-danger" type="submit">Cash</button>
                </form>
                <?php $is_cash = true;}?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>