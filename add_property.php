<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_name = $_POST['property_name'];
    $location = $_POST['location'];
    $value = $_POST['value'];
    $purchase_date = $_POST['purchase_date'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO properties (user_id, property_name, location, value, purchase_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $user_id, $property_name, $location, $value, $purchase_date);

    if ($stmt->execute()) {
        echo "Property added successfully. <a href='dashboard.php'>Go to Dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<h2>Add Property</h2>
<form method="POST">
    Property Name: <input type="text" name="property_name" required><br>
    Location: <input type="text" name="location" required><br>
    Value (₹): <input type="number" name="value" step="0.01" required><br>
    Purchase Date: <input type="date" name="purchase_date" required><br>
    <input type="submit" value="Add">
</form>
<a href="dashboard.php">Back to Dashboard</a>
