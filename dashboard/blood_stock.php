<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$stock = mysqli_query($conn, "SELECT * FROM blood_stock ORDER BY FIELD(blood_group, 'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Stock | LifeFlow</title>
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
            background: #0a0a0a;
            color: #ffffff;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* Stock Grid */
        .stock-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stock-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 25px;
            text-align: center;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stock-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .blood-group {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .units {
            font-size: 3rem;
            font-weight: 800;
            margin: 20px 0;
        }

        .status {
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
        }

        .status-critical {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            animation: pulse 1s infinite;
        }

        .status-low {
            background: rgba(243, 156, 18, 0.2);
            color: #f39c12;
        }

        .status-normal {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            margin-top: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .stat-summary {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }

        .stat-summary i {
            font-size: 2rem;
            color: #c7362b;
            margin-bottom: 10px;
        }

        .stat-summary .value {
            font-size: 1.8rem;
            font-weight: 800;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-warehouse"></i> Blood Stock Inventory</h1>
        <p style="color: #b0b0b0; margin-top: 10px;">Real-time blood availability across all blood groups</p>
    </div>

    <div class="stock-grid">
        <?php 
        $totalUnits = 0;
        $criticalCount = 0;
        while ($row = mysqli_fetch_assoc($stock)):
            $units = $row['units_available'];
            $totalUnits += $units;
            $status = '';
            $statusClass = '';
            $percentage = min(($units / 50) * 100, 100);
            
            if ($units <= 2) {
                $status = '⚠️ CRITICAL';
                $statusClass = 'status-critical';
                $criticalCount++;
                $fillColor = '#e74c3c';
            } elseif ($units <= 5) {
                $status = '⚠️ LOW STOCK';
                $statusClass = 'status-low';
                $fillColor = '#f39c12';
            } else {
                $status = '✓ AVAILABLE';
                $statusClass = 'status-normal';
                $fillColor = '#2ecc71';
            }
        ?>
        <div class="stock-card" data-aos="fade-up">
            <div class="blood-group"><?= $row['blood_group'] ?></div>
            <div class="units"><?= $units ?> <span style="font-size: 1rem;">units</span></div>
            <div class="status <?= $statusClass ?>"><?= $status ?></div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $percentage ?>%; background: <?= $fillColor ?>;"></div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="summary-stats">
        <div class="stat-summary">
            <i class="fas fa-tint"></i>
            <div class="value"><?= $totalUnits ?></div>
            <p>Total Units Available</p>
        </div>
        <div class="stat-summary">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="value"><?= $criticalCount ?></div>
            <p>Critical Stock Groups</p>
        </div>
        <div class="stat-summary">
            <i class="fas fa-chart-line"></i>
            <div class="value"><?= round($totalUnits / 8, 1) ?></div>
            <p>Average Units/Group</p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>
</body>
</html>