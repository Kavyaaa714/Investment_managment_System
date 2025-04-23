<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: view_fixed_deposits.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM fixed_deposits WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$deposit = $result->fetch_assoc();

if (!$deposit) {
    echo "Fixed Deposit not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bank_name = $_POST['bank_name'];
    $amount = $_POST['amount'];
    $interest_rate = $_POST['interest_rate'];
    $start_date = $_POST['start_date'];
    $maturity_date = $_POST['maturity_date'];

    $stmt = $conn->prepare("UPDATE fixed_deposits SET bank_name=?, amount=?, interest_rate=?, start_date=?, maturity_date=? WHERE id=?");
    $stmt->bind_param("sddssi", $bank_name, $amount, $interest_rate, $start_date, $maturity_date, $id);

    if ($stmt->execute()) {
        header("Location: view_fixed_deposits.php");
        exit();
    } else {
        echo "Failed to update record.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Fixed Deposit</title>
</head>
<body>
    <h2>Edit Fixed Deposit</h2>
    <form method="post">
        <label>Bank Name:</label>
        <input type="text" name="bank_name" value="<?= htmlspecialchars($deposit['bank_name']) ?>" required><br><br>
        <label>Amount (₹):</label>
        <input type="number" name="amount" step="0.01" value="<?= $deposit['amount'] ?>" required><br><br>
        <label>Interest Rate (%):</label>
        <input type="number" name="interest_rate" step="0.01" value="<?= $deposit['interest_rate'] ?>" required><br><br>
        <label>Start Date:</label>
        <input type="date" name="start_date" value="<?= $deposit['start_date'] ?>" required><br><br>
        <label>Maturity Date:</label>
        <input type="date" name="maturity_date" value="<?= $deposit['maturity_date'] ?>" required><br><br>
        <input type="submit" value="Update">
    </form>
    <a href="view_fixed_deposits.php">Back</a>
</body>
</html>
