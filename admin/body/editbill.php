<?php
//require 'db.php';

$current_path = $_SERVER['PHP_SELF'];
$directory_path = dirname($current_path);
$folder_name = basename($directory_path);

//echo basename($directory_path)."<br/>";
$isAdmin = false;
if(checkPermission("admin:editbill", $_SESSION['permissions'])){
    $isAdmin = true;
}

if ($folder_name === 'admin') {
    require 'db.php';
}else{
    require  './admin/db.php';
}

$id = $_GET['bill_id'] ?? null;
$message = "";

if (!$id) {
    die("Error: No bill ID specified.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $pmt_mode    = $_POST['pmt_mode'];
        $cheque_no   = $_POST['cheque_no'];
        $paid_amt    = $_POST['paid_amt'];
        $pending_amt = $_POST['pending_amt'];

    if($isAdmin){
        $retailer_name = $_POST['retailer_name'];
        $salesman = $_POST['salesman'];
        $beat_name = $_POST['beat_name'];

        $fullname = '';    $user_id = null;
        if(!empty($salesman) && strpos($salesman, '#') !== false){
            list($user_id, $fullname) = explode('#', $salesman, 2);
        }   
        if(!empty($pmt_mode) && $pmt_mode == 'NONE'){
            $pmt_mode = '';
        }
        $is_full_pmt = $_POST['is_full_pmt'];
        if(!empty($cheque_no)){
            $cheque_no = strtoupper($cheque_no);
        }

        $stmt = $pdo->prepare("UPDATE sales_bills SET pmt_mode=:n, cheque_no=:d, paid_amt=:p, pending_amt=:q, is_full_pmt=:f, retailer_name=:r, salesman=:s, beat_name=:b, user_id=:u WHERE id=:id");
        $result = $stmt->execute([
            ':n' => $pmt_mode,
            ':d' => $cheque_no,
            ':p' => $paid_amt,
            ':q' => $pending_amt,
            ':f' => $is_full_pmt,
            ':r' => $retailer_name,
            ':s' => $fullname,
            ':b' => $beat_name,
            ':u' => $user_id,
            ':id' => $id
        ]);
    }else{
        // Checkboxes are only sent if checked
        $original_paid_amt = $_POST['original_paid_amt']; 

        if(!empty($original_paid_amt) && is_numeric($original_paid_amt)){
            $paid_amt += floatval($original_paid_amt);
        }

        $is_full_pmt = $_POST['is_full_pmt'];

        if(!empty($cheque_no)){
            $cheque_no = strtoupper($cheque_no);
        }
        
        $stmt = $pdo->prepare("UPDATE sales_bills SET pmt_mode=:n, cheque_no=:d, paid_amt=:p, pending_amt=:q, is_full_pmt=:f WHERE id=:id");
        $result = $stmt->execute([
            ':n' => $pmt_mode,
            ':d' => $cheque_no,
            ':p' => $paid_amt,
            ':q' => $pending_amt,
            ':f' => $is_full_pmt,
            ':id' => $id
        ]);
    }
    
    $message = $result ? "Bill updated successfully!" : "Update failed.";
    //header('Location: default.php?p=cm9sZXMucGhw'); 
}

// 2. Fetch Current Data to populate form
    $stmt = $pdo->prepare("SELECT * FROM sales_bills WHERE id = ?");
    $stmt->execute([$id]);
    $bill = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bill) {
        die("Error: Bill not found.");
    }

    $is_paid = false;
    // If pending amount is zero and paid amount is equal to or greater than bill amount, set is_full_pmt to 0
    if (!empty($bill['pending_amt']) && $bill['pending_amt'] == 0 && $bill['paid_amt'] >= $bill['bill_amount']) {
        $is_paid = true;   
    }

    if ($isAdmin) {
        // Fetch all users with role 'Salesman'
        $stmt = $pdo->prepare("SELECT u.id, u.fullname FROM users u INNER JOIN user_roles ur ON u.id = ur.user_id INNER JOIN roles r ON ur.role_id = r.id WHERE r.name = 'Staff'");
        $stmt->execute();   
        $staffUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo "users: ".json_encode($staffUsers);
    }

?>

<h3><a href="default.php?p=ZGF0YS5waHA=" class="alert-link"><< BACK</a>.</h3> Go back to the bills list.
    <?php if ($message): ?>
    <div class="alert alert-success" role="alert">
        <p><strong><?= $message ?></strong></p>
    </div>
    <?php endif; ?>

    <div class="card card-primary card-outline mb-4">
        <!--begin::Header-->
        <div class="card-header">
            <h2>Edit Bill # <?= !empty($bill) && !empty($bill['bill_number']) ? htmlspecialchars($bill['bill_number']) : '' ?></h2>
            <div><h2>Bill Amount: <span class="badge text-bg-primary">₹<?= !empty($bill) && !empty($bill['bill_amount']) ? htmlspecialchars($bill['bill_amount']) : '' ?></span></h2></div>
            <?php if ($bill['is_full_pmt'] == 0): ?>
            <div><h3>Paid Amount: <span class="badge text-bg-success">₹<?= !empty($bill) && !empty($bill['paid_amt']) ? htmlspecialchars($bill['paid_amt']) : '' ?></span></h3></div>
            <?php if ($bill['pending_amt'] > 0): ?>
            <div><h2>Pending Amount: <span class="badge text-bg-danger">₹<?= !empty($bill) && !empty($bill['pending_amt']) ? htmlspecialchars($bill['pending_amt']) : '' ?></span></h2></div>
            <?php endif; ?>
            <?php endif; ?>
            <div class="card-title">Retailer Name : <?= !empty($bill) && !empty($bill['retailer_name']) ? htmlspecialchars($bill['retailer_name']) : '' ?></div>
            
        </div>

<!--begin::Quick Example-->
<?php if($isAdmin): ?>
    <?php include('editbill_admin.php'); ?>
<?php else: ?>
    <?php include('editbill_user.php'); ?>
<?php endif; ?>
</div>
<!--end::Quick Example-->