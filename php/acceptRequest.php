<?php
include 'Connection.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];

    $db = new Database();
    $conn = $db->getConnection();

    //  sender and receiver gannawa
    $stmt = $conn->prepare("SELECT sender_id, receiver_id FROM skill_exchange_requests WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
        exit;
    }

    $sender_id = $data['sender_id'];
    $receiver_id = $data['receiver_id'];

    // request status eka update karanwa
    $update = $conn->prepare("UPDATE skill_exchange_requests SET status = 'accepted' WHERE request_id = ?");
    $update->bind_param("i", $request_id);
    $update->execute();

    //  chat room ekata insert karanwa
    $insert = $conn->prepare("INSERT INTO chat_rooms (user1_id, user2_id, exchange_id, created_at) VALUES (?, ?, ?, NOW())");
    $insert->bind_param("iii", $sender_id, $receiver_id, $request_id);
    $insert->execute();
    $chat_id = $insert->insert_id;

    echo json_encode(['success' => true, 'chat_id' => $chat_id]);

    $db->closeConnection();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
