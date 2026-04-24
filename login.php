<?php
session_start();
include("database/connection.php");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'donor') {
                header("Location: dashboard/donor.php");
            } elseif ($user['role'] == 'hospital') {
                header("Location: dashboard/hospital.php");
            } else {
                header("Location: dashboard/admin.php");
            }
            exit();

        } else {
            $error = "Invalid credentials";
        }

    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>

/* 🌈 BACKGROUND */
body {
    margin: 0;
    height: 100vh;
    font-family: Inter, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;

    background: linear-gradient(135deg, #f4f6f9, #ffecec, #ffffff);
}

/* floating blobs */
body::before,
body::after {
    content: "";
    position: absolute;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: rgba(176, 42, 42, 0.15);
    filter: blur(80px);
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
    animation-delay: 4s;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(50px); }
    100% { transform: translateY(0px); }
}

/* WRAPPER */
.wrapper {
    display: flex;
    gap: 25px;
    z-index: 2;
}

/* 🧊 GLASS LOGIN BOX */
.box {
    width: 360px;
    padding: 30px;
    border-radius: 16px;

    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #222;
}

/* INPUT */
input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: none;
    border-radius: 8px;
    outline: none;

    background: rgba(255,255,255,0.6);
}

/* BUTTON */
button {
    width: 100%;
    padding: 10px;
    background: #b02a2a;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

/* ERROR */
.error {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}

/* DEMO PANEL */
.demo {
    width: 260px;
}

.title {
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.demo-card {
    background: rgba(255,255,255,0.35);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);

    padding: 15px;
    margin-bottom: 12px;
    border-radius: 12px;
    cursor: pointer;
    transition: 0.3s;
}

.demo-card:hover {
    transform: translateY(-5px);
}

.demo-card h4 {
    margin: 0;
    font-size: 14px;
}

.demo-card p {
    margin: 5px 0 0;
    font-size: 12px;
    color: #666;
}

/* SIGNUP */
.signup {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
    color: #444;
}

.signup a {
    display: inline-block;
    margin-top: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    border: 1px solid #b02a2a;
    color: #b02a2a;
    text-decoration: none;
    transition: 0.3s;
}

.signup a:hover {
    background: #b02a2a;
    color: white;
}

</style>
</head>

<body>

<div class="wrapper">

    <!-- LOGIN BOX -->
    <div class="box">

        <h2>Login</h2>

        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

        <form method="POST">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <div class="signup">
            Don’t have an account?<br>
            <a href="register.php">Create Account</a>
        </div>

    </div>

    <!-- DEMO PANEL -->
    <div class="demo">

        <div class="title">Quick Demo Login</div>

        <div class="demo-card" onclick="fillLogin('admin@test.com','123456')">
            <h4>Admin Login</h4>
            <p>admin@test.com</p>
        </div>

        <div class="demo-card" onclick="fillLogin('hospital@test.com','123456')">
            <h4>Hospital Login</h4>
            <p>hospital@test.com</p>
        </div>

        <div class="demo-card" onclick="fillLogin('donor@test.com','123456')">
            <h4>Donor Login</h4>
            <p>donor@test.com</p>
        </div>

    </div>

</div>

<script>
function fillLogin(email, password) {
    document.getElementById("email").value = email;
    document.getElementById("password").value = password;
}
</script>

</body>
</html>