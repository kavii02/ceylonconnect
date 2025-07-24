<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/Home.css">
</head>
<body>
<header>
    <div class="header">
        <?php
        // Always start session if you plan to use it later (e.g., for login status)
        // If includes/header.php also calls session_start(), you might get a notice.
        // The best practice is to call session_start() only once at the very top of the main script.
        // For this public-facing home page, it might not be strictly necessary if header.php doesn't rely on it.
        // session_start();
        include('includes/header.php');
        ?>
    </div>

    <div class="header-container-home">
        <div class="header-text">
            <h1 class="header-title">A Barter-Based Platform </h1>
            <h1 class="header-title-main">for Sharing ICT </h1>
            <h1 class="header-title">Skills and Goods</h1>
            <button class="landing-page-button" onclick="window.location.href='Login/Login1.php'">Get Started</button>
            </div>
        <div>
            <img class="header-image" src="assets/images/Homepage_img.png" alt="">
        </div>
    </div>

    <div class="header-container-points">

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/searching.png" alt="">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Browse Listings</h1>
                <p class="header-browser-subtitle">Search for skills and goods</p>
            </div>
        </div>

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/Browsing.png" alt="">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Post Listings</h1>
                <p class="header-browser-subtitle">Share what you offer</p>
            </div>
        </div>

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/exchanges img.png" alt="">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Want Items</h1> <p class="header-browser-subtitle">Connect with other users</p>
            </div>
        </div>

        <div class="header-container-browse" onclick="window.location.href=''">
            <div>
                <img class="header-browser-image" src="assets/images/ratings.png" alt="">
            </div>
            <div class="header-container-details-browse">
                <h1 class="header-browser-title">Leave Ratings</h1>
                <p class="header-browser-subtitle">Rate your exchange experiences</p>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</header>
</body>
</html>
