<?php
session_start();
include("database/connection.php");

// Handle demo login
if (isset($_GET['demo'])) {
    $role = $_GET['demo'];
    
    if ($role == 'admin') {
        $email = 'admin@lifeflow.com';
        $name = 'System Admin';
    } elseif ($role == 'hospital') {
        $email = 'hospital@citygen.com';
        $name = 'City General Hospital';
    } elseif ($role == 'donor') {
        $email = 'john@example.com';
        $name = 'John Doe';
    } else {
        $email = 'jane@example.com';
        $name = 'Jane Smith';
    }
    
    // Get user from database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] == 'admin') header("Location: dashboard/admin.php");
        elseif ($user['role'] == 'hospital') header("Location: dashboard/hospital.php");
        else header("Location: dashboard/donor.php");
        exit();
    }
}

// Handle normal login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] == 'admin') header("Location: dashboard/admin.php");
        elseif ($user['role'] == 'hospital') header("Location: dashboard/hospital.php");
        else header("Location: dashboard/donor.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LifeFlow Blood Bank</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0a0a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .bg-animation .circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(199, 54, 43, 0.3), transparent);
            animation: float 20s infinite;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 500px;
            height: 500px;
            bottom: -200px;
            right: -200px;
            animation-delay: 5s;
        }

        .circle-3 {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 50%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-50px) scale(1.1); }
        }

        /* Login Container */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 45px 35px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo i {
            font-size: 3rem;
            color: #c7362b;
        }

        .logo h1 {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-top: 10px;
        }

        .logo p {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #c7362b;
        }

        .input-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            transition: 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #c7362b;
            background: rgba(255, 255, 255, 0.15);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #c7362b, #a1241a);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(199, 54, 43, 0.4);
        }

        /* Demo Buttons Section */
        .demo-section {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .demo-title {
            text-align: center;
            color: #b0b0b0;
            font-size: 0.85rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .demo-title::before,
        .demo-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .demo-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .demo-btn {
            padding: 12px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .demo-admin {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.2), rgba(231, 76, 60, 0.1));
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        .demo-admin:hover {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            transform: translateY(-2px);
        }

        .demo-hospital {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(52, 152, 219, 0.1));
            border: 1px solid #3498db;
            color: #3498db;
        }

        .demo-hospital:hover {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            transform: translateY(-2px);
        }

        .demo-donor {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.2), rgba(46, 204, 113, 0.1));
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        .demo-donor:hover {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #b0b0b0;
        }

        .register-link a {
            color: #c7362b;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
            text-align: center;
        }

        .info-note {
            background: rgba(52, 152, 219, 0.1);
            border: 1px solid #3498db;
            color: #3498db;
            padding: 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            text-align: center;
            margin-top: 20px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            .demo-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="bg-animation">
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>
</div>

<div class="login-container">
    <div class="login-card">
        <div class="logo">
            <i class="fas fa-droplet"></i>
            <h1>LifeFlow</h1>
            <p>Blood Bank Management System</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Normal Login Form -->
        <form method="POST">
            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <!-- Demo Login Section -->
        <div class="demo-section">
            <div class="demo-title">
                <span>Quick Demo Access</span>
            </div>
            <div class="demo-buttons">
                <button onclick="demoLogin('admin')" class="demo-btn demo-admin">
                    <i class="fas fa-user-shield"></i> Admin Demo
                </button>
                <button onclick="demoLogin('hospital')" class="demo-btn demo-hospital">
                    <i class="fas fa-hospital"></i> Hospital Demo
                </button>
                <button onclick="demoLogin('donor')" class="demo-btn demo-donor">
                    <i class="fas fa-hand-holding-heart"></i> Donor Demo
                </button>
            </div>
            <div class="info-note">
                <i class="fas fa-info-circle"></i> Demo accounts: One-click login to test all features!
            </div>
        </div>

        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</div>

<script>
    function demoLogin(role) {
        // Show loading effect
        const btns = document.querySelectorAll('.demo-btn');
        btns.forEach(btn => {
            btn.style.opacity = '0.5';
            btn.style.cursor = 'wait';
        });
        
        // Redirect to login with demo parameter
        window.location.href = `?demo=${role}`;
    }
</script>
</body>
</html>