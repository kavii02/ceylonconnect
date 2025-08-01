<?php
session_start();
header('Content-Type: application/json');

require_once 'Connection.php';

$db = new Database();
$conn = $db->getConnection();

$action = $_GET['action'] ?? '';

if ($action === 'getMessages') {
    // Validate chat_id
    if (empty($_GET['chat_id']) || !is_numeric($_GET['chat_id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid chat_id']);
        exit;
    }
    $chat_id = intval($_GET['chat_id']);

    $stmt = $conn->prepare("SELECT id, chat_id, sender_id, message, attachment_url, attachment_type, created_at FROM chat_messages WHERE chat_id = ? ORDER BY created_at ASC");
    $stmt->bind_param('i', $chat_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;

} elseif ($action === 'sendMessage') {
    // Validate inputs
    $chat_id = $_POST['chat_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if (!is_numeric($chat_id) || !is_numeric($user_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid chat_id or user_id']);
        exit;
    }
    $chat_id = intval($chat_id);
    $user_id = intval($user_id);

    // Check if message or attachment exists
    if ($message === '' && (!isset($_FILES['attachment']) || $_FILES['attachment']['error'] != 0)) {
        echo json_encode(['success' => false, 'message' => 'Message or attachment required']);
        exit;
    }

    $attachment_url = null;
    $attachment_type = null;

    // File upload handling with validation
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $fileTmp = $_FILES['attachment']['tmp_name'];
        $fileName = basename($_FILES['attachment']['name']);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed_exts = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($ext, $allowed_exts)) {
            echo json_encode(['success' => false, 'message' => 'Unsupported file type']);
            exit;
        }

        // Max file size 5MB
        $maxFileSize = 5 * 1024 * 1024;
        if ($_FILES['attachment']['size'] > $maxFileSize) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit']);
            exit;
        }

        $subFolder = ($ext === 'pdf') ? 'pdf' : 'images';
        $uploadDir = __DIR__ . "/../uploads/$subFolder/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Clean file name to avoid issues
        $safeFileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName);
        $uniqueName = uniqid() . "_" . $safeFileName;
        $targetPath = $uploadDir . $uniqueName;

        if (!move_uploaded_file($fileTmp, $targetPath)) {
            echo json_encode(['success' => false, 'message' => 'File upload failed']);
            exit;
        }

        $attachment_url = "$subFolder/$uniqueName";
        $attachment_type = ($ext === 'pdf') ? 'pdf' : 'image';
    }

    $stmt = $conn->prepare("INSERT INTO chat_messages (chat_id, sender_id, message, attachment_url, attachment_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('iisss', $chat_id, $user_id, $message, $attachment_url, $attachment_type);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insert failed']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit;
