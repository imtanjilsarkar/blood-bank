<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location: ../login.php");
    exit();
}

$name = $_SESSION['name'];

if (isset($_POST['request'])) {

    $blood = $_POST['blood_group'];
    $units = 1;

    mysqli_query($conn, "INSERT INTO donation_requests (donor_name, blood_group, units)
    VALUES ('$name', '$blood', '$units')");

    $msg = "Request Sent Successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Donation Request</title>

<style>
body {
    margin:0;
    font-family: Inter;
    background: linear-gradient(135deg,#f5f7fa,#fff);
}

.box {
    width: 400px;
    margin: 80px auto;
    padding: 25px;

    background: rgba(255,255,255,0.4);
    backdrop-filter: blur(15px);

    border-radius: 16px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}

input, select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
}

button {
    width: 100%;
    padding: 10px;
    background: #b02a2a;
    color: white;
    border: none;
    border-radius: 10px;
}

</style>
</head>

<body>

<div class="box">

<h2>Request Donation</h2>

<?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

<form method="POST">

<select name="blood_group" required>
    <option>A+</option>
    <option>A-</option>
    <option>B+</option>
    <option>B-</option>
    <option>O+</option>
    <option>O-</option>
    <option>AB+</option>
    <option>AB-</option>
</select>

<button name="request">Send Request</button>

</form>

</div>

</body>
</html>