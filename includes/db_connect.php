<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
// Database connection settings 
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "esp32_hub";

// Create a new MySQLi connection
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
?>
