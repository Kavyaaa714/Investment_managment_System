<?php
require 'db.php';

$response = [];

// Totals from each table
$fd = $conn->query("SELECT SUM(amount) AS total FROM fixed_deposits")->fetch_assoc();
$prop = $conn->query("SELECT SUM(value) AS total FROM properties")->fetch_assoc();
$stocks = $conn->query("SELECT SUM(value) AS total FROM stocks")->fetch_assoc();

$response['fixed_deposits'] = (float) ($fd['total'] ?? 0);
$response['properties'] = (float) ($prop['total'] ?? 0);
$response['stocks'] = (float) ($stocks['total'] ?? 0);

echo json_encode($response);
?>
