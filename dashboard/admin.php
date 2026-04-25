<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch dashboard data
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE status='pending'"))['c'];
$approved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE status='approved'"))['c'];
$rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE status='rejected'"))['c'];
$totalDonors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='donor'"))['c'];
$totalHospitals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='hospital'"))['c'];

// Stock data
$stock = mysqli_query($conn, "SELECT * FROM blood_stock");
$bloodGroups = [];
$units = [];
while ($row = mysqli_fetch_assoc($stock)) {
    $bloodGroups[] = $row['blood_group'];
    $units[] = $row['units_available'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | LifeFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
        }

        .dashboard-container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px 0;
        }

        .sidebar-header {
            text-align: center;
            padding: 0 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 25px;
            color: #b0b0b0;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-menu a:hover, .sidebar-menu .active a {
            background: linear-gradient(90deg, rgba(199, 54, 43, 0.2), transparent);
            color: #c7362b;
            border-left: 3px solid #c7362b;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
        }

        /* Top Bar with Home Button */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px 30px;
            margin-bottom: 30px;
        }

        .home-btn {
            background: rgba(199, 54, 43, 0.2);
            border: 1px solid #c7362b;
            padding: 8px 20px;
            border-radius: 40px;
            color: #c7362b;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .home-btn:hover {
            background: #c7362b;
            color: white;
            transform: translateY(-2px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(199, 54, 43, 0.5);
        }

        .stat-card i {
            font-size: 2rem;
            color: #c7362b;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            font-size: 0.9rem;
            color: #b0b0b0;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 800;
        }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .chart-card h3 {
            margin-bottom: 20px;
        }

        canvas {
            max-height: 300px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .sidebar-header h2, .sidebar-menu a span {
                display: none;
            }
            .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-droplet"></i> LifeFlow</h2>
            <p style="font-size: 0.8rem; color:#c7362b;">Admin Panel</p>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="admin.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="admin_requests.php"><i class="fas fa-tasks"></i> <span>Blood Requests</span></a></li>
            <li><a href="blood_stock.php"><i class="fas fa-warehouse"></i> <span>Blood Stock</span></a></li>
            <li><a href="update_stock.php"><i class="fas fa-plus-circle"></i> <span>Update Stock</span></a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <h2>Welcome back, <?php echo $_SESSION['name']; ?></h2>
                <p style="color: #b0b0b0;">Here's what's happening with your blood bank today</p>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="../index.php" class="home-btn">
                    <i class="fas fa-home"></i> Home
                </a>
                <i class="fas fa-bell" style="font-size: 1.5rem; cursor: pointer;"></i>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-tint"></i>
                <h3>Total Donors</h3>
                <div class="number"><?= $totalDonors ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-hospital"></i>
                <h3>Total Hospitals</h3>
                <div class="number"><?= $totalHospitals ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3>Pending Requests</h3>
                <div class="number"><?= $pending ?></div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <h3>Approved Requests</h3>
                <div class="number"><?= $approved ?></div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Request Status Distribution</h3>
                <canvas id="requestChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Blood Stock Overview</h3>
                <canvas id="stockChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById("requestChart"), {
        type: "doughnut",
        data: {
            labels: ["Pending", "Approved", "Rejected"],
            datasets: [{
                data: [<?= $pending ?>, <?= $approved ?>, <?= $rejected ?>],
                backgroundColor: ["#f39c12", "#2ecc71", "#e74c3c"],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#fff' } }
            }
        }
    });

    new Chart(document.getElementById("stockChart"), {
        type: "bar",
        data: {
            labels: <?= json_encode($bloodGroups) ?>,
            datasets: [{
                label: "Units Available",
                data: <?= json_encode($units) ?>,
                backgroundColor: "#c7362b",
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#fff' } },
                x: { ticks: { color: '#fff' } }
            },
            plugins: { legend: { labels: { color: '#fff' } } }
        }
    });
</script>
<!-- Advanced Floating Home Button -->
<a href="../index.php" class="floating-home-btn">
    <i class="fas fa-home"></i>
    <span class="btn-text">Home</span>
</a>

<style>
    .floating-home-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: linear-gradient(135deg, #c7362b, #a1241a);
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
        z-index: 9999;
        overflow: hidden;
        white-space: nowrap;
    }
    
    .floating-home-btn .btn-text {
        display: none;
        margin-left: 8px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .floating-home-btn:hover {
        width: auto;
        border-radius: 40px;
        padding: 0 20px;
        gap: 8px;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(199, 54, 43, 0.5);
    }
    
    .floating-home-btn:hover .btn-text {
        display: inline;
    }
    
    .floating-home-btn:hover i {
        margin: 0;
    }
    
    /* Pulse animation */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(199, 54, 43, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(199, 54, 43, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(199, 54, 43, 0);
        }
    }
    
    .floating-home-btn {
        animation: pulse 2s infinite;
    }
</style>
</body>
</html>