<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM fixed_deposits WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bank = $_POST['bank_name'];
    $amount = $_POST['amount'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $stmt = $conn->prepare("UPDATE fixed_deposits SET bank_name=?, amount=?, start_date=?, end_date=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sdssii", $bank, $amount, $start, $end, $id, $user_id);

    if ($stmt->execute()) {
        header("Location: view_fixed_deposits.php");
        exit();
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>

<h2>Edit Fixed Deposit</h2>
<form method="POST">
    Bank Name: <input type="text" name="bank_name" value="<?php echo $row['bank_name']; ?>" required><br>
    Amount: <input type="number" name="amount" value="<?php echo $row['amount']; ?>" step="0.01" required><br>
    Start Date: <input type="date" name="start_date" value="<?php echo $row['start_date']; ?>" required><br>
    End Date: <input type="date" name="end_date" value="<?php echo $row['end_date']; ?>" required><br>
    <input type="submit" value="Update">
</form>
