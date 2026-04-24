<?php
session_start();
include("../database/connection.php");

/* SECURITY CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* DASHBOARD DATA */
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE status='pending'"))['c'];
$approved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE status='approved'"))['c'];
$rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests WHERE status='rejected'"))['c'];

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM blood_requests"))['c'];

/* BLOOD STOCK */
$stock = mysqli_query($conn, "SELECT * FROM blood_stock");

$bloodGroups = [];
$units = [];

while ($row = mysqli_fetch_assoc($stock)) {
    $bloodGroups[] = $row['blood_group'];
    $units[] = $row['units_available'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

/* ===== BACKGROUND ===== */
body {
    margin: 0;
    font-family: Inter, sans-serif;
    background: linear-gradient(135deg, #f4f6f9, #ffecec, #ffffff);
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

    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(12px);

    color: white;
    padding-top: 20px;
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
}

.sidebar a:hover {
    background: rgba(176,42,42,0.7);
}

/* ===== MAIN ===== */
.main {
    margin-left: 220px;
    width: calc(100% - 220px);
}

/* ===== TOPBAR ===== */
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
    width: 220px;
    padding: 20px;
    border-radius: 14px;

    background: rgba(255,255,255,0.3);
    backdrop-filter: blur(12px);

    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.card h3 {
    margin: 0;
    font-size: 14px;
}

.card p {
    font-size: 22px;
    font-weight: bold;
}

/* ===== CHART BOX ===== */
.chart-box {
    width: 450px;
    padding: 20px;
    border-radius: 14px;

    background: rgba(255,255,255,0.3);
    backdrop-filter: blur(12px);

    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.section-title {
    padding-left: 25px;
    font-size: 18px;
    margin-top: 10px;
}

.logout {
    color: #ff6b6b !important;
}
</style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Admin Panel</h2>

        <a href="admin.php">Dashboard</a>
        <a href="admin_requests.php">Blood Requests</a>
        <a href="blood_stock.php">Blood Stock</a>
        <a href="../logout.php" class="logout">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOP BAR -->
        <div class="topbar">
            <div>Welcome, <?php echo $_SESSION['name']; ?></div>
            <div>Admin Control Panel</div>
        </div>

        <!-- CARDS -->
        <div class="cards">

            <div class="card">
                <h3>Total Requests</h3>
                <p><?= $total ?></p>
            </div>

            <div class="card">
                <h3>Pending</h3>
                <p><?= $pending ?></p>
            </div>

            <div class="card">
                <h3>Approved</h3>
                <p><?= $approved ?></p>
            </div>

            <div class="card">
                <h3>Rejected</h3>
                <p><?= $rejected ?></p>
            </div>

        </div>

        <div class="section-title">Analytics Overview</div>

        <!-- CHARTS -->
        <div class="cards">

            <div class="chart-box">
                <h3>Request Status</h3>
                <canvas id="requestChart"></canvas>
            </div>

            <div class="chart-box">
                <h3>Blood Stock</h3>
                <canvas id="stockChart"></canvas>
            </div>

        </div>

    </div>

</div>

<script>
// PIE CHART
new Chart(document.getElementById("requestChart"), {
    type: "pie",
    data: {
        labels: ["Pending", "Approved", "Rejected"],
        datasets: [{
            data: [<?= $pending ?>, <?= $approved ?>, <?= $rejected ?>],
            backgroundColor: ["#f39c12", "#2ecc71", "#e74c3c"]
        }]
    }
});

// BAR CHART
new Chart(document.getElementById("stockChart"), {
    type: "bar",
    data: {
        labels: <?= json_encode($bloodGroups) ?>,
        datasets: [{
            label: "Units",
            data: <?= json_encode($units) ?>,
            backgroundColor: "#b02a2a"
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>