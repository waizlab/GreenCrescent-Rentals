<?php
// Database connection for GreenCrescent Rentals
$servername = "localhost";   // MySQL server
$username   = "root";        // MySQL username
$password   = "";            // MySQL password (default XAMPP is blank)
$dbname     = "GreenCrescent_Rentals"; // Database name
$port       = "3306";          // MySQL port 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: Set charset
$conn->set_charset("utf8");

?>
