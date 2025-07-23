<?php
$conn = new mysqli("localhost", "root", "", "ceylonconnect_db");
if ($conn->connect_error) die("DB Connection failed");

$nic = trim($_POST['nic']);

$stmt = $conn->prepare("SELECT email FROM users WHERE nic = ? LIMIT 1");
$stmt->bind_param("s", $nic);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  session_start();
  $_SESSION['reset_nic'] = $nic;
  header("Location: enter-email.html");
  exit();
} else {
  echo "❌ NIC not found.";
}
?>