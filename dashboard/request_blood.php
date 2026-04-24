<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}

// handle request
if (isset($_POST['request'])) {

    $hospital = $_SESSION['name'];
    $blood_group = $_POST['blood_group'];
    $units = $_POST['units'];
    $patient_name = $_POST['patient_name'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO blood_requests (hospital_name, blood_group, units, patient_name, reason, status)
            VALUES ('$hospital', '$blood_group', '$units', '$patient_name', '$reason', 'pending')";

    mysqli_query($conn, $sql);

    $success = "Request submitted successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Blood</title>

    <style>
        body {
            margin: 0;
            font-family: Inter, sans-serif;
            background: #f4f6f9;
        }

        /* LAYOUT */
        .container {
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px;
            height: 100vh;
            background: #111;
            color: white;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #ccc;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #b02a2a;
            color: white;
        }

        /* MAIN */
        .main {
            margin-left: 220px;
            width: calc(100% - 220px);
            padding: 20px;
        }

        /* CARD FORM */
        .form-card {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #b02a2a;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }

    </style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Hospital Panel</h2>
        <a href="hospital.php">Dashboard</a>
        <a href="request_blood.php">Request Blood</a>
        <a href="my_requests.php">My Requests</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="form-card">

            <h2>Blood Request Form</h2>

            <?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>

            <form method="POST">

                <input type="text" name="patient_name" placeholder="Patient Name" required>

                <select name="blood_group" required>
                    <option value="">Select Blood Group</option>
                    <option>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>O+</option>
                    <option>O-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                </select>

                <input type="number" name="units" placeholder="Units Required" required>

                <textarea name="reason" placeholder="Reason for Request" rows="4"></textarea>

                <button type="submit" name="request">Submit Request</button>

            </form>

        </div>

    </div>

</div>

</body>
</html>