<?php
session_start();

header('Content-Type: application/json');

require_once 'Connection.php';  // Adjust path if needed

$db = new Database();
$conn = $db->getConnection();

$action = $_GET['action'] ?? '';

if ($action === 'getMessages') {
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
    // Validate POST
    $chat_id = $_POST['chat_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!is_numeric($chat_id) || !is_numeric($user_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid chat_id or user_id']);
        exit;
    }
    $chat_id = intval($chat_id);
    $user_id = intval($user_id);
    $message = trim($message);

    // Handle file upload (optional)
    $attachment_url = null;
    $attachment_type = null;

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['attachment']['tmp_name'];
        $fileName = basename($_FILES['attachment']['name']);
        $fileSize = $_FILES['attachment']['size'];
        $fileType = $_FILES['attachment']['type'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedImageExt = ['jpg','jpeg','png','gif'];
        $allowedPdfExt = ['pdf'];
        $uploadFolder = 'uploads/';

        if (in_array($fileExt, $allowedImageExt)) {
            $attachment_type = 'image';
            $uploadFolder .= 'images/';
        } elseif (in_array($fileExt, $allowedPdfExt)) {
            $attachment_type = 'pdf';
            $uploadFolder .= 'pdf/';
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }

        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0755, true);
        }

        // Create unique file name to avoid overwriting
        $newFileName = uniqid() . '.' . $fileExt;
        $destPath = $uploadFolder . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
            exit;
        }

        $attachment_url = ($attachment_type === 'image' ? 'images/' : 'pdf/') . $newFileName;
    }

    if ($message === '' && $attachment_url === null) {
        echo json_encode(['success' => false, 'message' => 'Message or attachment required']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO chat_messages (chat_id, sender_id, message, attachment_url, attachment_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisss", $chat_id, $user_id, $message, $attachment_url, $attachment_type);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit;
?>
