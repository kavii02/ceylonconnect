<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $nic = $_SESSION['nic'] ?? '';

    if (empty($nic)) {
        echo "<script>alert('Session expired. Start again.'); window.location.href='reset-password.html';</script>";
        exit();
    }

    // Check if NIC and email match
    $stmt = $conn->prepare("SELECT * FROM user WHERE nic = ? AND email = ?");
    $stmt->bind_param("ss", $nic, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP to email
        $subject = "Your CeylonConnect OTP Code";
        $message = "Your OTP code is: $otp";
        $headers = "From: ceylonconnect@example.com";

        if (mail($email, $subject, $message, $headers)) {
            header("Location: reset-password-otp.html");
            exit();
        } else {
            echo "<script>alert('Failed to send OTP.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('NIC and Email do not match.'); window.history.back();</script>";
    }
}
?>
