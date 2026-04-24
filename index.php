<?php
include("database/connection.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>Blood Bank Management System</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&display=swap" rel="stylesheet">

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #f5f7fa, #ffffff);
    overflow-x: hidden;
}

/* BACKGROUND GLOW */
body::before,
body::after {
    content: "";
    position: fixed;
    width: 450px;
    height: 450px;
    border-radius: 50%;
    background: rgba(176, 42, 42, 0.10);
    filter: blur(120px);
    z-index: 0;
}

body::before {
    top: -120px;
    left: -120px;
}

body::after {
    bottom: -120px;
    right: -120px;
}

/* NAVBAR */
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 999;

    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 14px 40px;

    background: rgba(255,255,255,0.55);
    backdrop-filter: blur(14px);

    border-bottom: 1px solid rgba(0,0,0,0.06);
}

.navbar b {
    color: #b02a2a;
    font-size: 16px;
}

.navbar a {
    text-decoration: none;
    margin-left: 18px;
    color: #222;
    font-weight: 500;
}

.navbar a:hover {
    color: #b02a2a;
}

/* HERO */
.hero {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.hero-card {
    width: 100%;
    max-width: 750px;

    padding: 45px;
    border-radius: 20px;

    background: rgba(255,255,255,0.40);
    backdrop-filter: blur(20px);

    box-shadow: 0 25px 50px rgba(0,0,0,0.08);

    text-align: center;
}

.hero-card h1 {
    font-size: 40px;
    margin-bottom: 12px;
    color: #111;
}

.hero-card p {
    font-size: 15px;
    color: #444;
    line-height: 1.6;
}

/* BUTTONS */
.btn-group {
    margin-top: 22px;
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
}

.btn {
    display: inline-block;
    padding: 12px 22px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    min-width: 140px;
    text-align: center;
    transition: 0.3s;
}

.btn-primary {
    background: #b02a2a;
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
}

.btn-outline {
    border: 2px solid #b02a2a;
    color: #b02a2a;
    background: transparent;
}

.btn-outline:hover {
    background: #b02a2a;
    color: white;
}

/* FEATURES */
.features {
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 70px 20px;
    flex-wrap: wrap;
}

.card {
    width: 260px;
    padding: 22px;
    border-radius: 14px;

    background: rgba(255,255,255,0.45);
    backdrop-filter: blur(14px);

    box-shadow: 0 12px 25px rgba(0,0,0,0.06);
}

.card h3 {
    margin-bottom: 10px;
    color: #111;
}

.card p {
    font-size: 14px;
    color: #555;
    line-height: 1.5;
}

/* STOCK SECTION */
.stock {
    padding: 60px 20px;
    display: flex;
    justify-content: center;
}

.stock-box {
    width: 100%;
    max-width: 900px;

    padding: 25px;
    border-radius: 18px;

    background: rgba(255,255,255,0.45);
    backdrop-filter: blur(18px);

    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.stock-box h2 {
    margin-bottom: 18px;
    color: #111;
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
}

th {
    background: #111;
    color: white;
    padding: 10px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

/* CTA */
.cta {
    text-align: center;
    margin-top: 20px;
}

.cta a {
    display: inline-block;
    padding: 12px 20px;
    background: #b02a2a;
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
}

/* FOOTER */
.footer {
    text-align: center;
    padding: 18px;
    font-size: 13px;
    background: #111;
    color: #ccc;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div><b>Blood Bank System</b></div>
    <div>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">

    <div class="hero-card">

        <h1>Blood Bank Management System</h1>

        <p>
            A centralized healthcare platform for managing donors, hospital requests, and real-time blood inventory tracking.
        </p>

        <div class="btn-group">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-outline">Register</a>
        </div>

    </div>

</div>

<!-- FEATURES -->
<div class="features">

    <div class="card">
        <h3>Donor System</h3>
        <p>Track donation history and eligibility with automated medical rules.</p>
    </div>

    <div class="card">
        <h3>Hospital Requests</h3>
        <p>Structured request workflow with approval system.</p>
    </div>

    <div class="card">
        <h3>Inventory Control</h3>
        <p>Automatic blood stock updates after approval.</p>
    </div>

    <div class="card">
        <h3>Secure System</h3>
        <p>Role-based authentication for Admin, Hospital, Donor.</p>
    </div>

</div>

<!-- STOCK -->
<div class="stock">

<div class="stock-box">

<h2>Current Blood Availability</h2>

<table>

<tr>
    <th>Blood Group</th>
    <th>Units</th>
    <th>Status</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM blood_stock");

while($row = mysqli_fetch_assoc($result)) {
?>

<tr>
    <td><?php echo $row['blood_group']; ?></td>
    <td><?php echo $row['units_available']; ?></td>
    <td>
        <?php
        $u = $row['units_available'];

        if ($u <= 2) {
            echo "<span style='color:#c0392b;font-weight:bold;'>Critical</span>";
        } elseif ($u <= 5) {
            echo "<span style='color:#b8860b;font-weight:bold;'>Low</span>";
        } else {
            echo "<span style='color:#1e7e34;font-weight:bold;'>Available</span>";
        }
        ?>
    </td>
</tr>

<?php } ?>

</table>

<div class="cta">
    <a href="login.php">Request Blood Now</a>
</div>

</div>

</div>

<!-- FOOTER -->
<div class="footer">
    Blood Bank Management System © 2026
</div>

</body>
</html>