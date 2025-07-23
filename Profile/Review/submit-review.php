

<?php
// Include the database connection file
require 'db.php';

// Get form values
$review_title = $_POST['title'];
$review_description = $_POST['description'];
$rating = $_POST['rating'];

// Dummy placeholders for required fields
$reviewer_id = "student123";
$reviewed_id = "teacher456";
$request_id = "req789";

// Prepare and execute SQL statement
$sql = "INSERT INTO review (reviewer_id, reviewed_id, request_id, rating_stars, review_text)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssds", $reviewer_id, $reviewed_id, $request_id, $rating, $review_description);

if ($stmt->execute()) {
    header("Location: success.html");
    echo "<a href='add-review.html'>Go back</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
