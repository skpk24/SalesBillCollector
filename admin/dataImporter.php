<?php
require 'db.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("INSERT INTO sales_bills (bill_number, bill_date, retailer_name, beat_name, salesman, bill_amount) VALUES (?, ?, ?, ?, ?, ?)");

    if (isset($_POST['bill_number']) && is_array($_POST['bill_date']) && is_array($_POST['retailer_name']) && is_array($_POST['beat_name']) && is_array($_POST['salesman']) && is_array($_POST['bill_amount'])) {
        
        foreach ($_POST['bill_number'] as $index => $bill_number) {
            $bill_date = $_POST['bill_date'][$index];
            $retailer_name = $_POST['retailer_name'][$index];
            $beat_name = $_POST['beat_name'][$index];
            $salesman = $_POST['salesman'][$index];
            $bill_amount = $_POST['bill_amount'][$index];

            // 3. Execute with the current row's data
            if (!empty($bill_number) && !empty($bill_date) && !empty($retailer_name) && !empty($beat_name) && !empty($salesman) && !empty($bill_amount)) {
                $stmt->execute([$bill_number, $bill_date, $retailer_name, $beat_name, $salesman, $bill_amount]);
                //echo "".$bill_number . $bill_date;
            }
        }
        
    }
}else{

    echo "NO POST";
}

header('Location: default.php?p=ZGF0YS5waHA='); exit;
?>