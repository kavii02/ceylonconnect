<?php
include 'db.php';

$reviewed_id = 'teacher456'; // Replace with dynamic value later if needed

// Fetch total reviews and average
$sql = "SELECT COUNT(*) AS total_reviews, ROUND(AVG(rating_stars),1) AS avg_rating 
        FROM review WHERE reviewed_id = '$reviewed_id'";
$result = $conn->query($sql)->fetch_assoc();
$total_reviews = $result['total_reviews'] ?? 0;
$avg_rating = $result['avg_rating'] ?? 0;

// Star breakdown
$breakdown = [5=>0,4=>0,3=>0,2=>0,1=>0];
$sql2 = "SELECT rating_stars, COUNT(*) as count FROM review 
         WHERE reviewed_id = '$reviewed_id' GROUP BY rating_stars";
$res2 = $conn->query($sql2);
while ($row = $res2->fetch_assoc()) {
  $breakdown[$row['rating_stars']] = $row['count'];
}

// Fetch all reviews
$sql3 = "SELECT rating_stars, review_text, review_date FROM review 
         WHERE reviewed_id = '$reviewed_id' ORDER BY review_date DESC";
$res3 = $conn->query($sql3);
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Post Details</title>
  <link rel="stylesheet" href="review.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <!-- Toggle Button -->
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="profile">
      <img src="images/p1.jpg" alt="profile">
      <h3>shaikh anas</h3>
      <p>shaikh@gmail.com</p><br>
      <a href="update-profile.html" class="profile-btn">View Profile</a>
    </div>
    <nav class="nav-menu">
      <a href="#"><i class="fas fa-home"></i> home</a>
      <a href="#"><i class="fas fa-info-circle"></i> about us</a>
      <a href="#"><i class="fas fa-envelope"></i> contact us</a>
      <!--a href="review.html"><i class="fas fa-star"></i> Reviews & Ratings</a-->
      <a href="#"><i class="fas fa-bell"></i> Notifications</a>
      <a href="#"><i class="fas fa-clock"></i> Time Slots</a>

      <a href="#"><i class="fas fa-chalkboard-teacher"></i> Log Out</a>
      
    </nav>
  </div>
  <!-- Main Content -->
  <div class="main-content">
    <div class="post-detail-container">
      
      <!-- Top Header with "All Posts" button -->
      <div class="detail-header">
        <h2 class="section-title">Post Details</h2>
        <a href="profile.html" class="all-posts-btn">All Posts</a>
      </div>

      <!-- Post Details -->
      <div class="post-detail-card">
        <img src="images/post_2.webp" alt="Post Image">
        <div class="post-info">
          
        <h3>02 example post title</h3>
          <p><strong><?php echo $avg_rating; ?></strong> <i class="fas fa-star"></i> total <?php echo $total_reviews; ?> reviews</p>
          <div class="rating-breakdown">
            <?php foreach (array_reverse($breakdown, true) as $star => $count): ?>
              <p><i class="fas fa-star"></i> <?php echo $star; ?> Stars - <?php echo $count; ?></p>
            <?php endforeach; ?>
          </div>


          <!-- Add Review Button -->
          <a href="add-review.html" class="add-review-btn">Add Review</a>
        </div>
      </div>

      <!-- User's Reviews -->
      <h3 style="margin-top: 30px;">User's Reviews</h3>
      <div class="user-reviews">
        <?php if ($res3->num_rows > 0): ?>
          <?php while ($r = $res3->fetch_assoc()): ?>
            <div class="single-review">
              <p><strong><?php echo $r['rating_stars']; ?> Stars</strong> - <?php echo htmlspecialchars($r['review_text']); ?></p>
              <p style="color:#888; font-size:12px;">Posted on: <?php echo $r['review_date']; ?></p>
              <hr>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="color: #777;">No Reviews Added Yet!</p>
        <?php endif; ?>
      </div>


    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('active');
    }
  </script>

</body>
</html>
