<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Hospital Dashboard</title>

<style>

/* ===== BACKGROUND ===== */
body {
    margin: 0;
    font-family: Inter, sans-serif;

    background: linear-gradient(135deg, #f4f6f9, #ffecec, #ffffff);
    min-height: 100vh;
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
    align-items: center;
}

/* ===== HOME BUTTON ===== */
.home-btn {
    background: #111;
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
}

/* ===== ACTION BUTTON ===== */
.action-btn {
    margin: 20px;
}

.btn {
    padding: 12px 18px;
    background: #b02a2a;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
}

.btn:hover {
    opacity: 0.9;
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
    <a href="../index.php">🏠 Home</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOP BAR -->
    <div class="topbar">
        <div>Welcome, <?php echo $_SESSION['name']; ?></div>

        <div style="display:flex; gap:10px; align-items:center;">
            <div>Hospital Dashboard</div>
            <a href="../index.php" class="home-btn">Home</a>
        </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="action-btn">
        <a href="request_blood.php" class="btn">Create Blood Request</a>
    </div>

    <!-- CARDS -->
    <div class="cards">

        <div class="card">
            <h3>Total Requests</h3>
            <p>
                <?php
                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM blood_requests WHERE hospital_name='{$_SESSION['name']}'");
                $r = mysqli_fetch_assoc($q);
                echo $r['total'];
                ?>
            </p>
        </div>

        <div class="card">
            <h3>Pending Requests</h3>
            <p>
                <?php
                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM blood_requests WHERE hospital_name='{$_SESSION['name']}' AND status='pending'");
                $r = mysqli_fetch_assoc($q);
                echo $r['total'];
                ?>
            </p>
        </div>

        <div class="card">
            <h3>Approved</h3>
            <p>
                <?php
                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM blood_requests WHERE hospital_name='{$_SESSION['name']}' AND status='approved'");
                $r = mysqli_fetch_assoc($q);
                echo $r['total'];
                ?>
            </p>
        </div>

        <div class="card">
            <h3>Rejected</h3>
            <p>
                <?php
                $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM blood_requests WHERE hospital_name='{$_SESSION['name']}' AND status='rejected'");
                $r = mysqli_fetch_assoc($q);
                echo $r['total'];
                ?>
            </p>
        </div>

    </div>

    <!-- INFO -->
    <div class="section">
        <h2>Hospital Operations</h2>
        <p>
            Manage blood requests and track approval status in real-time.
        </p>
    </div>

</div>

</div>

</body>
</html>