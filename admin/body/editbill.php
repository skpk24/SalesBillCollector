<?php
//require 'db.php';
$current_path = $_SERVER['PHP_SELF'];
$directory_path = dirname($current_path);
$folder_name = basename($directory_path);

if ($folder_name === 'admin') {
    require 'db.php';
}else{
    require  './admin/db.php';
}



//echo basename($directory_path)."<br/>";
$isAdmin = false;
if(checkPermission("admin:editbill", $_SESSION['permissions'])){
    $isAdmin = true;
}



$id = $_GET['bill_id'] ?? null;
$message = "";

if (!$id) {
    die("Error: No bill ID specified.");
}

include('handle_sales_bill.php');
// 1. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        $formData = [
            'pmt_mode' => $_POST['pmt_mode'] ?? null, // Let's say this is empty
            'cheque_no'       => $_POST['cheque_no'] ?? null,       // Value: 500.00
            'paid_amt'       => $_POST['paid_amt'] ?? null,       // Value: "REF123"
            'pending_amt'     => $_POST['pending_amt'] ?? null,      // Let's say this is empty
            'bill_number'     => $_POST['bill_number'] ?? null      // Let's say this is empty
        ];


        if(!empty($formData['pmt_mode'])){
            switch($formData['pmt_mode']){
                case 'CASH':
                    $formData['cash'] = floatval($_POST['paid_amt'] ?? 0.0) + floatval($_POST['originalCash'] ?? 0.0);
                    $formData['cheque_no'] = '';
                    break;
                case 'UPI':
                    $formData['upi'] = floatval($_POST['paid_amt'] ?? 0.0) + floatval($_POST['originalUpi'] ?? 0.0);
                    $formData['cheque_no'] = strtoupper($formData['cheque_no']);
                    break;
                case 'CHEQUE':
                    $formData['cheque'] = floatval($_POST['paid_amt'] ?? 0.0) + floatval($_POST['originalCheque'] ?? 0.0);
                    $formData['cheque_no'] = strtoupper($formData['cheque_no']);
                    break;
                default:
                    // No payment mode selected or NONE
                    break;
            }
        }
    createBillTransaction($pdo, $id, $_SESSION['user_id'], $_SESSION['fullname'], $formData['pmt_mode'], $formData['paid_amt'], $formData['cheque_no'], $formData['bill_number']);
    $formData['is_full_pmt'] = $_POST['is_full_pmt'];
    if($isAdmin){
        $formData['retailer_name'] = $_POST['retailer_name'];
        $formData['salesman'] = $_POST['salesman'];
        $formData['beat_name'] = $_POST['beat_name'];

        $fullname = '';    $user_id = null;
        if(!empty($formData['salesman']) && strpos($formData['salesman'], '#') !== false){
            list($user_id, $fullname) = explode('#', $formData['salesman'], 2);
            $formData['user_id'] = $user_id;
            $formData['fullname'] = $fullname;
        }   

        if(!empty($formData['pmt_mode']) && $formData['pmt_mode'] == 'NONE'){
            $formData['pmt_mode'] = '';
        }

        $result = updateSaleBill($pdo, $id, $formData);
    }else{
        $formData['paid_amt'] =  floatval($formData['paid_amt']) + floatval($_POST['original_paid_amt'] ?? 0.0);

         $result = updateSaleBill($pdo, $id, $formData);
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