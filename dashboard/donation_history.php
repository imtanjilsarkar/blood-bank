<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location: ../login.php");
    exit();
}

$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Donation History</title>

<style>
body {
    margin:0;
    font-family:Inter;
    background: linear-gradient(135deg,#f5f7fa,#fff);
}

.container {
    padding:40px;
}

.box {
    background: rgba(255,255,255,0.35);
    backdrop-filter: blur(18px);
    padding:20px;
    border-radius:18px;
    box-shadow:0 15px 30px rgba(0,0,0,0.08);
}

table {
    width:100%;
    border-collapse:collapse;
}

th {
    background:#111;
    color:white;
    padding:10px;
}

td {
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

.badge {
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

</style>
</head>

<body>

<div class="container">

<div class="box">

<h2>Donation History</h2>

<table>

<tr>
    <th>ID</th>
    <th>Blood Group</th>
    <th>Units</th>
    <th>Date</th>
</tr>

<?php
$q = mysqli_query($conn,"SELECT * FROM donations WHERE donor_name='$name' ORDER BY id DESC");

while($r = mysqli_fetch_assoc($q)) {
?>

<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['blood_group'] ?></td>
    <td><?= $r['units'] ?></td>
    <td><?= $r['donation_date'] ?></td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>