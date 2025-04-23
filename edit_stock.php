<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: view_stocks.php");
    exit();
}

$id = intval($_GET['id']);

// Get existing stock record
$stmt = $conn->prepare("SELECT * FROM stocks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$stock = $result->fetch_assoc();

if (!$stock) {
    echo "Stock record not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'];
    $shares = intval($_POST['shares']);
    $price_per_share = floatval($_POST['purchase_price']);  // Use correct type
    $purchase_date = $_POST['purchase_date'];

    $stmt = $conn->prepare("UPDATE stocks SET company_name=?, shares=?, price_per_share=?, purchase_date=? WHERE id=?");
    $stmt->bind_param("sidss", $company_name, $shares, $price_per_share, $purchase_date, $id);

    if ($stmt->execute()) {
        header("Location: view_stocks.php");
        exit();
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Edit Stock</h2>
    <form method="post" class="border p-4 bg-white rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">Company Name:</label>
            <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($stock['company_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Number of Shares:</label>
            <input type="number" name="shares" class="form-control" value="<?= htmlspecialchars($stock['shares']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price per Share (₹):</label>
            <input type="number" name="purchase_price" step="0.01" class="form-control" value="<?= htmlspecialchars($stock['price_per_share']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Date:</label>
            <input type="date" name="purchase_date" class="form-control" value="<?= htmlspecialchars($stock['purchase_date']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_stocks.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
