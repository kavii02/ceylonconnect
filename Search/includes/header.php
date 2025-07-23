<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ceylon Connect</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<?php
session_start();
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>

<div class="header-container">
    <div class="logo-title">
        <img src="assets/images/logo.png" alt="Ceylon Connect Logo" class="logo-img">
        <h1>CeylonConnect</h1>
    </div>

    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>

            <?php if (!$isLoggedIn): ?>
                <!-- Links for NOT logged-in users -->
                <li><button class="header-button" onclick="window.location.href='signin.php'">Sign In</button></li>
                <li><button class="header-button" onclick="window.location.href='signup.php'">Sign Up</button></li>
            <?php else: ?>
                <!-- Links for logged-in users -->
                <li><a href="profile.php">Profile</a></li>
                <li><button class="header-button" onclick="window.location.href='logout.php'">Sign Out</button></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
</body>
</html>