<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM fixed_deposits WHERE user_id = ? AND (bank_name LIKE ? OR amount LIKE ?)";
$stmt = $conn->prepare($sql);
$searchParam = "%{$search}%";
$stmt->bind_param("iss", $user_id, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Fixed Deposits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">💰 Fixed Deposits</h2>

    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" placeholder="Search by bank or amount" class="form-control me-2" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="add_fixed_deposit.php" class="btn btn-success ms-2">➕ Add New</a>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Bank</th>
            <th>Amount (₹)</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['bank_name']) ?></td>
                <td>₹<?= number_format($row['amount'], 2) ?></td>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['end_date'] ?></td>
                <td>
                    <a href="edit_fixed_deposit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_fixed_deposit.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this fixed deposit?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</div>
</body>
</html>
