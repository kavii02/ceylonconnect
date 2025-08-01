<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>

<header class="top-bar">
    <div class="logo">
        <img src="/ceylonconnect/assets/images/logo.png" alt="CeylonConnect Logo" class="logo-img">
        <span>CeylonConnect</span>
    </div>

    <link rel="stylesheet" href="/ceylonconnect/assets/css/header.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <nav class="nav-buttons">
        <a href="/ceylonconnect/index.php">Home</a>
        <a href="/ceylonconnect/aboutus.html">About Us</a>
        <a href="/ceylonconnect/Contact_us/Contact_us.php">Contact Us</a>

        <?php if (!$isLoggedIn): ?>
            <button class="back-btn" onclick="window.location.href='/ceylonconnect/Login/Login1.html'">Sign In</button>
            <button class="back-btn" onclick="window.location.href='/ceylonconnect/Registration/registration.html'">Sign Up</button>
        <?php else: ?>
            <a href="/ceylonconnect/profile.php">Profile</a>
            <button class="back-btn" onclick="window.location.href='/ceylonconnect/logout.php'">Sign Out</button>
        <?php endif; ?>
    </nav>
</header>
