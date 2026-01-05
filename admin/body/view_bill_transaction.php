<?php
require  './admin/db.php';

$id = $_GET['bill_number'] ?? null;
$message = "";

echo $id;

if (!$id) {
    die("Error: No bill ID specified.");
}

$query = "SELECT * FROM bill_transaction WHERE bill_number = ?";
$params = [$id];

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grandTotal = 0;
foreach ($transactions as $row) {
    $grandTotal += floatval($row['amount']);
}
?>
<h3><a href="default.php?p=ZGF0YS5waHA=" class="alert-link"><< BACK</a>.</h3> Go back to the bills list.
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Transaction Records for Bill No.: <?= htmlspecialchars($id) ?></h4>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Collected By</th>
                        <th>Method</th>
                        <th>Ref No</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transactions) > 0): ?>
                        <?php foreach ($transactions as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= $row['payment_type'] ?></span></td>
                            <td><?= htmlspecialchars($row['ref_no'] ?? '-') ?></td>
                            <td class="fw-bold text-end">₹<?= number_format($row['amount'], 2) ?></td>
                            <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No transactions found.</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if (count($transactions) > 0): ?>
                <tfoot class="table-secondary">
                    <tr>
                        <th colspan="3" class="text-end">Total Collected Amt:</th>
                        <th class="text-end text-primary" style="font-size: 1.1rem;">
                            ₹<?= number_format($grandTotal, 2) ?>
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>