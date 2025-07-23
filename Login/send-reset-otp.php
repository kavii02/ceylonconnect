<?php
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;

session_start();
$nic = $_SESSION['reset_nic'] ?? '';
$email = trim($_POST['email']);

$conn = new mysqli("localhost", "root", "Mihini123", "ceylonconnect_db");
if ($conn->connect_error) die("DB Error");

$stmt = $conn->prepare("SELECT * FROM user WHERE nic = ? AND email = ?");
$stmt->bind_param("ss", $nic, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $otp = rand(1000, 9999);
  $update = $conn->prepare("UPDATE user SET otp_code = ? WHERE nic = ? AND email = ?");
  $update->bind_param("sss", $otp, $nic, $email);
  $update->execute();

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'nipuninirupama0916@gmail.com';
    $mail->Password = 'zitggdncoxpkvltq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('nipuninirupama0916@gmail.com', 'CeylonConnect');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset OTP';
    $mail->Body = "<h3>Your OTP Code is: <b>$otp</b></h3>";
    $mail->send();

    $_SESSION['reset_email'] = $email;
    header("Location: reset-password.html");
    exit();
  } catch (Exception $e) {
    echo "❌ Failed to send OTP.";
  }
} else {
  echo "❌ NIC and Email do not match.";
}
?>
