<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<h2>Update Blood Stock 🩸</h2>

<form method="POST">
    Blood Group:
    <select name="blood_group">
        <option>A+</option>
        <option>A-</option>
        <option>B+</option>
        <option>B-</option>
        <option>O+</option>
        <option>O-</option>
        <option>AB+</option>
        <option>AB-</option>
    </select><br><br>

    Units:
    <input type="number" name="units" required><br><br>

    Action:
    <select name="action">
        <option value="add">Add</option>
        <option value="remove">Remove</option>
    </select><br><br>

    <button type="submit" name="update">Update</button>
</form>


<?php
if (isset($_POST['update'])) {

    $blood_group = $_POST['blood_group'];
    $units = $_POST['units'];
    $action = $_POST['action'];

    $query = "SELECT units_available FROM blood_stock WHERE blood_group='$blood_group'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $current = $row['units_available'];

    if ($action == "add") {
        $new = $current + $units;
    } else {
        $new = $current - $units;
        if ($new < 0) $new = 0;
    }

    $update = "UPDATE blood_stock 
               SET units_available=$new 
               WHERE blood_group='$blood_group'";

    mysqli_query($conn, $update);

    echo "<p style='color:green;'>Stock Updated ✅</p>";
}
?>