<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $bank = $_POST['bank_name'];
    $amount = $_POST['amount'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO fixed_deposits (user_id, bank_name, amount, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $bank, $amount, $start, $end);

    if ($stmt->execute()) {
        header("Location: view_fixed_deposits.php");
        exit();
    } else {
        echo "Error adding deposit: " . $stmt->error;
    }
}
?>

<h2>Add Fixed Deposit</h2>
<form method="POST">
    Bank Name: <input type="text" name="bank_name" required><br>
    Amount: <input type="number" name="amount" step="0.01" required><br>
    Start Date: <input type="date" name="start_date" required><br>
    End Date: <input type="date" name="end_date" required><br>
    <input type="submit" value="Add Deposit">
</form>
``
