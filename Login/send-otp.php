<?php
session_start();

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$nic = $_SESSION['nic'] ?? '';  // NIC stored in earlier step
$email = trim($_POST['email'] ?? '');

if (empty($nic)) {
    echo "<script>alert('Session expired or NIC not set. Please start again.'); window.location.href='reset-password.html';</script>";
    exit();
}

if (empty($email)) {
    echo "<script>alert('Please enter your email.'); window.history.back();</script>";
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "ceylonconnect");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Check if NIC and email match
$stmt = $conn->prepare("SELECT * FROM user WHERE nic = ? AND LOWER(email) = LOWER(?)");
$stmt->bind_param("ss", $nic, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Generate a 4-digit OTP
    $otp = rand(1000, 9999);

    // Store OTP in DB and mark as not verified
    $update = $conn->prepare("UPDATE user SET otp_code = ?, is_verified = 0 WHERE nic = ? AND LOWER(email) = LOWER(?)");
    $update->bind_param("sss", $otp, $nic, $email);
    $update->execute();

    // Store values in session for verification
    $_SESSION['otp'] = $otp;
    $_SESSION['reset_email'] = $email;

    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mihini1234weerasekara@gmail.com';  // your Gmail
        $mail->Password = 'viojhbfnzsaxfonz';                 // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('mihini1234weerasekara@gmail.com', 'CeylonConnect');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your CeylonConnect Password Reset OTP';
        $mail->Body    = "<h3>Your OTP Code is: <b>$otp</b></h3>";

        $mail->send();

        // Redirect to OTP input form
        header("Location: reset-password-otp.html");
        exit();
    } catch (Exception $e) {
        echo "<script>alert('Failed to send OTP. Mailer Error: " . $mail->ErrorInfo . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('NIC and Email do not match.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
