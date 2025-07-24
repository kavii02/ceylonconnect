<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/Search.css">
</head>
<body>
    <div class="background-blur"></div>
<header>
    <div class="header">
 <?php include('includes/header.php'); ?>
 
    </div>
   <div class="blur-content">
    <div class="header-container-home">
        <div class="header-text">
            <h1 class="header-welcome-title">Welcome, User!</h1>
            <h1 class="header-title">A Barter-Based Platform </h1>
            <h1 class="header-title-main">for Sharing ICT </h1>
            <h1 class="header-title">Skills and Goods</h1>
            <div class="header-welcome-button-container">
                <button class="landing-page-button">Check Your Messages</button>
                <button class="landing-page-button">View Your Reviews</button>
            </div>
        </div>
        <div>
            <img class="header-image" src="assets/images/Homepage_img.png" alt="">
        </div>
    </div>

    <div class="header-container-points">

    <div class="header-container-browse">
        <button onClick="window.location.href='search.php'" class='browse-button'>
        <div>
            <img class="header-browser-image" src="assets/images/searching.png" alt="">
        </div>
        <div class="header-container-details-browse">
            <h1 class="header-browser-title">Browse Listings</h1>
        <p class="header-browser-subtitle">Search for skills and goods</p>
        </div>
        </button>
    </div>


    <div class="header-container-browse">
        <div>
            <img class="header-browser-image" src="assets/images/Browsing.png" alt="">
        </div>
        <div class="header-container-details-browse">
            <h1 class="header-browser-title">Post Listings</h1>
        <p class="header-browser-subtitle">Share what you offer</p>
        </div>
    </div>


    <div class="header-container-browse">
        <div>
            <img class="header-browser-image" src="assets/images/exchanges img.png" alt="">
        </div>
        <div class="header-container-details-browse">
            <h1 class="header-browser-title">Make Exchanges</h1>
        <p class="header-browser-subtitle">Connect with other users</p>
        </div>
    </div>

    <div class="header-container-browse">
        <div>
            <img class="header-browser-image" src="assets/images/ratings.png" alt="">
        </div>
        <div class="header-container-details-browse">
            <h1 class="header-browser-title">Leave Ratings</h1>
        <p class="header-browser-subtitle">Rate your exchange experiences</p>
        </div>
    </div>
    </div>
    </div>

    <!-- Search Overlay -->
    <div class="search-overlay">
  <div class="search-section-container">
    <!-- Items Column -->
    <div class="search-section">
      <h2>Search Items</h2>
      <input type="text" class="search-bar" placeholder="Search for items..." />
      <select class="category-dropdown">
        <option disabled selected>Select an item category</option>
        <option value="electronics">Electronics</option>
        <option value="books">Books</option>
        <option value="sports">Sports</option>
        <option value="music">Music</option>
      </select>
    </div>

    <!-- Skills Column -->
    <div class="search-section">
      <h2>Search Skills</h2>
      <input type="text" class="search-bar" placeholder="Search for skills..." />
      <select class="category-dropdown">
        <option disabled selected>Select a skill category...</option>
        <option value="programming_languages">Programming Languages</option>
        <option value="graphic_design">Graphic Design</option>
        <option value="office_package">Office Package</option>
      </select>
    </div>
  </div>
  <div class="search-btn-container full-width-btn">
    <a href="/ceylonconnect/Home_AfterLogIn/HomeAfter.php" class="search-btn">Back</a>
  <button class="search-btn">Search</button>
</div>
</div>



    <?php include('includes/footer.php'); ?>
</header>  
</body>
</html>
