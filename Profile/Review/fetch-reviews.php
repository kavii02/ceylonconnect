<?php
// fetch-review.php
include 'db.php';

$reviewed_id = 'teacher456';  // Or dynamically from GET param

// Fetch total reviews and average rating
$sql = "SELECT COUNT(*) AS total_reviews, ROUND(AVG(rating_stars),1) AS avg_rating
        FROM review WHERE reviewed_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $reviewed_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$total_reviews = $result['total_reviews'] ?? 0;
$avg_rating = $result['avg_rating'] ?? 0;

// Star breakdown
$breakdown = [5=>0,4=>0,3=>0,2=>0,1=>0];
$sql2 = "SELECT rating_stars, COUNT(*) as count FROM review WHERE reviewed_id = ? GROUP BY rating_stars";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $reviewed_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
  $breakdown[$row['rating_stars']] = $row['count'];
}

// Fetch all reviews
$sql3 = "SELECT rating_stars, review_text, review_date FROM review WHERE reviewed_id = ? ORDER BY review_date DESC";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("s", $reviewed_id);
$stmt3->execute();
$res3 = $stmt3->get_result();

$reviews = [];
while ($row = $res3->fetch_assoc()) {
  $reviews[] = $row;
}
?>
