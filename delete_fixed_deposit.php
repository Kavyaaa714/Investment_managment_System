<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM fixed_deposits WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_fixed_deposits.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
