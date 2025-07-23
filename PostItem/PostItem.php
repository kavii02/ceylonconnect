<?php
class ItemPost {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "ceylonconnect");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function generateItemID() {
        return 'i_' . rand(1000, 9999);
    }

    public function post($item_name, $category, $description, $condition, $photo) {
        $item_id = $this->generateItemID();
        $targetDir = "uploads/";
        $photoName = basename($photo["name"]);
        $targetFile = $targetDir . $photoName;

        if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO post_goods (item_name, category, description, photo, item_id, `condition`)
                    VALUES ('$item_name', '$category', '$description', '$targetFile', '$item_id', '$condition')";

            if ($this->conn->query($sql) === TRUE) {
                return "success";
            } else {
                return "Error: " . $this->conn->error;
            }
        } else {
            return "upload_failed";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postObj = new ItemPost();
    $result = $postObj->post(
        $_POST['item_name'],
        $_POST['category'],
        $_POST['description'],
        $_POST['condition'],
        $_FILES['photo']
    );

    if ($result === "success") {
        echo "<script>alert('Item posted successfully!');</script>";
    } elseif ($result === "upload_failed") {
        echo "<script>alert('Failed to upload photo.');</script>";
    } else {
        echo $result;
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
</head>
<body>
  <header class="top-bar">
    <div class="logo">
      <img src="assets/images/logo.png" alt="CeylonConnect Logo" class="logo-img">
      <span>CeylonConnect</span>
    </div>
    <button class="back-btn">Back</button>
  </header>

  <main class="container">
    <section class="form-section">
      <h1>Post an Item</h1>
      <form method="POST" action="" enctype="multipart/form-data">
        <label for="item-name">Item Name</label>
        <select id="item-name" name="item_name" required>
          <option>Novels</option>
          <option>Story Books</option>
          <option>Educational Books</option>
          <option>Yoga Mats</option>
          <option>Skipping Ropes</option>
          <option>Bats</option>
          <option>Dumbbells</option>
          <option>Educational Books</option>
          <option>Bags</option>
          <option>Back Packs</option>
          <option>Shoes</option>
          <option>Water Bottles</option>
          <option>Lunch Box</option>
          <option>Caps</option>
          <option>Watches</option>
          <option>Sunglass</option>
          <option>Slippers</option>
        </select>

        <label for="category">Category</label>
        <select id="category" name="category" required>
          <option>Electronics</option>
          <option>Books</option>
          <option>Sports</option>
          <option>Music</option>
          <option>Others</option>
        </select>

        <label for="condition">Condition</label>
        <select id="condition" name="condition" required>
          <option>Unused</option>
          <option>Used</option>
</select>

        <label for="description">Description</label>
        <textarea id="description" name="description" placeholder="Add item details like brand, features, or any issues..."></textarea>

        <label for="photo">Upload Photo</label>
        <input id="photo" name="photo" type="file">

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
