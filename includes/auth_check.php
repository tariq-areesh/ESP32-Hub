<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426

// Authentication check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Redirect to login if not authenticated
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
