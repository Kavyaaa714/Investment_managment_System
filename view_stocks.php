<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // DB connection file

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM stocks";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE company_name LIKE ?";
    $params[] = '%' . $search . '%';
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Stocks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">📈 Stock Portfolio</h2>

    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" placeholder="Search by Company Name" class="form-control me-2" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="add_stock.php" class="btn btn-success ms-2">➕ Add New</a>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Company Name</th>
            <th>Number of Shares</th>
            <th>Price per Share (₹)</th>
            <th>Purchase Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['company_name']) ?></td>
                <td><?= $row['shares'] ?></td>
                <td>₹<?= $row['price_per_share'] ?></td>
                <td><?= $row['purchase_date'] ?></td>
                <td>
                    <a href="edit_stock.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_stock.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this stock?')">Delete</a>

                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
