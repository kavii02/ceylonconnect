<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp'] ?? '');
    $new_password = $_POST['password'] ?? '';

    if (empty($entered_otp) || empty($new_password)) {
        echo "<script>alert('Please enter both OTP and new password.'); window.history.back();</script>";
        exit();
    }

    if (!isset($_SESSION['otp'], $_SESSION['reset_email'])) {
        echo "<script>alert('Session expired. Please restart password reset.'); window.location.href='reset-password.html';</script>";
        exit();
    }

    if ($entered_otp == $_SESSION['otp']) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        // Update password in DB
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            // Clear session variables
            unset($_SESSION['otp']);
            unset($_SESSION['reset_email']);
            unset($_SESSION['nic']);

            echo "<script>alert('Password updated successfully.'); window.location.href='Login1.html';</script>";
        } else {
            echo "<script>alert('Failed to update password.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Incorrect OTP.'); window.history.back();</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
