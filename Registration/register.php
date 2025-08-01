<?php
// Database connection
require 'db.php'; 

$conn = new mysqli("localhost", "root", "", "ceylonconnect");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load PHPMailer classes
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get user inputs
$name = $_POST['name'];
$nic = $_POST['nic'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hash

// Check if NIC already exists
$check = $conn->prepare("SELECT nic FROM user WHERE NIC = ?");
$check->bind_param("s", $nic);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "❌ NIC already registered. Try again";
    exit();
}

// Generate OTP
$otp = rand(1000, 9999);

// Send email using PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    // Uncomment for debug if needed:
    // $mail->SMTPDebug = 2; 
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nipuninirupama0916@gmail.com';
    $mail->Password = 'zitggdncoxpkvltq'; // Make sure there are no spaces
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email content
    $mail->setFrom('nipuninirupama0916@gmail.com', 'CeylonConnect');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'CeylonConnect Email Verification';
    $mail->Body = "<h3>Your OTP Code is: <b>$otp</b></h3>";

    $mail->send();

    // If email sent successfully, store data temporarily with OTP
    $insert = $conn->prepare("INSERT INTO user (NIC, full_name, phone_number, email, password, otp_code, is_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $insert->bind_param("ssssss", $nic, $name, $phone, $email, $password, $otp);

    if ($insert->execute()) {
        //echo "✅ OTP sent successfully to $email. Please verify.";
        //echo "<p><a href='otp-verification.php?email=$email'>Verify OTP</a></p>";

        header("Location: otp-verification.php?email=" . urlencode($email));
exit();

    } else {
        echo "❌ Error inserting user: " . $conn->error;
    }

} catch (Exception $e) {
    echo "❌ OTP sending failed. Mailer Error: {$mail->ErrorInfo}";
}

$conn->close();
?>
