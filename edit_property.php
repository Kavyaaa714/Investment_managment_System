<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: view_properties.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();

if (!$property) {
    echo "Property not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_name = $_POST['property_name'];
    $location = $_POST['location'];
    $value = $_POST['value'];
    $purchase_date = $_POST['purchase_date'];

    $stmt = $conn->prepare("UPDATE properties SET property_name=?, location=?, value=?, purchase_date=? WHERE id=?");
    $stmt->bind_param("ssdsi", $property_name, $location, $value, $purchase_date, $id);

    if ($stmt->execute()) {
        header("Location: view_properties.php");
        exit();
    } else {
        echo "Failed to update record.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Property</title>
</head>
<body>
    <h2>Edit Property</h2>
    <form method="post">
        <label>Property Name:</label>
        <input type="text" name="property_name" value="<?= htmlspecialchars($property['property_name']) ?>" required><br><br>
        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($property['location']) ?>" required><br><br>
        <label>Value (₹):</label>
        <input type="number" name="value" step="0.01" value="<?= $property['value'] ?>" required><br><br>
        <label>Purchase Date:</label>
        <input type="date" name="purchase_date" value="<?= $property['purchase_date'] ?>" required><br><br>
        <input type="submit" value="Update">
    </form>
    <a href="view_properties.php">Back</a>
</body>
</html>
