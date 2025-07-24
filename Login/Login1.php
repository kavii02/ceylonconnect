<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and normalize email input
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Prepare SQL query to fetch user by email (case-insensitive)
    $stmt = $conn->prepare("SELECT user_id, full_name, password FROM user WHERE LOWER(email) = ? AND is_verified =1");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Securely compare the entered password with the hashed password in DB
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                header("Location: ../Home_AfterLogin/HomeAfter.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('No user found with this email.'); window.history.back();</script>";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
