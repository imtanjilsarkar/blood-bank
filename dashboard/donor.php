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
<title>Donor Dashboard</title>

<style>

/* ===== BACKGROUND ===== */
body {
    margin: 0;
    font-family: Inter, sans-serif;

    background: linear-gradient(135deg, #f4f6f9, #ffecec, #ffffff);
    min-height: 100vh;
}

/* floating blobs */
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

/* ===== LAYOUT ===== */
.container {
    display: flex;
    position: relative;
    z-index: 2;
}

/* ===== SIDEBAR GLASS ===== */
.sidebar {
    width: 220px;
    height: 100vh;
    position: fixed;
    padding-top: 20px;

    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    color: white;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #ddd;
    text-decoration: none;
    transition: 0.3s;
}

.sidebar a:hover {
    background: rgba(176,42,42,0.7);
    color: white;
}

/* ===== MAIN ===== */
.main {
    margin-left: 220px;
    width: calc(100% - 220px);
}

/* ===== TOP BAR ===== */
.topbar {
    margin: 20px;
    padding: 15px 25px;

    background: rgba(255,255,255,0.3);
    backdrop-filter: blur(12px);

    border-radius: 12px;

    display: flex;
    justify-content: space-between;
}

/* ===== CARDS ===== */
.cards {
    display: flex;
    gap: 20px;
    padding: 20px;
    flex-wrap: wrap;
}

.card {
    width: 240px;
    padding: 20px;
    border-radius: 14px;

    background: rgba(255,255,255,0.3);
    backdrop-filter: blur(12px);

    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    font-size: 14px;
    margin: 0;
    color: #444;
}

.card p {
    font-size: 22px;
    font-weight: bold;
}

/* ===== SECTION ===== */
.section {
    padding: 0 25px;
}

.section h2 {
    font-size: 20px;
}

/* ===== STATUS BOX ===== */
.status-box {
    margin: 25px;
    padding: 20px;
    border-radius: 14px;

    background: rgba(255,255,255,0.3);
    backdrop-filter: blur(12px);

    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* STATUS TEXT */
.status-ok {
    color: #1e7e34;
    font-weight: bold;
}

.status-wait {
    color: #b8860b;
    font-weight: bold;
}

</style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Donor Panel</h2>

        <a href="donor.php">Dashboard</a>
        <a href="donation_history.php">Donation History</a>
        <a href="eligibility.php">Eligibility</a>
        <a href="../logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOP BAR -->
        <div class="topbar">
            <div>Welcome, <?php echo $name; ?></div>
            <div>Donor Dashboard</div>
        </div>

        <!-- CARDS -->
        <div class="cards">

            <div class="card">
                <h3>Total Donations</h3>
                <p>
                    <?php
                    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM donations WHERE donor_name='$name'");
                    $r = mysqli_fetch_assoc($q);
                    echo $r['total'];
                    ?>
                </p>
            </div>

            <div class="card">
                <h3>Last Donation</h3>
                <p>
                    <?php
                    $q = mysqli_query($conn, "SELECT MAX(donation_date) as last_date FROM donations WHERE donor_name='$name'");
                    $r = mysqli_fetch_assoc($q);
                    echo $r['last_date'] ? $r['last_date'] : "N/A";
                    ?>
                </p>
            </div>

        </div>

        <!-- ELIGIBILITY -->
        <div class="status-box">

            <h2>Eligibility Status</h2>

            <?php
            $q = mysqli_query($conn, "SELECT MAX(donation_date) as last_date FROM donations WHERE donor_name='$name'");
            $r = mysqli_fetch_assoc($q);

            if ($r['last_date']) {

                $today = new DateTime();
                $last = new DateTime($r['last_date']);
                $diff = $today->diff($last)->days;

                if ($diff >= 90) {
                    echo "<p class='status-ok'>You are eligible to donate</p>";
                } else {
                    echo "<p class='status-wait'>Not eligible yet. Wait ".(90-$diff)." days</p>";
                }

            } else {
                echo "<p class='status-ok'>You are eligible (first-time donor)</p>";
            }
            ?>

        </div>

        <!-- INFO -->
        <div class="section">
            <h2>Donor Information</h2>
            <p>
                Track your donation history, eligibility, and contribution to save lives.
            </p>
        </div>

    </div>

</div>

</body>
</html>