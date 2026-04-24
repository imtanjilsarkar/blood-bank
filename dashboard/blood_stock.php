<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Blood Stock System</title>

<style>

/* ===== BACKGROUND ===== */
body {
    margin: 0;
    font-family: Inter, sans-serif;

    background: linear-gradient(135deg, #f4f6f9, #ffecec, #ffffff);
    min-height: 100vh;
    overflow-x: hidden;
}

/* floating glow */
body::before,
body::after {
    content: "";
    position: fixed;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: rgba(176, 42, 42, 0.15);
    filter: blur(90px);
    z-index: 0;
    animation: float 10s infinite ease-in-out;
}

body::before {
    top: -80px;
    left: -80px;
}

body::after {
    bottom: -80px;
    right: -80px;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(40px); }
    100% { transform: translateY(0px); }
}

/* ===== CONTAINER ===== */
.container {
    padding: 40px;
    position: relative;
    z-index: 2;
}

/* TITLE */
h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #222;
}

/* ===== GLASS TABLE WRAPPER ===== */
.table-box {
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    border-radius: 16px;
    padding: 10px;

    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
th {
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 14px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ROWS */
td {
    text-align: center;
    padding: 14px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    font-size: 14px;
}

/* ROW HOVER */
tr:hover {
    background: rgba(176, 42, 42, 0.08);
    transition: 0.2s;
}

/* ===== BADGES ===== */
.badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* status colors */
.low {
    background: rgba(255, 99, 132, 0.15);
    color: #c0392b;
}

.critical {
    background: rgba(255, 193, 7, 0.2);
    color: #856404;
}

.ok {
    background: rgba(46, 204, 113, 0.2);
    color: #1e7e34;
}

/* HEADER CARD STYLE (optional feel) */
.header-box {
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 16px;

    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);

    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

</style>
</head>

<body>

<div class="container">

    <div class="header-box">
        <h2>🩸 Blood Stock Management</h2>
        <p>Real-time inventory monitoring system for hospital blood bank</p>
    </div>

    <div class="table-box">

        <table>

            <tr>
                <th>ID</th>
                <th>Blood Group</th>
                <th>Units Available</th>
                <th>Status</th>
            </tr>

            <?php
            $result = mysqli_query($conn, "SELECT * FROM blood_stock");

            while ($row = mysqli_fetch_assoc($result)) {
            ?>

            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><b><?php echo $row['blood_group']; ?></b></td>
                <td><?php echo $row['units_available']; ?></td>

                <td>
                    <?php
                    $units = $row['units_available'];

                    if ($units <= 2) {
                        echo "<span class='badge low'>Critical</span>";
                    } elseif ($units <= 5) {
                        echo "<span class='badge critical'>Low</span>";
                    } else {
                        echo "<span class='badge ok'>Available</span>";
                    }
                    ?>
                </td>
            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>