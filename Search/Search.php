<?php
$selectedCategory = $_GET['category'] ?? 'all';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/Search.css">
    <title>Home</title>
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
                        <img class="header-browser-image" src="assets/images/searching.png" alt="">
                        <div class="header-container-details-browse">
                            <h1 class="header-browser-title">Browse Listings</h1>
                            <p class="header-browser-subtitle">Search for skills and goods</p>
                        </div>
                    </button>
                </div>

                <div class="header-container-browse">
                    <img class="header-browser-image" src="assets/images/Browsing.png" alt="">
                    <div class="header-container-details-browse">
                        <h1 class="header-browser-title">Post Listings</h1>
                        <p class="header-browser-subtitle">Share what you offer</p>
                    </div>
                </div>

                <div class="header-container-browse">
                    <img class="header-browser-image" src="assets/images/exchanges img.png" alt="">
                    <div class="header-container-details-browse">
                        <h1 class="header-browser-title">Make Exchanges</h1>
                        <p class="header-browser-subtitle">Connect with other users</p>
                    </div>
                </div>

                <div class="header-container-browse">
                    <img class="header-browser-image" src="assets/images/ratings.png" alt="">
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
    <div class="search-section">
        <h2>Search Items</h2>
        <input type="text" id="goods-search-bar" class="search-bar" placeholder="Search for items..." />
        <select class="item-category-dropdown" id="goods">
            <option disabled selected>Select an item category</option>
            <option value="Electronics">Electronics</option>
            <option value="Books">Books</option>
            <option value="Sports">Sports</option>
            <option value="Music">Music</option>
        </select>
    </div>

    <div class="search-section">
        <h2>Search Skills</h2>
        <form method="GET" action="browsepg.php">
            <input type="text" id="skills-search-bar" class="search-bar" placeholder="Search for skills..." />
        </form>
        <select class="skill-category-dropdown" id="skills">
            <option disabled selected>Select a skill category...</option>
            <option value="programming">Programming Languages</option>
            <option value="graphic-design">Graphic Design</option>
            <option value="microsoft-office">Office Package</option>
            <option value="web-development">Web Development</option>
        </select>
    </div>
</div>

<div class="search-btn-container full-width-btn">
    <button class="search-btn" onclick="goToBrowse()">Search</button>
    <button class="search-btn" onclick="clearSelection()">Clear Selection</button>
    <a href="/ceylonconnect/Home_AfterLogIn/HomeAfter.php">
        <a href="javascript:history.back()" button class="search-btn">Back</button></a>
    </a>
</div>

<script>
function goToBrowse() {
    const goodsSearchBar = document.getElementById('goods-search-bar');
    const skillsSearchBar = document.getElementById('skills-search-bar');
    const itemDropdown = document.querySelector('.item-category-dropdown');
    const skillDropdown = document.querySelector('.skill-category-dropdown');

    const goodsQuery = goodsSearchBar.value.trim();
    const skillsQuery = skillsSearchBar.value.trim();
    const itemCategory = itemDropdown.selectedIndex > 0 ? itemDropdown.value : null;
    const skillCategory = skillDropdown.selectedIndex > 0 ? skillDropdown.value : null;

    let url = 'browsepg.php?';

    // Prioritize search bar input
    if (goodsQuery) {
        url += `search_query=${encodeURIComponent(goodsQuery)}&type=goods`;
    } else if (skillsQuery) {
        url += `search_query=${encodeURIComponent(skillsQuery)}&type=skills`;
    } 
    // Fallback to category selection
    else if (itemCategory) {
        url += `type=goods&category=${encodeURIComponent(itemCategory)}`;
    } else if (skillCategory) {
        url += `type=skills&category=${encodeURIComponent(skillCategory)}`;
    } else {
        alert("Please enter a search term or select a category before searching.");
        return;
    }

    // Prevent both goods and skills searches from happening at the same time
    if ((goodsQuery && skillsQuery) || (itemCategory && skillCategory) || (goodsQuery && skillCategory) || (skillsQuery && itemCategory)) {
        alert("Please search for either a good or a skill, not both.");
        return;
    }

    window.location.href = url;
}

function clearSelection() {
    document.getElementById('goods-search-bar').value = '';
    document.getElementById('skills-search-bar').value = '';
    document.querySelector('.item-category-dropdown').selectedIndex = 0;
    document.querySelector('.skill-category-dropdown').selectedIndex = 0;
}
</script>

</body>
</html>
