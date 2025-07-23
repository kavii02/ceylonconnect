<?php
$conn = new mysqli("localhost", "root", "", "ceylonconnect");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Data
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM user")->fetch_assoc()['count'];
$visitedUsers = 0; // Not tracked yet
$postedItems = $conn->query("SELECT COUNT(*) AS count FROM goods")->fetch_assoc()['count'];
$exchangeRequests = 0; // Not available yet
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CeylonConnect</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <?php include('includes/header.php'); ?>

    <div class="main-layout">
        <div class="sidebar">
            <a href="Dashboard.php" class="side-button active">Dashboard</a>
            <a href="manageUsers.php" class="side-button">Users</a>
        </div>

        <div class="container">
            <h1>Welcome to Admin Dashboard</h1>

            <div class="stats">
                <div class="card">Registered Users: <?= $totalUsers ?></div>
                <div class="card">Visited Users: Not Tracked</div>
                <div class="card">Posted Items: <?= $postedItems ?></div>
                <div class="card">Exchange Requests: Not Available</div>
            </div>

            <div class="chart-container">
                <canvas id="userPieChart"></canvas>
            </div>
        </div>
    </div>


    <script>
        const ctx = document.getElementById('userPieChart').getContext('2d');
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Total Registered Users'],
                datasets: [{
                    data: [<?= $totalUsers ?>],
                    backgroundColor: ['#4caf50']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'User Registration Overview'
                    }
                }
            }
        });
    </script>

    </body>
</html>