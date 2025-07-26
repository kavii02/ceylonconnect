<?php
header('Content-Type: application/json');

require_once 'Connection.php';
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$chat_id = isset($_POST['chat_id']) ? intval($_POST['chat_id']) : 0;
$sender_id = isset($_POST['sender_id']) ? intval($_POST['sender_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($chat_id <= 0 || $sender_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid chat_id or sender_id']);
    exit;
}

$attachment_url = null;
$attachment_type = null;

if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
    $fileTmp = $_FILES['attachment']['tmp_name'];
    $fileName = basename($_FILES['attachment']['name']);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file types
    $validTypes = ['jpg', 'jpeg', 'png', 'pdf'];
    if (!in_array($ext, $validTypes)) {
        echo json_encode(['success' => false, 'message' => 'Unsupported file type']);
        exit;
    }

    // Define subfolder
    $subFolder = ($ext === 'pdf') ? 'pdf' : 'images';
    $uploadDir = "../uploads/$subFolder/";

    // Create directory if missing
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique name
    $uniqueName = uniqid() . "_" . $fileName;
    $targetPath = $uploadDir . $uniqueName;

    if (!move_uploaded_file($fileTmp, $targetPath)) {
        echo json_encode(['success' => false, 'message' => 'File upload failed']);
        exit;
    }

    $attachment_url = "$subFolder/$uniqueName";
    $attachment_type = ($ext === 'pdf') ? 'pdf' : 'image';
}

// Prevent completely empty message
if ($message === '' && $attachment_url === null) {
    echo json_encode(['success' => false, 'message' => 'Message or file required']);
    exit;
}

// Insert into DB
$stmt = $conn->prepare("INSERT INTO chat_messages (chat_id, sender_id, message, attachment_url, attachment_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("iisss", $chat_id, $sender_id, $message, $attachment_url, $attachment_type);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
}

$stmt->close();
$conn->close();
