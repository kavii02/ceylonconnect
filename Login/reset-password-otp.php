<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp'] ?? '');
    $new_password = trim($_POST['password'] ?? '');

    if (empty($entered_otp) || empty($new_password)) {
        echo "<script>alert('Please enter both OTP and new password.'); window.history.back();</script>";
        exit();
    }

    if (!isset($_SESSION['otp'], $_SESSION['reset_email'])) {
        echo "<script>alert('Session expired. Please restart password reset.'); window.location.href='reset-password.html';</script>";
        exit();
    }

    if ($entered_otp == $_SESSION['otp']) {
        $email = $_SESSION['reset_email'];

        // Hash the new password securely
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password, otp_code, and is_verified
        $stmt = $conn->prepare("UPDATE user SET password = ?, otp_code = NULL, is_verified = 1 WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();
        $stmt->close();

        // Clear session variables related to reset
        unset($_SESSION['otp'], $_SESSION['reset_email']);

        // Redirect to login
        echo "<script>alert('Password updated successfully. Please login.'); window.location.href='Login1.html';</script>";
        exit();
    } else {
        echo "<script>alert('Incorrect OTP. Please try again.'); window.history.back();</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
