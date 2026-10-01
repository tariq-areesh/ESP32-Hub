<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
// Logout script
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = [];
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>
