<?php
// post_item.php (No JavaScript Version)

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ItemPost {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "ceylonconnect");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function post($good_id, $item_name_display, $category, $description, $condition, $photo) {
        $targetDir = "uploads/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        if (!is_writable($targetDir)) {
            return "upload_failed: Uploads directory is not writable. Please set appropriate permissions (e.g., 755 or 777).";
        }

        $photoName = basename($photo["name"]);
        $targetFile = $targetDir . $photoName;

        if ($photo["error"] !== UPLOAD_ERR_OK) {
            switch ($photo["error"]) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    return "upload_failed: File is too large. Max size: " . ini_get('upload_max_filesize') . ".";
                case UPLOAD_ERR_PARTIAL:
                    return "upload_failed: File was only partially uploaded.";
                case UPLOAD_ERR_NO_FILE:
                    return "upload_failed: No file was uploaded. Please select a photo.";
                case UPLOAD_ERR_NO_TMP_DIR:
                    return "upload_failed: Missing a temporary folder for uploads.";
                case UPLOAD_ERR_CANT_WRITE:
                    return "upload_failed: Failed to write file to disk. Check server disk space or permissions.";
                case UPLOAD_ERR_EXTENSION:
                    return "upload_failed: A PHP extension stopped the file upload. (Check php.ini)";
                default:
                    return "upload_failed: Unknown file upload error (Code: " . $photo["error"] . ").";
            }
        }

        if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
            // *** IMPORTANT CHANGE 1: REMOVE 'post_id' from the INSERT query's column list ***
            // The database will auto-generate it.
            $sql = "INSERT INTO post_goods (item_id, item_name, category, description, photo, `condition`)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                return "Database error (prepare): " . $this->conn->error;
            }

            // The bind_param order and types remain the same as 'post_id' is not passed by us
            $stmt->bind_param("ssssss", $good_id, $item_name_display, $category, $description, $targetFile, $condition);

            if ($stmt->execute()) {
                // *** IMPORTANT CHANGE 2: Retrieve the auto-generated ID and format it ***
                $last_inserted_id = $this->conn->insert_id; // Get the raw integer ID
                $formatted_post_id = 'p_' . sprintf('%02d', $last_inserted_id); // Format as p_01, p_02 etc.

                $stmt->close();
                // You can return the formatted ID or store it if needed
                return "success with post_id: " . $formatted_post_id;
            } else {
                $error = $stmt->error;
                $stmt->close();
                if (file_exists($targetFile)) {
                    unlink($targetFile);
                }
                return "Database error (execute): " . $error;
            }
        } else {
            return "upload_failed: Could not move uploaded file. Check directory permissions for 'uploads/'.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postObj = new ItemPost();
    
    $item_name_parts = explode('|', $_POST['item_name']);
    
    $good_id = $item_name_parts[0] ?? '';
    $item_name_display = $item_name_parts[1] ?? '';

    if (empty($good_id) || empty($item_name_display)) {
        echo "<script>alert('Invalid item selection. Please choose an item from the list.');</script>";
    } else {
        $result = $postObj->post(
            $good_id,
            $item_name_display,
            $_POST['category'],
            $_POST['description'],
            $_POST['condition'],
            $_FILES['photo']
        );

        // Update the alert message to show the generated post ID
        if (strpos($result, "success") === 0) { // Check if the result string starts with "success"
            echo "<script>alert('Item posted successfully! " . $result . "');</script>";
        } else {
            echo "<script>alert('Failed to post item: " . $result . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Post an Item | CeylonConnect</title>
    <link rel="stylesheet" href="assets/css/Post.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-bar">
        <div class="logo">
            <img src="assets/images/logo.png" alt="CeylonConnect Logo" class="logo-img">
            <span>CeylonConnect</span>
        </div>
        <nav class="nav-buttons">
            <button class="back-btn" onclick="window.history.back()">Back</button>
        </nav>
    </header>

    <main class="container">
        <section class="form-section">
            <h1>Post an Item</h1>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="item-name">Item Name</label>
                <select id="item-name" name="item_name" required>
                    <option value="">Select an Item</option>
                    <option value="g_01|Novels">Novels</option>
                    <option value="g_02|Story Books">Story Books</option>
                    <option value="g_03|Educational Books">Educational Books</option>
                    <option value="g_04|Yoga Mats">Yoga Mats</option>
                    <option value="g_05|Skipping Ropes">Skipping Ropes</option>
                    <option value="g_06|Bats">Bats</option>
                    <option value="g_07|Dumbbells">Dumbbells</option>
                    <option value="g_08|Bags">Bags</option>
                    <option value="g_09|Back Packs">Back Packs</option>
                    <option value="g_10|Shoes">Shoes</option>
                    <option value="g_11|Water Bottles">Water Bottles</option>
                    <option value="g_12|Lunch Box">Lunch Box</option>
                    <option value="g_13|Caps">Caps</option>
                    <option value="g_14|Watches">Watches</option>
                    <option value="g_15|Sunglass">Sunglass</option>
                    <option value="g_16|Slippers">Slippers</option>
                    <option value="g_17|Other">Other</option>
                </select>

                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a Category</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Books">Books</option>
                    <option value="Sports">Sports</option>
                    <option value="Music">Music</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Accessories">Accessories</option>
                    <option value="Home Goods">Home Goods</option>
                    <option value="Other">Other</option>
                </select>

                <label for="condition">Condition</label>
                <select id="condition" name="condition" required>
                    <option value="">Select Condition</option>
                    <option value="Unused">Unused</option>
                    <option value="Used">Used</option>
                </select>

                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Add item details like brand, features, or any issues..." rows="6"></textarea>

                <label for="photo">Upload Photo</label>
                <input id="photo" name="photo" type="file" accept="image/*">

                <button type="submit" class="submit-btn">Post Item</button>
            </form>
        </section>
        <section class="illustration">
            <img src="assets/images/post.png" alt="Illustration" class="illustration-img">
        </section>
    </main>

    <footer>
        <p>© 2025 CeylonConnect. All rights reserved. | Designed by CST 23 – UWU</p>
    </footer>

    </body>
</html>
