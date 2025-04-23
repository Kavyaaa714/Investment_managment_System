<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = $_POST['company_name'];
    $shares = $_POST['shares'];
    $price_per_share = $_POST['price_per_share'];
    $purchase_date = $_POST['purchase_date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO stocks (user_id, company_name, shares, price_per_share, purchase_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isids", $user_id, $company_name, $shares, $price_per_share, $purchase_date);
    
    if ($stmt->execute()) {
        header("Location: view_stocks.php");
        exit();
    } else {
        $error = "Failed to add record.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Stock Investment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">➕ Add Stock Investment</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-4 shadow-sm rounded">
        <div class="mb-3">
            <label class="form-label">Company Name:</label>
            <input type="text" name="company_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Number of Shares:</label>
            <input type="number" name="shares" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price per Share (₹):</label>
            <input type="number" name="price_per_share" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Purchase Date:</label>
            <input type="date" name="purchase_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Add</button>
        <a href="view_stocks.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
