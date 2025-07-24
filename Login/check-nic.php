<?php
session_start();
require 'db.php'; // since db.php is also in Login folder

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nic = trim($_POST['nic']);

    $stmt = $conn->prepare("SELECT * FROM user WHERE nic = ?");
    $stmt->bind_param("s", $nic);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['nic'] = $nic;
        header("Location: enter-email.html"); // also in Login folder
        exit();
    } else {
        echo "<script>alert('NIC not found.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
