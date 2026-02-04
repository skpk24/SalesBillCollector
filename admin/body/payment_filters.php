<?php

function formateDate($dateStr) {
    $dateTime = DateTime::createFromFormat('d/m/Y H:i', $dateStr);
    if ($dateTime === false) {
        $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateStr);
    }
    if ($dateTime) {
        return $dateTime->format('Y-m-d H:i:s');
    }
    
    return null;
}

function cleanDate($dateStr, $newMinutes) {
    $updated = substr_replace($dateStr, $newMinutes, -2);  // 14/01/2026 23:59:59
    if ($updated) {
        return $updated;
    }
    return null;
}

function getBillTransactions(PDO $pdo, array $bill_numbers, $from_date, $to_date): array {
    $count = 0;
    if(!empty($bill_numbers) && is_array($bill_numbers)) {
        if(count($bill_numbers) === 0){
            $count = 0;
        }else{
            $count = count($bill_numbers) - 1;  
        }
        
    }

    $placeholders = str_repeat('?,', $count) . '?';
    $where = ' WHERE 1=1 ';
    $where .= ' AND bill_number IN (' . $placeholders . ') ';
    if($from_date !== '' && $to_date !== '') {
        $from_date = formateDate(cleanDate($from_date, '00:00'));
        $to_date = formateDate(cleanDate($to_date, '59:59'));
    } else {
        $from_date = '1970-01-01 00:00:00';
        $to_date = date('Y-m-d H:i:s');
    }
    $where .= ' AND created_at BETWEEN \''.$from_date.'\' AND \''.$to_date.'\' ';

    $stmt = $pdo->prepare("SELECT sales_bill_id, payment_type, amount FROM bill_transaction ".$where." ORDER BY id DESC");
    $stmt->execute($bill_numbers);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBillTransaction(PDO $pdo, $from_date, $to_date): array {
    $where = ' WHERE 1=1 ';
    if($from_date !== '' && $to_date !== '') {
        $from_date = $from_date;
        $to_date = $to_date;
    } else {
        $from_date = '1970-01-01 00:00:00';
        $to_date = date('Y-m-d H:i:s');
    }
    $where .= ' AND created_at BETWEEN \''.$from_date.'\' AND \''.$to_date.'\' ';
    //echo " WHERE CLAUSE: ".$where."<br/>";
    $stmt = $pdo->prepare("SELECT sales_bill_id, payment_type, amount FROM bill_transaction ".$where." ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$bill_number   = $_GET['bill_number']   ?? '';
$salesman      = $_GET['salesman']      ?? '';
$to_date       = $_GET['to_date']       ?? '';   
$from_date     = $_GET['from_date']     ?? '';

$where  = [];
$params = [];
$types  = '';


$start = date('Y-m-d 00:00:00');
$end   = date('Y-m-d 23:59:59');

$defaultFrom_date = date('d/m/Y 00:00');
$defaultTo_date   = date('d/m/Y 23:59');

if($from_date !== null){
    $start = formateDate(cleanDate($from_date, '00:00'));
    //echo " From Date: ".$from_date."<br/>";
    //$defaultFrom_date = (new DateTime($from_date))->format('d/m/Y H:i');
    $defaultFrom_date = $from_date;
}
if($to_date !== null){
    $end = formateDate(cleanDate($to_date, '59:59'));
    //echo " To Date: ".$to_date."<br/>";
    //$defaultTo_date = (new DateTime($to_date))->format('d/m/Y H:i');
    $defaultTo_date = $to_date;
}

$transactions = getBillTransaction($pdo, $start, $end);

$sales_bill_ids = [];
if(!empty($transactions)) {
    $sales_bill_ids = array_column($transactions, 'sales_bill_id');
}
//echo "Sales Bill IDs: ".json_encode($sales_bill_ids)."<br/>";

// Build dynamic WHERE (use LIKE for text, = for exact numeric/boolean)
if ($bill_number !== '') {
    $where[]  = 'bill_number LIKE ?';
    $params[] = '%' . $bill_number . '%';
    $types   .= ':bill_number';
}
if ($salesman !== '') {
    $where[]  = 'salesman LIKE ?';
    $params[] = '%' . $salesman . '%';
    $types   .= ':salesman';
}

/*
if($from_date !== '' && $to_date !== '') {
    $where[]  = 'updated_at BETWEEN ? AND ?';
    $params[] = formateDate(cleanDate($from_date, '00:00'));
    $params[] = formateDate(cleanDate($to_date, '59:59'));
    $types   .= ':from_date:to_date';
} elseif ($from_date !== '') {
    $where[]  = 'updated_at >= ?';
    $params[] = formateDate(cleanDate($from_date, '00:00'));
    $types   .= ':from_date';
} elseif ($to_date !== '') {
    $where[]  = 'updated_at <= ?';
    $params[] = formateDate(cleanDate($to_date, '59:59'));
    $types   .= ':to_date';
}
*/
if(!empty($sales_bill_ids)) {
    $placeholders = str_repeat('?,', count($sales_bill_ids) - 1) . '?';
    $where[]  = ' id IN (' . $placeholders . ') ';
    $params = array_merge($params, $sales_bill_ids);
    $types   .= ':sales_bill_ids';
}else{
    // If no sales bill IDs found for the given date range, ensure no results are returned
    $where[] = ' id IN (NULL) ';
}

$fields = isset($_GET['fields']) ? $_GET['fields'] : '*';

$sql = "SELECT ".$fields." FROM sales_bills  WHERE ((bill_amount = paid_amt AND pending_amt = 0) OR (bill_amount >= paid_amt AND pending_amt > 0)) AND 1 = 1 ";

if ($where) {
    $sql .= " AND " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";
//echo  print_r($sql, true)."<br/>";
$stmt = $pdo->prepare($sql);

if ($where) {
    $stmt->execute($params);
} else {
    $stmt->execute();
}
//echo "<pre>";
//$stmt->debugDumpParams();
//echo "</pre>";
$formatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);

?>
