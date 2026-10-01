<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ESP32 Hub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/webp" href="/images/esp32.webp">
  <link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/print.css" media="print">
</head>

<body>
  <!-- header -->
  <header id="site-header">
    <div class="container clearfix">

      <!-- logo: same size/position as Assignment 1 -->
      <a href="index.php" class="logo-link">
        <img src="../images/esp32.webp"
             alt="ESP32 logo"
             width="40"
             class="float-left"
             style="margin-top:4px;border-radius:6px">
      </a>

      <!-- navigation -->
      <nav class="nav">
        <a href="index.php">Home</a>
        <a href="projects.php">Projects Table</a>
        <a href="gallery.php">Gallery</a>
        <a href="videos.php">Tutorial Videos</a>
        <a href="help_list.php">Help Requests</a>
        <a href="feedback.php">Feedback</a>
        <a href="resume.php">Resume</a>
        <?php if (isset($_SESSION["user_id"])): ?>
          <a href="dashboard.php">Dashboard</a>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="login.php">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- main content -->
  <main class="container">