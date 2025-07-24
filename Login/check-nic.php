<?php
session_start();
require 'db.php'; // Assuming db.php is inside Login folder

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nic = trim($_POST['nic']);

    $stmt = $conn->prepare("SELECT * FROM user WHERE nic = ?");
    $stmt->bind_param("s", $nic);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['nic'] = $nic; // ✅ Set session variable correctly
        header("Location: enter-email.html"); // Continue to email input
        exit();
    } else {
        echo "<script>alert('NIC not found.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
