<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'] ?? '';
    $new_password = $_POST['password'] ?? '';

    // Compare OTP
    if ($entered_otp == $_SESSION['otp']) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        // Update password
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            // Clear session values
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
}
?>
