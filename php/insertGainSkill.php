<?php
session_start();
header('Content-Type: application/json');

require_once "Connection.php";
require_once "gainSkill.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->getConnection();

    $user_id = $_SESSION['user_id'];

    // Validate all required fields
    if (!isset($_POST['skill-title'], $_POST['category'], $_POST['description'], $_POST['level'])) {
        echo json_encode(["success" => false, "error" => "Missing required fields."]);
        exit();
    }

    $skillPost = new GainSkill(
        $conn,
        $_POST['skill-title'],
        $_POST['category'],
        $_POST['description'],
        $_POST['level'],
        $user_id
    );

    $response = $skillPost->save();
    echo json_encode($response);

    $db->closeConnection();
}
?>
