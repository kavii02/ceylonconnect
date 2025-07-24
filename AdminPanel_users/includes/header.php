<?php
// This line should be at the very top of your PHP file, before any HTML output
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>

<header class="top-bar">
    <div class="logo">
        <img src="assets/images/logo.png" alt="CeylonConnect Logo" class="logo-img">
        <span>CeylonConnect</span>
    </div>
    <link rel="stylesheet" href="assets/css/header.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <nav class="nav-buttons">
        <a href="/CeylonConnect/Home/Home.php">Home</a>
        <a href=" ">About Us</a>
        <a href="/CeylonConnect/Contact_us/Contact_us.php">Contact Us</a>


            <a href="/cst22056C/AdminPanel_dashboard/Dashboard.html">Users</a>
            <button class="back-btn" onclick="window.location.href='logout.php'">Sign Out</button>

    </nav>
</header>