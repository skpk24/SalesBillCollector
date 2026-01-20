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

$bill_number   = $_GET['bill_number']   ?? '';
$bill_date     = $_GET['bill_date']     ?? '';
$retailer_name = $_GET['retailer_name'] ?? '';
$beat_name     = $_GET['beat_name']     ?? '';
$salesman      = $_GET['salesman']      ?? '';
$bill_amount   = $_GET['bill_amount']   ?? '';
$is_full_pmt   = $_GET['is_full_pmt']   ?? '';
$cheque_no     = $_GET['cheque_no']     ?? '';
$pmt_mode      = $_GET['pmt_mode']      ?? '';
$to_date       = $_GET['to_date']       ?? '';   
$from_date     = $_GET['from_date']     ?? '';

$where  = [];
$params = [];
$types  = '';

// Build dynamic WHERE (use LIKE for text, = for exact numeric/boolean)
if ($bill_number !== '') {
    $where[]  = 'bill_number LIKE ?';
    $params[] = '%' . $bill_number . '%';
    $types   .= ':bill_number';
}
if ($bill_date !== '') {
    $where[]  = 'bill_date LIKE ?';
    $params[] = '%' . $bill_date . '%';
    $types   .= ':bill_date';
}
if ($retailer_name !== '') {
    $where[]  = 'retailer_name LIKE ?';
    $params[] = '%' . $retailer_name . '%';
    $types   .= ':retailer_name';
}
if ($beat_name !== '') {
    $where[]  = 'beat_name LIKE ?';
    $params[] = '%' . $beat_name . '%';
    $types   .= ':beat_name';
}
if ($salesman !== '') {
    $where[]  = 'salesman LIKE ?';
    $params[] = '%' . $salesman . '%';
    $types   .= ':salesman';
}
if ($bill_amount !== '') {
    $where[]  = 'bill_amount = ?';
    $params[] = $bill_amount;
    $types   .= ':bill_amount';
}
if ($is_full_pmt !== '') { // expect 0 or 1
    $where[]  = 'is_full_pmt = ?';
    $params[] = $is_full_pmt;
    $types   .= ':is_full_pmt';
}
if ($cheque_no !== '') {
    $where[]  = 'cheque_no LIKE ?';
    $params[] = '%' . $cheque_no . '%';
    $types   .= ':cheque_no';
}
if ($pmt_mode !== '') {
    $where[]  = 'pmt_mode LIKE ?';
    $params[] = '%' . $pmt_mode . '%';
    $types   .= ':pmt_mode';
}
if($from_date !== '' && $to_date !== '') {
    $where[]  = 'updated_at BETWEEN ? AND ?';
    $params[] = formateDate(cleanDate($from_date, '00:00'));
    $params[] = formateDate(cleanDate($to_date, '59:59'));
    $types   .= ':from_date,:to_date';
} elseif ($from_date !== '') {
    $where[]  = 'updated_at >= ?';
    $params[] = formateDate(cleanDate($from_date, '00:00'));
    $types   .= ':from_date';
} elseif ($to_date !== '') {
    $where[]  = 'updated_at <= ?';
    $params[] = formateDate(cleanDate($to_date, '59:59'));
    $types   .= ':to_date';
}

$fields = isset($_GET['fields']) ? $_GET['fields'] : '*';

$sql = "SELECT ".$fields." FROM sales_bills  WHERE ((bill_amount = paid_amt AND pending_amt = 0) OR (bill_amount >= paid_amt AND pending_amt > 0)) AND 1 = 1 ";

if ($where) {
    $sql .= " AND " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";
//echo  print_r($params);
$stmt = $pdo->prepare($sql);

// For PDO use execute with the positional parameters array

//echo "SQL Query: ".$sql."<br/>";

if ($where) {
    $stmt->execute($params);
} else {
    $stmt->execute();
}
$formatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);
?>
