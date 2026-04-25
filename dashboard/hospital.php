<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['user_id'];
$hospital_name = $_SESSION['name'];

// Use hospital_id for queries
$totalRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE hospital_id='$hospital_id'"))['c'];
$pendingRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE hospital_id='$hospital_id' AND status='pending'"))['c'];
$approvedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE hospital_id='$hospital_id' AND status='approved'"))['c'];
$rejectedRequests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE hospital_id='$hospital_id' AND status='rejected'"))['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard | LifeFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a0a, #1a0a0a);
            color: #ffffff;
        }

        .dashboard-container {
            display: flex;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px 0;
        }

        .sidebar-header {
            text-align: center;
            padding-bottom: 30px;
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

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 25px;
            color: #b0b0b0;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-menu a:hover {
            background: linear-gradient(90deg, rgba(199, 54, 43, 0.2), transparent);
            color: #c7362b;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
        }

        .top-bar {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            text-align: center;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border: 1px solid rgba(199, 54, 43, 0.5);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: #c7362b;
            margin-bottom: 15px;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 800;
        }

        .action-button {
            background: linear-gradient(135deg, #c7362b, #a1241a);
            padding: 15px 30px;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(199, 54, 43, 0.4);
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
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-droplet"></i> LifeFlow</h2>
            <p style="color: #c7362b;">Hospital Portal</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="hospital.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="request_blood.php"><i class="fas fa-hand-holding-medical"></i> <span>Request Blood</span></a></li>
            <li><a href="my_requests.php"><i class="fas fa-list"></i> <span>My Requests</span></a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div>
                <h2>Welcome, <?php echo $hospital_name; ?></h2>
                <p style="color: #b0b0b0;">Manage your blood requests and track approvals</p>
            </div>
            <a href="../index.php" class="home-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-tasks"></i>
                <div class="number"><?= $totalRequests ?></div>
                <p>Total Requests</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <div class="number"><?= $pendingRequests ?></div>
                <p>Pending</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <div class="number"><?= $approvedRequests ?></div>
                <p>Approved</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-times-circle"></i>
                <div class="number"><?= $rejectedRequests ?></div>
                <p>Rejected</p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="request_blood.php" class="action-button">
                <i class="fas fa-plus-circle"></i> Create New Blood Request
            </a>
        </div>
    </div>
</div>
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