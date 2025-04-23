<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM properties";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE property_name LIKE ?";
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
    <title>View Properties</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">🏠 Property Portfolio</h2>

    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" placeholder="Search by Property Name" class="form-control me-2" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="add_property.php" class="btn btn-success ms-2">➕ Add New</a>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Property Name</th>
            <th>Location</th>
            <th>Value (₹)</th>
            <th>Purchase Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['property_name']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td>₹<?= number_format($row['value']) ?></td>
                <td><?= $row['purchase_date'] ?></td>
                <td>
                    <a href="edit_property.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_property.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this property?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
