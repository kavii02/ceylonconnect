<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized - Please login."]);
    exit;
}

// NotificationManager class handles DB operations related to notifications
class NotificationManager {
    private $conn;

    // Constructor - accepts database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Fetch notifications for a specific user ID
    public function getUserNotifications($userId) {
        $sql = "
            SELECT id, type, content, created_at, is_read
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        return $notifications;
    }
}

// Database connection (update with your own credentials if needed)
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "ceylonconnect";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create DB and NotificationManager instances
$db = new Database();
$conn = $db->getConnection();
$manager = new NotificationManager($conn);

// Fetch notifications for logged-in user
$userId = $_SESSION['user_id'];
$notifications = $manager->getUserNotifications($userId);

// Return as JSON
header('Content-Type: application/json');
echo json_encode($notifications);
?>
