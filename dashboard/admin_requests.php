<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* ACTION HANDLER */
if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "approve") {
        mysqli_query($conn, "UPDATE blood_requests SET status='approved' WHERE id=$id");
    }

    if ($action == "reject") {
        mysqli_query($conn, "UPDATE blood_requests SET status='rejected' WHERE id=$id");
    }

    header("Location: admin_requests.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Requests</title>

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
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        /* STATUS BADGES */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .approved {
            background: #d4edda;
            color: #155724;
        }

        .rejected {
            background: #f8d7da;
            color: #721c24;
        }

        /* BUTTONS */
        .btn {
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-size: 12px;
            margin: 2px;
        }

        .approve {
            background: #28a745;
        }

        .reject {
            background: #dc3545;
        }

        .btn:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Blood Requests Panel</h2>

    <table>

        <tr>
            <th>ID</th>
            <th>Hospital</th>
            <th>Blood Group</th>
            <th>Units</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM blood_requests ORDER BY id DESC");

        while ($row = mysqli_fetch_assoc($result)) {
        ?>

        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['hospital_name']; ?></td>
            <td><?php echo $row['blood_group']; ?></td>
            <td><?php echo $row['units']; ?></td>

            <!-- STATUS BADGE -->
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

            <!-- ACTION -->
            <td>
                <a class="btn approve" href="?action=approve&id=<?php echo $row['id']; ?>">Approve</a>
                <a class="btn reject" href="?action=reject&id=<?php echo $row['id']; ?>">Reject</a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>