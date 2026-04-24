<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* =========================
   APPROVE REQUEST
========================= */
if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    // fetch request
    $req = mysqli_fetch_assoc(mysqli_query(
        $conn,
        "SELECT * FROM blood_requests WHERE id=$id"
    ));

    if ($req) {

        if ($action == "approve") {

            // 1. update request
            mysqli_query($conn, "UPDATE blood_requests SET status='approved' WHERE id=$id");

            // 2. update blood stock (DEDUCT units)
            mysqli_query($conn, "
                UPDATE blood_stock 
                SET units_available = units_available - {$req['units']}
                WHERE blood_group='{$req['blood_group']}'
            ");
        }

        if ($action == "reject") {
            mysqli_query($conn, "UPDATE blood_requests SET status='rejected' WHERE id=$id");
        }
    }

    header("Location: admin_requests.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Blood Requests</title>

<style>
body {
    margin: 0;
    font-family: Inter, sans-serif;
    background: linear-gradient(135deg, #f4f6f9, #ffffff);
}

/* GLASS CONTAINER */
.container {
    padding: 25px;
}

/* TITLE */
h2 {
    margin-bottom: 20px;
    color: #111;
}

/* GLASS TABLE */
table {
    width: 100%;
    border-collapse: collapse;

    background: rgba(255,255,255,0.4);
    backdrop-filter: blur(12px);

    border-radius: 14px;
    overflow: hidden;

    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

th {
    background: rgba(0,0,0,0.85);
    color: white;
    padding: 14px;
}

td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

/* BADGES */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.pending { background:#fff3cd; color:#856404; }
.approved { background:#d4edda; color:#155724; }
.rejected { background:#f8d7da; color:#721c24; }

/* BUTTONS */
.btn {
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-size: 12px;
    margin: 2px;
    transition: 0.2s;
}

.approve { background:#28a745; }
.reject { background:#dc3545; }

.btn:hover {
    transform: scale(1.05);
}
</style>
</head>

<body>

<div class="container">

<h2>Hospital Blood Requests</h2>

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
    <td><?= $row['id'] ?></td>
    <td><?= $row['hospital_name'] ?></td>
    <td><?= $row['blood_group'] ?></td>
    <td><?= $row['units'] ?></td>

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

    <td>
        <a class="btn approve" href="?action=approve&id=<?= $row['id'] ?>">Approve</a>
        <a class="btn reject" href="?action=reject&id=<?= $row['id'] ?>">Reject</a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>