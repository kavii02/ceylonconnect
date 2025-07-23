<?php
session_start();
$nic = $_SESSION['reset_nic'] ?? '';
$email = $_SESSION['reset_email'] ?? '';
$otp = trim($_POST['otp']);
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$conn = new mysqli("localhost", "root", "", "ceylonconnect_db");
if ($conn->connect_error) die("DB Error");

$stmt = $conn->prepare("SELECT * FROM users WHERE nic = ? AND email = ? AND otp_code = ?");
$stmt->bind_param("sss", $nic, $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $update = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL WHERE nic = ? AND email = ?");
  $update->bind_param("sss", $new_password, $nic, $email);
  if ($update->execute()) {
    session_destroy();
    header("Location: login.html");
    exit();
  } else {
    echo "❌ Failed to update password.";
  }
} else {
  echo "❌ Invalid OTP.";
}
?>