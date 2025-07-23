<?php
include "Connection.php";
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($connection, $_POST['skill-title']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $level = mysqli_real_escape_string($connection, $_POST['level']);

    // Fetch skill_id from skill table where skill_name = category
    $skillQuery = "SELECT skill_id FROM skill WHERE LOWER(skill_name) = LOWER('$category') LIMIT 1";
    $result = mysqli_query($connection, $skillQuery);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $skill_id = $row['skill_id'];

        // Insert into post_skills
        $query = "INSERT INTO skill_want (skill_title, category, skill_id, description, level)
                  VALUES ('$title', '$category', '$skill_id', '$description', '$level')";

        if (mysqli_query($connection, $query)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => mysqli_error($connection)]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "No matching skill_id found for the selected category."]);
    }

    mysqli_close($connection);
}
?>
