<?php
require 'db.php';


function getUsers(array $userNames = array())
{
    global $pdo;
    if (empty($userNames)) {
        return null;
    }

    // build placeholders for prepared statement
    $placeholders = implode(',', array_fill(0, count($userNames), '?'));
    $stmt = $pdo->prepare("SELECT fullname, id FROM users WHERE fullname IN ($placeholders)");
    $stmt->execute($userNames);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results ?: null;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("INSERT IGNORE INTO sales_bills (bill_number, bill_date, retailer_name, beat_name, salesman, bill_amount, user_id, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (isset($_POST['bill_number']) && is_array($_POST['bill_date']) && is_array($_POST['retailer_name']) && is_array($_POST['beat_name']) && is_array($_POST['salesman']) && is_array($_POST['bill_amount'])) {

        //$users = $pdo->query("SELECT fullname,id FROM users ORDER BY id DESC")->fetchAll();

        $salesman_user = array_values(array_unique($_POST['salesman']));


        $userList = [];
        $users = getUsers($salesman_user);
        if (!empty($users)) {
            foreach ($users as $row) {
                $userList[$row['fullname']] = $row['id'];
            }
        }

        //echo "<br>Salesman User Map:<br> ".print_r($userList, true)."<br>";

        foreach ($_POST['bill_number'] as $index => $bill_number) {
            $bill_date = $_POST['bill_date'][$index];
            $retailer_name = $_POST['retailer_name'][$index];
            $beat_name = $_POST['beat_name'][$index];
            $salesman = $_POST['salesman'][$index];
            $bill_amount = $_POST['bill_amount'][$index];

            // 3. Execute with the current row's data
            if (!empty($bill_number) && !empty($bill_date) && !empty($salesman) && !empty($bill_amount)) {
                $stmt->execute([$bill_number, $bill_date, $retailer_name, $beat_name, $salesman, $bill_amount, $userList[$salesman], $_POST['user_id'], $_POST['user_id']]);
                //echo "".$userList[$salesman]."<br>";
            }
        }
        
    }
}else{
    echo "NO POST";
}

header('Location: default.php?p=ZGF0YS5waHA='); exit;
?>