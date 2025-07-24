<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp'] ?? '');

    if (empty($entered_otp)) {
        echo "<script>alert('Please enter the OTP.'); window.history.back();</script>";
        exit();
    }

    if (!isset($_SESSION['otp'], $_SESSION['reset_email'])) {
        echo "<script>alert('Session expired. Please restart password reset.'); window.location.href='reset-password.html';</script>";
        exit();
    }

    if ($entered_otp == $_SESSION['otp']) {
        $email = $_SESSION['reset_email'];

        // Update otp_code and is_verified
        $stmt = $conn->prepare("UPDATE user SET otp_code = NULL, is_verified = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        // Redirect to password reset form
        header("Location: Login1.html");
        exit();
    } else {
        echo "<script>alert('Incorrect OTP. Please try again.'); window.history.back();</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
