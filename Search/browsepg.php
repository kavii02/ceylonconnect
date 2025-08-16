<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylonconnect";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize search parameters from URL
$search_query = trim($_GET['search_query'] ?? '');
$selectedType = $_GET['type'] ?? 'all';
$selectedCategory = $_GET['category'] ?? 'all';

$results = [];
$sql = "";
$stmt = null;

// Use prepared statements for all queries to prevent SQL injection
if (!empty($search_query)) {
    $like_query = "%" . $search_query . "%";
    
    // Combined query to search both tables
    $sql = "
        (SELECT 'goods' as post_type, item_id as id, item_name as title, category, `condition`as item_condition, description, photo, user_id, NULL as level FROM post_goods WHERE item_name LIKE ? OR description LIKE ? OR category LIKE ?)
        UNION ALL
        (SELECT 'skills' as post_type, skill_id as id, skill_title as title, category, NULL as item_condition, description, NULL as photo, user_id, level FROM post_skills WHERE skill_title LIKE ? OR description LIKE ? OR category LIKE ?)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $like_query, $like_query, $like_query, $like_query, $like_query, $like_query);
} else {
    // Fallback to category search if no word query is provided
    if ($selectedType === 'skills' && $selectedCategory !== 'all') {
        $sql = "SELECT 'skills' as post_type, skill_id as id, skill_title as title, category, NULL as item_condition, description, NULL as photo, user_id, level FROM post_skills WHERE skill_title = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $selectedCategory);
    } elseif ($selectedType === 'goods' && $selectedCategory !== 'all') {
        $sql = "SELECT 'goods' as post_type, item_id as id, item_name as title, category, `condition`as item_condition, description, photo, user_id, NULL as level FROM post_goods WHERE category = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $selectedCategory);
    } else {
        // If no query or specific category, show all goods and skills
        $sql = "
            (SELECT 'goods' as post_type, item_id as id, item_name as title, category, `condition`as item_condition, description, photo, user_id, NULL as level FROM post_goods)
            UNION ALL
            (SELECT 'skills' as post_type, skill_id as id, skill_title as title, category, NULL as item_condition, description, NULL as photo, user_id, level FROM post_skills)
        ";
        $stmt = $conn->prepare($sql);
    }
}

// Execute the query and fetch results
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $results = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Browse Listings | CeylonConnect</title>
        <link rel="stylesheet" href="assets/css/browsepg.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    </head>
<body>
    <header>
        <?php include('includes/header.php'); ?>
    </header>

    <div class="back-btn-container">
        <a href="javascript:history.back()" class="nav-btn">Back</a>
    </div>


    <main class="container">
        
        <section class="listings-grid">
            <?php if (!empty($results)): ?>
                <?php foreach ($results as $row): ?>
                    <div class="post-card">
                        <?php if ($row['post_type'] === 'goods'): ?>
                            <?php if (!empty($row['photo'])): ?>
                                <img src="../PostItem/<?= htmlspecialchars($row['photo']) ?>" alt="Post Image" class="post-image">
                            <?php else: ?>
                                <img src="assets/images/goods_placeholder.png" alt="No Image" class="post-image">
                            <?php endif; ?>
                        <?php else: ?>
                            <?php
                                // Sanitize file path components by replacing spaces with underscores
                                $skill_folder = str_replace(' ', '_', $row['title']);
                                $skill_image_base = str_replace(' ', '_', $row['category']);
                                $image_found = false;

                                // Define possible image extensions
                                $extensions = ['png', 'jpg', 'jpeg'];

                                foreach ($extensions as $ext) {
                                    $file_path = "skillPost/" . $skill_folder . "/" . $skill_image_base . "." . $ext;
                                    // Check if the file exists on the server
                                    if (file_exists($file_path)) {
                                        $final_skill_path =$file_path;
                                        $image_found = true;
                                        break;
                                    }
                                }

                                // If no image was found, use the placeholder
                                if (!$image_found) {
                                    $final_skill_path = "assets/images/skill_placeholder.png";
                                }
                            ?>
                            <img src="<?= htmlspecialchars($final_skill_path) ?>" alt="Skill Image" class="post-image">
                        <?php endif; ?>
                        
                        
                        <div class="post-content">
                            <?php if ($row['post_type'] === 'goods'): ?>
                                <p class="item-id">Item ID: <?= htmlspecialchars($row['id']) ?></p>
                                <h3 class="post-title"><?= htmlspecialchars($row['title']) ?></h3>
                                <p class="post-category">Category: <?= htmlspecialchars($row['category']) ?></p>
                                <p class="post-condition">Condition: <?= htmlspecialchars($row['item_condition']) ?></p>
                                <p class="post-description"><?= htmlspecialchars($row['description']) ?></p>
                            <?php else: ?>
                                <p class="item-id">Skill ID: <?= htmlspecialchars($row['id']) ?></p>
                                <h3 class="post-title"><?= htmlspecialchars($row['title']) ?></h3>
                                <p class="post-category">Category: <?= htmlspecialchars($row['category']) ?></p>
                                <p class="post-level">Level: <?= htmlspecialchars($row['level']) ?></p>
                                <p class="post-description"><?= htmlspecialchars($row['description']) ?></p>
                            <?php endif; ?>

                            <div class="post-actions">
                                <a href="profile.php?user_id=<?= htmlspecialchars($row['user_id']) ?>">
                                    <button class="view-details-btn">View Details</button>
                                </a>
                                <button class="send-request-btn">Send Request</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No listings found matching your search criteria.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>© 2025 CeylonConnect. All rights reserved. | Designed by CST 23 – UWU</p>
    </footer>

</body>
</html>

<?php
$conn->close();
?>