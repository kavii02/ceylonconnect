<?php
class WantedItemPost {
    private $conn;

    public function __construct() {
        // IMPORTANT: Replace "localhost", "root", "" with your actual database credentials
        $this->conn = new mysqli("localhost", "root", "", "ceylonconnect");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // No longer generates a w_XXXX ID; accepts the good_id from the form
    public function postWantedItem($item_id, $item_name_display, $category, $description) {
        // Use prepared statements for security (HIGHLY RECOMMENDED!)
        $stmt = $this->conn->prepare("INSERT INTO item_want (item_id, item_name, category, description) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            return "Error preparing statement: " . $this->conn->error;
        }

        // "ssss" indicates all four parameters are strings
        // item_id will receive the good_id (e.g., 'i_1234')
        // item_name_display will receive the text (e.g., 'Novels')
        $stmt->bind_param("ssss", $item_id, $item_name_display, $category, $description);

        if ($stmt->execute()) {
            $stmt->close();
            return "success";
        } else {
            $error = $stmt->error;
            $stmt->close();
            return "Error: " . $error;
        }
    }
}

// Session start for navigation bar login status
session_start();
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postObj = new WantedItemPost();
    
    // $_POST['item_name'] will now directly contain the good_id from the selected option's value
    // The actual display text needs to be retrieved from $_POST['item_name_display_text']
    $result = $postObj->postWantedItem(
        $_POST['item_name'], // This holds the good_id
        $_POST['item_name_display_text'], // This holds the actual item name text
        $_POST['category'],
        $_POST['description']
    );

    if ($result === "success") {
        echo "<script>alert('Wanted item added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add wanted item: " . $result . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Add Required Item | CeylonConnect</title>
    <link rel="stylesheet" href="assets/css/required.css" />
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
            <h1>Add a Required Item</h1>
            <form method="POST" action="">
                <label for="item-name">Item Name</label>
                <select id="item-name" name="item_name" required>
                    <option value="">Select an Item</option>
                    <option value="g_01">Novels</option>
                    <option value="g_02">Story Books</option>
                    <option value="g_03">Educational Books</option>
                    <option value="g_04">Yoga Mats</option>
                    <option value="g_05">Skipping Ropes</option>
                    <option value="g_06">Bats</option>
                    <option value="g_07">Dumbbells</option>
                    <option value="g_08">Bags</option>
                    <option value="g_09">Back Packs</option>
                    <option value="g_10">Shoes</option>
                    <option value="g_11">Water Bottles</option>
                    <option value="g_12">Lunch Box</option>
                    <option value="g_13">Caps</option>
                    <option value="g_14">Watches</option>
                    <option value="g_15">Sunglass</option>
                    <option value="g_16">Slippers</option>
                    <option value="">Other (specify in description)</option>
                </select>
                <input type="hidden" id="item-name-display-text" name="item_name_display_text">

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

                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Describe the item you are looking for (e.g., brand, specific model, condition, desired features, how urgently you need it)..." rows="6"></textarea>

                <button type="submit" class="submit-btn">Add Required Item</button>
            </form>
        </section>
        <section class="illustration">
            <img src="assets/images/required_items.png" alt="Wanted Item Illustration" class="illustration-img">
        </section>
    </main>

    <footer>
        <p>© 2025 CeylonConnect. All rights reserved. | Designed by CST 23 – UWU</p>
    </footer>

    <script>
    // Simple JavaScript to capture the display text of the selected item
    // This is needed because the 'value' of the option is now the good_id
    document.addEventListener('DOMContentLoaded', function() {
        const itemNameSelect = document.getElementById('item-name');
        const itemNameDisplayText = document.getElementById('item-name-display-text');

        // Set initial value for hidden field in case an option is pre-selected
        if (itemNameSelect.value) {
            itemNameDisplayText.value = itemNameSelect.options[itemNameSelect.selectedIndex].text;
        }

        itemNameSelect.addEventListener('change', function() {
            itemNameDisplayText.value = this.options[this.selectedIndex].text;
        });
    });
    </script>
</body>
</html>