<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Totals
$stock_result = $conn->query("SELECT SUM(shares * price_per_share) AS total FROM stocks");
$stock_total = $stock_result->fetch_assoc()['total'] ?? 0;

$property_result = $conn->query("SELECT SUM(value) AS total FROM properties");
$property_total = $property_result->fetch_assoc()['total'] ?? 0;

$fd_result = $conn->query("SELECT SUM(amount) AS total FROM fixed_deposits");
$fd_total = $fd_result->fetch_assoc()['total'] ?? 0;

$total_investment = $stock_total + $property_total + $fd_total;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Investment Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .dashboard-card {
            flex: 1 1 250px;
            min-width: 250px;
        }
        canvas {
            max-width: 300px;
            margin: auto;
        }
        .card-header a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
        }
        .card-header a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5 text-center">
    <h2 class="mb-4">📊 Investment Dashboard</h2>

    <div class="dashboard-cards mb-4">
        <div class="card text-white bg-primary dashboard-card">
            <div class="card-header">
                <a href="view_stocks.php">📈 Stocks</a>
            </div>
            <div class="card-body">
                <h4 class="card-title">₹<?= number_format($stock_total, 2) ?></h4>
            </div>
        </div>

        <div class="card text-white bg-success dashboard-card">
            <div class="card-header">
                <a href="view_properties.php">🏠 Properties</a>
            </div>
            <div class="card-body">
                <h4 class="card-title">₹<?= number_format($property_total, 2) ?></h4>
            </div>
        </div>

        <div class="card text-white bg-warning dashboard-card">
            <div class="card-header">
                <a href="view_fixed_deposits.php">💰 Fixed Deposits</a>
            </div>
            <div class="card-body">
                <h4 class="card-title">₹<?= number_format($fd_total, 2) ?></h4>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h5><strong>Total Investment:</strong> ₹<?= number_format($total_investment, 2) ?></h5>
            </div>
        </div>
    </div>

    <div class="mt-4 mb-5">
        <canvas id="investmentChart"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('investmentChart').getContext('2d');
    const investmentChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Stocks', 'Properties', 'Fixed Deposits'],
            datasets: [{
                label: 'Investment Distribution',
                data: [<?= $stock_total ?>, <?= $property_total ?>, <?= $fd_total ?>],
                backgroundColor: [
                    'rgba(13, 110, 253, 0.8)',   // blue
                    'rgba(25, 135, 84, 0.8)',    // green
                    'rgba(255, 193, 7, 0.8)'     // yellow
                ],
                borderColor: ['#fff', '#fff', '#fff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
</body>
</html>
