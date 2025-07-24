<?php
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
        <a href="/ceylonconnect/Home_AfterLogIn/HomeAfter.php">Home</a>
        <a href="/ceylonconnect/aboutus.html">About Us</a>
        <a href="/ceylonconnect/Contact_us/Contact_us.php">Contact Us</a>

            <a href="/ceylonconnect/Profile/AllPost/profile.html">Profile</a>
            <button class="back-btn" onclick="confirmLogout()">Sign Out</button>
      
    </nav>
</header>

<script>
    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            alert("You have logged out.");
            window.location.href = "/ceylonconnect/index.php?logout=true";
        }
    }
</script>
