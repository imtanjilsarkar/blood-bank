<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location: ../login.php");
    exit();
}

$donor_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$totalDonations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM donations WHERE donor_id='$donor_id'"))['c'];
$lastDonation = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(donation_date) as last FROM donations WHERE donor_id='$donor_id'"))['last'];

// Check eligibility
$eligible = true;
$daysLeft = 0;
if ($lastDonation) {
    $lastDate = new DateTime($lastDonation);
    $today = new DateTime();
    $diff = $today->diff($lastDate)->days;
    if ($diff < 90) {
        $eligible = false;
        $daysLeft = 90 - $diff;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard | LifeFlow</title>
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
            background: linear-gradient(135deg, #0a0a0a, #0a1520);
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

        .hero-banner {
            background: linear-gradient(135deg, rgba(199, 54, 43, 0.2), rgba(199, 54, 43, 0.05));
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid rgba(199, 54, 43, 0.3);
        }

        .hero-banner h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .eligibility-card {
            background: <?= $eligible ? 'rgba(46, 204, 113, 0.1)' : 'rgba(231, 76, 60, 0.1)' ?>;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid <?= $eligible ? '#2ecc71' : '#e74c3c' ?>;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
        }

        .stat-card i {
            font-size: 2rem;
            color: #c7362b;
            margin-bottom: 15px;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 800;
        }

        .btn-donate {
            background: linear-gradient(135deg, #c7362b, #a1241a);
            padding: 14px 28px;
            border-radius: 50px;
            text-decoration: none;
            color: white;
            display: inline-block;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-donate:hover {
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
            <p style="color: #c7362b;">Donor Portal</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="donor.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="donation_history.php"><i class="fas fa-history"></i> <span>Donation History</span></a></li>
            <li><a href="request_donation.php"><i class="fas fa-hand-holding-heart"></i> <span>Request Donation</span></a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div>
                <h2>Welcome, <?php echo $name; ?>!</h2>
                <p style="color: #b0b0b0;">Your donation journey matters</p>
            </div>
            <a href="../index.php" class="home-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>

        <div class="hero-banner">
            <h1><i class="fas fa-heartbeat"></i> Thank You, <?php echo $name; ?>!</h1>
            <p>Your generosity saves lives. Every drop counts.</p>
        </div>

        <div class="eligibility-card">
            <h3><i class="fas fa-stethoscope"></i> Eligibility Status</h3>
            <?php if ($eligible): ?>
                <p style="color: #2ecc71; font-size: 1.2rem;">✅ You are eligible to donate blood!</p>
                <a href="request_donation.php" class="btn-donate" style="margin-top: 15px;">Schedule Donation →</a>
            <?php else: ?>
                <p style="color: #e74c3c;">⚠️ You need to wait <?= $daysLeft ?> more days before your next donation</p>
                <p>Minimum gap between donations is 90 days for your safety</p>
            <?php endif; ?>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-tint"></i>
                <div class="number"><?= $totalDonations ?></div>
                <p>Total Donations</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-alt"></i>
                <div class="number"><?= $lastDonation ? date('M d, Y', strtotime($lastDonation)) : 'N/A' ?></div>
                <p>Last Donation</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <div class="number"><?= $totalDonations * 3 ?></div>
                <p>Lives Saved</p>
            </div>
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