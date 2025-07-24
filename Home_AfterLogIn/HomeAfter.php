<?php
// Start the session at the very beginning of the script
// This is crucial to access $_SESSION variables
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/HomeAfter.css">
</head>
<body>
<header>
    <div class="header">
        <?php include('includes/header.php'); ?>
    </div>

    <div class="header-container-home">
        <div class="header-text">
            <?php
            // Check if the 'username' session variable is set and not empty
            if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                // Display the actual username
                echo '<h1 class="header-welcome-title">Welcome, ' . htmlspecialchars($_SESSION['username']) . '!</h1>';
            } else {
                // Fallback for when no user is logged in
                echo '<h1 class="header-welcome-title">Welcome, User!</h1>';
            }
            ?>
            <h1 class="header-title">A Barter-Based Platform </h1>
            <h1 class="header-title-main">for Sharing ICT </h1>
            <h1 class="header-title">Skills and Goods</h1>
            <div class="header-welcome-button-container">
<button class="landing-page-button" onclick="window.location.href='/ceylonconnect/browse/notifications.html'">
  Check Your Messages
</button>
                <button class="landing-page-button">View Your Reviews</button>
            </div>
        </div>
        <div>
            <img class="header-image" src="assets/images/Homepage_img.png" alt="">
        </div>
    </div>

    <div class="header-container-points">
        <div class="header-container-browse">
            <button onClick="window.location.href='/ceylonconnect/browse/browse.html'" class='browse-button'>
                <div>
                    <img class="header-browser-image" src="assets/images/Browsing.png" alt="">
                </div>
                <div class="header-container-details-browse">
                    <h1 class="header-browser-title">Browse Listings</h1>
                    <p class="header-browser-subtitle">Browse skills and goods</p>
                </div>
            </button>
        </div>

        <div class="header-container-browse">
            <button onClick="window.location.href='/ceylonconnect/Search/Search.php'" class='browse-button'>
                <div>
                    <img class="header-browser-image" src="assets/images/searching.png" alt="">
                </div>
                <div class="header-container-details-browse">
                    <h1 class="header-browser-title">Search Listings</h1>
                    <p class="header-browser-subtitle">Search for skills and goods</p>
                </div>
            </button>
        </div>

        <div class="header-container-browse">
            <button onClick="window.location.href='Required Items/Required_items.php'" class='browse-button'>
                <div>
                    <img class="header-browser-image" src="assets/images/exchanges img.png" alt="">
                </div>
                <div class="header-container-details-browse">
                    <h1 class="header-browser-title">Want Items</h1>
                    <p class="header-browser-subtitle">Connect with other users</p>
                </div>
            </button>
        </div>

        <div class="header-container-browse">
            <button onClick="window.location.href='Ratings/Ratings.php'" class='browse-button'>
                <div>
                    <img class="header-browser-image" src="assets/images/ratings.png" alt="">
                </div>
                <div class="header-container-details-browse">
                    <h1 class="header-browser-title">Your Feedbacks</h1>
                    <p class="header-browser-subtitle">For our future use</p>
                </div>
            </button>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</header>
</body>
</html>
