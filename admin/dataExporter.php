<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if(isset($_GET['f']) && $_GET['f'] === 'p'){
            include('./body/payment_filters.php');
        }else{
            include('./body/report_filters.php');
        }

        $filenameprefix = 'sales_export_';
        if(isset($_GET['fn']) && !empty($_GET['fn'])){
            $filenameprefix = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_GET['fn']) . '_';
        }
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filenameprefix . date('Y-m-d') . '.csv');

        // Create file pointer connected to PHP output stream
        $output = fopen('php://output', 'w');

        // Add CSV column headers
        $headings = isset($_GET['headings']) ? explode(',', $_GET['headings']) : [];
        if (!empty($headings)) {
            fputcsv($output, $headings);
        } else {
            // Default headings if none provided
            fputcsv($output, ['ID', 'Bill Number', 'Date', 'Retailer', 'Beat', 'Salesman', 'Amount', 'User ID', 'Full Payment', 'Pmt Mode', 'Cheque No']);
        }
        

        // Fetch and write data rows
        $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = json_decode(json_encode($bills), true);
        $total = array_sum(array_column($data, 'bill_amount'));
        $collected = array_sum(array_column($data, 'paid_amt'));
        $pending = array_sum(array_column($data, 'pending_amt'));
        //$total = abs($total - $collected);

        //echo " Total: ".json_encode($transactions).") Collected: <br/>";

        foreach ($bills as $row) {
            $foundBill = array_filter($transactions, function($obj) use ($row) {
                  return $obj['sales_bill_id'] === $row['id'];
              });
              //$foundBill = reset($foundBill); // Get the first matching element
              $grandTotal = 0;
              if (!empty($foundBill) && is_array($foundBill)) {
                  $grandTotal = array_sum(array_column($foundBill, 'amount'));
              }

              $upiRow = array_filter($foundBill, function($item) {
                  return $item['payment_type'] === 'UPI';
              });
              
              $chequeRow = array_filter($foundBill, function($item) {
                  return $item['payment_type'] === 'CHEQUE';
              });

              $cashRow = array_filter($foundBill, function($item) {
                  return $item['payment_type'] === 'CASH';
              });

              $row['paid_amt'] = $grandTotal;
              $row['cash'] = !empty($cashRow) ? array_sum(array_column($cashRow, 'amount')) : 0.0;
              $row['upi'] = !empty($upiRow) ? array_sum(array_column($upiRow, 'amount')) : 0.0;
              $row['cheque'] = !empty($chequeRow) ? array_sum(array_column($chequeRow, 'amount')) : 0.0;    

            fputcsv($output, $row);
        }
        // Add totals row
        fputcsv($output, ['', '', '', '', '', $total, $collected, $pending, '', '', '', '', '', '', '', '']);
        
        fclose($output);
        exit;

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}else{
    echo "NO POST";
}

header('Location: default.php?p=ZGF0YS5waHA='); exit;
?>