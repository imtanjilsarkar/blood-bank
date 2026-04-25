<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location: ../login.php");
    exit();
}

$donor_id = $_SESSION['user_id'];
$donations = mysqli_query($conn, "SELECT * FROM donations WHERE donor_id='$donor_id' ORDER BY donation_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History | LifeFlow</title>
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
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow-x: auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            color: #c7362b;
            border-bottom: 2px solid rgba(199, 54, 43, 0.3);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-history"></i> My Donation History</h1>
        <p style="color: #b0b0b0;">Track all your past blood donations</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Blood Group</th>
                    <th>Units</th>
                    <th>Donation Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;
                while ($row = mysqli_fetch_assoc($donations)): 
                ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><strong><?= $row['blood_group'] ?></strong></td>
                    <td><?= $row['units'] ?> unit(s)</td>
                    <td><?= date('F d, Y', strtotime($row['donation_date'])) ?></td>
                    <td><span class="badge"><i class="fas fa-check-circle"></i> Completed</span></td>
                </tr>
                <?php endwhile; ?>
                
                <?php if ($count == 1): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #b0b0b0;">
                        <i class="fas fa-info-circle"></i> No donation records found yet
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>