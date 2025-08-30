<?php
$servername = "localhost";
$username = "apiticke_apitickets";   // Default for XAMPP
$password = "apitickets@123";       // Default for XAMPP
$dbname = "apiticke_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
date_default_timezone_set('Asia/Kathmandu');


// Check connection
if ($conn->connect_error) {
    // Handle the error more securely (avoid leaking sensitive info)
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to utf8mb4 for better encoding support
$conn->set_charset("utf8mb4");

?>
