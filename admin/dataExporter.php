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
        fputcsv($output, [
            'ID', 'Bill Number', 'Date', 'Retailer', 'Beat', 
            'Salesman', 'Amount', 'User ID', 'Full Payment', 
            'Pmt Mode', 'Cheque No'
        ]);

        // Fetch and write data rows
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }

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