<?php
// Database connection
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Helper function for validation
function validateName($name) {
    // Only letters and spaces allowed
    return preg_match("/^[a-zA-Z\s]+$/", $name);
}

function validateNIC($nic) {
    // 12 digits OR 9 chars with last character V/v or X/x
    return preg_match("/^\d{12}$|^\d{8}[vVxX]$/", $nic);
}

function validatePhone($phone) {
    // +94 followed by 9 digits, optionally remove 0 after +94
    // OR 10 digits starting with 0
    // Remove spaces, dashes etc.
    $phone = preg_replace("/[^0-9\+]/", "", $phone);

    if (preg_match("/^\+94\d{9}$/", $phone)) {
        return $phone; // correct format
    } elseif (preg_match("/^\+940\d{9}$/", $phone)) {
        // Remove the extra 0 after +94
        return '+94' . substr($phone, 4);
    } elseif (preg_match("/^0\d{9}$/", $phone)) {
        return $phone; // local format
    }
    return false;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    // >5 characters, at least 1 number, no uppercase letters or symbols like $<>/\@ etc.
    if (strlen($password) <= 5) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (preg_match('/[A-Z]/', $password)) return false;
    if (preg_match('/[$<>\/\\@]/', $password)) return false;
    return true;
}

// Get user inputs safely
$name = trim($_POST['name'] ?? '');
$nic = trim($_POST['nic'] ?? '');
$phoneInput = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$passwordRaw = $_POST['password'] ?? '';

// Validate inputs
if (!validateName($name)) {
    die("❌ Invalid name. Only letters and spaces allowed.");
}

if (!validateNIC($nic)) {
    die("❌ Invalid NIC format.");
}

$phone = validatePhone($phoneInput);
if (!$phone) {
    die("❌ Invalid phone number format.");
}

if (!validateEmail($email)) {
    die("❌ Invalid email address.");
}

if (!validatePassword($passwordRaw)) {
    die("❌ Password must be >5 chars, include a number, no uppercase letters or symbols like $<>/\\@");
}

// Connect to DB (you already have $conn from db.php, but let's ensure)
$conn = new mysqli("localhost", "root", "", "ceylonconnect");
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Check duplicate NIC
$stmt = $conn->prepare("SELECT NIC FROM user WHERE NIC = ?");
$stmt->bind_param("s", $nic);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    die("❌ NIC already registered.");
}
$stmt->close();

// Check duplicate email
$stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    die("❌ Email already registered.");
}
$stmt->close();

// Hash password
$password = password_hash($passwordRaw, PASSWORD_DEFAULT);

// Generate OTP
$otp = rand(1000, 9999);

// Send OTP Email
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
    $mail->Subject = 'CeylonConnect Email Verification';
    $mail->Body = "<h3>Your OTP Code is: <b>$otp</b></h3>";

    $mail->send();

    // Store user with OTP and is_verified = 0
    $stmt = $conn->prepare("INSERT INTO user (NIC, full_name, phone_number, email, password, otp_code, is_verified) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssssss", $nic, $name, $phone, $email, $password, $otp);
    if ($stmt->execute()) {
        // Redirect to OTP verification page with email param
        header("Location: otp-verification.php?email=" . urlencode($email));
        exit();
    } else {
        die("❌ Failed to register user: " . $conn->error);
    }
} catch (Exception $e) {
    die("❌ OTP sending failed: {$mail->ErrorInfo}");
}

$conn->close();
?>
