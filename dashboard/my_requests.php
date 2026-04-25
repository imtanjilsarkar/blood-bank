<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}

$hospital_id = $_SESSION['user_id'];
$requests = mysqli_query($conn, "SELECT * FROM blood_requests WHERE hospital_id='$hospital_id' ORDER BY id DESC");
?>

<!-- Rest of the HTML remains the SAME as the previous my_requests.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests | LifeFlow</title>
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
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .requests-timeline {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .request-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            transition: 0.3s;
        }

        .request-card:hover {
            transform: translateX(5px);
            border-left: 4px solid #c7362b;
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .request-id {
            font-size: 1.2rem;
            font-weight: 700;
            color: #c7362b;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(243, 156, 18, 0.2);
            color: #f39c12;
        }

        .status-approved {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }

        .status-rejected {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
        }

        .request-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-item i {
            color: #c7362b;
            width: 20px;
        }

        .timeline-progress {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step .circle {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
        }

        .step.active .circle {
            background: #c7362b;
            color: white;
        }

        .step.completed .circle {
            background: #2ecc71;
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            color: #b0b0b0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-clipboard-list"></i> My Blood Requests</h1>
        <p style="color: #b0b0b0;">Track the status of all your blood requests</p>
    </div>

    <div class="requests-timeline">
        <?php while ($req = mysqli_fetch_assoc($requests)): ?>
        <?php
        $status = $req['status'];
        ?>
        <div class="request-card">
            <div class="request-header">
                <span class="request-id">#REQ-<?= str_pad($req['id'], 5, '0', STR_PAD_LEFT) ?></span>
                <span class="status-badge status-<?= $status ?>">
                    <?php if ($status == 'pending'): ?>
                        <i class="fas fa-clock"></i> Pending Review
                    <?php elseif ($status == 'approved'): ?>
                        <i class="fas fa-check-circle"></i> Approved
                    <?php else: ?>
                        <i class="fas fa-times-circle"></i> Rejected
                    <?php endif; ?>
                </span>
            </div>

            <div class="request-details">
                <div class="detail-item">
                    <i class="fas fa-user"></i>
                    <span>Patient: <strong><?= htmlspecialchars($req['patient_name']) ?></strong></span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-tint"></i>
                    <span>Blood Group: <strong><?= $req['blood_group'] ?></strong></span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-cubes"></i>
                    <span>Units: <strong><?= $req['units'] ?></strong></span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar"></i>
                    <span>Requested: <strong><?= date('M d, Y', strtotime($req['created_at'])) ?></strong></span>
                </div>
            </div>

            <div class="timeline-progress">
                <div class="progress-steps">
                    <div class="step completed">
                        <div class="circle"><i class="fas fa-paper-plane"></i></div>
                        <div class="step-label">Request Sent</div>
                    </div>
                    <div class="step <?= $status == 'pending' ? 'active' : ($status != 'pending' ? 'completed' : '') ?>">
                        <div class="circle"><i class="fas fa-search"></i></div>
                        <div class="step-label">Under Review</div>
                    </div>
                    <div class="step <?= $status == 'approved' ? 'active' : ($status == 'rejected' ? '' : '') ?>">
                        <div class="circle"><i class="fas fa-check"></i></div>
                        <div class="step-label">Final Decision</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>