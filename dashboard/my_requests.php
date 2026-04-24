<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}

$hospital = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Requests</title>

    <style>
        body {
            font-family: Inter, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .container {
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        th {
            background: #111;
            color: white;
            padding: 12px;
        }

        td {
            text-align: center;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .pending { background: #fff3cd; color: #856404; }
        .approved { background: #d4edda; color: #155724; }
        .rejected { background: #f8d7da; color: #721c24; }
    </style>
</head>

<body>

<div class="container">

    <h2>My Blood Requests</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Blood Group</th>
            <th>Units</th>
            <th>Patient</th>
            <th>Reason</th>
            <th>Status</th>
        </tr>

        <?php
        $sql = "SELECT * FROM blood_requests WHERE hospital_name='$hospital' ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>

        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['blood_group']; ?></td>
            <td><?php echo $row['units']; ?></td>
            <td><?php echo $row['patient_name']; ?></td>
            <td><?php echo $row['reason']; ?></td>

            <td>
                <?php
                if ($row['status'] == "approved") {
                    echo "<span class='badge approved'>Approved</span>";
                } elseif ($row['status'] == "rejected") {
                    echo "<span class='badge rejected'>Rejected</span>";
                } else {
                    echo "<span class='badge pending'>Pending</span>";
                }
                ?>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>