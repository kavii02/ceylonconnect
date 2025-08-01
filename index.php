<?php
session_start();

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/Home.css">
    <title>CeylonConnect | Home</title>
</head>
<body>
<header>
    <div class="header">
        <?php include('includes/header.php'); ?>
    </div>

    <div class="header-container-home">
        <div class="header-text">
            <h1 class="header-title">A Barter-Based Platform</h1>
            <h1 class="header-title-main">for Sharing ICT</h1>
            <h1 class="header-title">Skills and Goods</h1>
            <button class="landing-page-button" onclick="window.location.href='/ceylonconnect/Registration/registration.html'">Get Started</button>
        </div>
        <div>
            <img class="header-image" src="assets/images/Homepage_img.png" alt="Homepage Image">
        </div>
    </div>

    <div class="header-container-points">

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/searching.png" alt="Search Icon">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Browse Listings</h1>
                <p class="header-browser-subtitle">Search for skills and goods</p>
            </div>
        </div>

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/Browsing.png" alt="Post Icon">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Post Listings</h1>
                <p class="header-browser-subtitle">Share what you offer</p>
            </div>
        </div>

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/exchanges img.png" alt="Exchange Icon">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Want Items</h1>
                <p class="header-browser-subtitle">Connect with other users</p>
            </div>
        </div>

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/ratings.png" alt="Ratings Icon">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Your Feedbacks</h1>
                    <p class="header-browser-subtitle">For our future use</p>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</header>
</body>
</html>
