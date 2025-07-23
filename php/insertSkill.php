<?php
header('Content-Type: application/json');
require_once "Connection.php";
require_once "skillPost.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Database();
    $conn = $db->getConnection();

    $skillPost = new SkillPost(
        $conn,
        $_POST['skill-title'],
        $_POST['category'],
        $_POST['description'],
        $_POST['level']
    );

    $response = $skillPost->save();

    echo json_encode($response);

    $db->closeConnection();
}
?>
