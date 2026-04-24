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

/* BACKGROUND BLUR EFFECT */
body::before,
body::after {
    content: "";
    position: fixed;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: rgba(176, 42, 42, 0.12);
    filter: blur(100px);
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

    background: rgba(255,255,255,0.4);
    backdrop-filter: blur(12px);

    border-bottom: 1px solid rgba(0,0,0,0.05);
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

/* GLASS CARD */
.hero-card {
    width: 100%;
    max-width: 720px;

    padding: 40px;
    border-radius: 18px;

    background: rgba(255,255,255,0.35);
    backdrop-filter: blur(18px);

    box-shadow: 0 20px 40px rgba(0,0,0,0.08);

    text-align: center;
}

.hero-card h1 {
    font-size: 38px;
    margin-bottom: 12px;
    color: #111;
}

.hero-card p {
    font-size: 15px;
    color: #444;
    line-height: 1.6;
}

/* BUTTON AREA FIX */
.btn-group {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap; /* IMPORTANT FIX */
}

/* BUTTONS */
.btn {
    display: inline-block;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
    min-width: 140px; /* prevents overflow */
    text-align: center;
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

    background: rgba(255,255,255,0.35);
    backdrop-filter: blur(12px);

    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
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
            A centralized platform for managing donors, hospital blood requests, and inventory tracking with secure role-based access.
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
        <p>Track donation history and eligibility in a structured medical workflow.</p>
    </div>

    <div class="card">
        <h3>Hospital Requests</h3>
        <p>Hospitals can request blood with controlled approval system.</p>
    </div>

    <div class="card">
        <h3>Stock Management</h3>
        <p>Automatic inventory tracking after request approvals.</p>
    </div>

    <div class="card">
        <h3>Secure System</h3>
        <p>Role-based login system for admin, hospital, and donor users.</p>
    </div>

</div>

<!-- FOOTER -->
<div class="footer">
    Blood Bank Management System © 2026
</div>

</body>
</html>