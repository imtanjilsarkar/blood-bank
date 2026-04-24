<?php
include("database/connection.php");

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $error = "Email already exists";
    } else {

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$password', '$role')";

        if (mysqli_query($conn, $sql)) {
            $success = "Account created successfully";
        } else {
            $error = "Registration failed";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <style>
        body {
            margin: 0;
            font-family: Inter, sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            width: 380px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #b02a2a;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #555;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="box">

    <h2>Create Account</h2>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <select name="role">
            <option value="donor">Donor</option>
            <option value="hospital">Hospital</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="register">Create Account</button>

    </form>

    <a href="login.php">Already have an account?</a>

</div>

</body>
</html>