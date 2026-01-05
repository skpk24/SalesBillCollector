<?php

/**
 * Adds a new payment transaction for a specific bill
 * * @param PDO $pdo           The database connection object
 * @param int $billId        The ID of the bill from sales_bills
 * @param int $userId        The ID of the user creating the transaction
 * @param string $userName   The full name of the person
 * @param string $type       Payment method (Cash, UPI, Cheque)
 * @param float $amount      The amount paid
 * @param string|null $refNo Reference number or cheque number
 * @return bool              True on success
 */
function createBillTransaction($pdo, $billId, $userId, $userName, $type, $amount, $refNo = null, $bill_number = null): bool {
    try {
        $sql = "INSERT INTO bill_transaction 
                (sales_bill_id, created_by, fullname, payment_type, amount, ref_no, bill_number) 
                VALUES 
                (:bill_id, :user_id, :fullname, :pay_type, :amount, :ref_no, :bill_number)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':bill_id'  => $billId,
            ':user_id'  => $userId,
            ':fullname' => $userName,
            ':pay_type' => $type,
            ':amount'   => $amount,
            ':ref_no'   => $refNo,
            ':bill_number' => $bill_number
        ]);

        return true;
    } catch (PDOException $e) {
        // In a production app, log this error instead of echoing it
        error_log("Transaction Error: " . $e->getMessage());
        return false;
    }
}


/**
 * Updates a transaction record only for the fields provided.
 * * @param PDO $pdo        The database connection
 * @param int $id         The ID of the transaction to update
 * @param array $data     An associative array of [column_name => value]
 */
function updateSaleBill($pdo, $id, $data) {
    // 1. Filter out null or empty values so they aren't updated
    $updateData = array_filter($data, function($value) {
        return $value !== null && $value !== ''; 
    });

    if (empty($updateData)) {
        return false; // Nothing to update
    }

    $setParts = [];
    $params = [':id' => $id];

    // 2. Build the "SET column = :column" string
    foreach ($updateData as $column => $value) {
        $setParts[] = "`$column` = :$column";
        $params[":$column"] = $value;
    }

    $sql = "UPDATE sales_bills SET " . implode(', ', $setParts) . " WHERE id = :id";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}