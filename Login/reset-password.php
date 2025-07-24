<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nic = trim($_POST['nic']);

    // Check NIC in the user table
    $stmt = $conn->prepare("SELECT email FROM user WHERE nic = ?");
    $stmt->bind_param("s", $nic);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['reset_email'] = $user['email']; // Save email for OTP
            $_SESSION['nic'] = $nic;

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            // Send OTP via email
            $to = $user['email'];
            $subject = "CeylonConnect Password Reset OTP";
            $message = "Your OTP code is: $otp";
            $headers = "From: ceylonconnect@example.com";

            if (mail($to, $subject, $message, $headers)) {
                header("Location: reset-password-otp.html");
                exit();
            } else {
                echo "<script>alert('Failed to send OTP.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('NIC not found.'); window.history.back();</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
