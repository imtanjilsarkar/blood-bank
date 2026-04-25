<?php
// database/connection.php

$host = 'localhost';
$user = 'root';        // XAMPP default username
$pass = '';            // XAMPP default password (empty)
$dbname = 'blood_bank'; // Database name we created

// Create connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// For debugging (remove in production)
// echo "Connected successfully";
?>