<?php
session_start();
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "ceylonconnect");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userContact = $_POST['contact'];
    $userEmail = $_POST['email'];
    $userMessage = $_POST['message'];

    // Get admin email (username = 'livini')
    $adminEmail = '';
    $stmt = $conn->prepare("SELECT email FROM admin WHERE username = ?");
    $adminUsername = 'livini';
    $stmt->bind_param("s", $adminUsername);
    $stmt->execute();
    $stmt->bind_result($adminEmail);
    $stmt->fetch();
    $stmt->close();

    if (!$adminEmail) {
        echo "<script>alert('Admin email not found.');</script>";
    } else {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'livbudara@gmail.com';        // Change this
            $mail->Password   = 'agsx lfdh dfye imrq';          // Change this
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('livbudara@gmail.com', 'CeylonConnect'); // Change this
            $mail->addAddress($adminEmail);

            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = "
                <h3>Contact Form Details</h3>
                <p><strong>Contact Number:</strong> {$userContact}</p>
                <p><strong>Email:</strong> {$userEmail}</p>
                <p><strong>Message:</strong><br>{$userMessage}</p>
            ";

            $mail->send();
            echo "<script>alert('Message sent successfully!');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us | CeylonConnect</title>
  <link rel="stylesheet" href="assets/css/Contact_us.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <header class="top-bar">
    <div class="logo">
      <img src="assets/images/logo.png" alt="CeylonConnect Logo" class="logo-img">
      <span>CeylonConnect</span>
    </div>
    <nav class="nav-buttons">
        <button class="back-btn" onclick="window.location.href='/ceylonconnect/Home_AfterLogIn/HomeAfter.php'">Back</button>
    </nav>
  </header>

  <main class="container">
    <section class="form-section">
      <h1>Contact Us</h1>
      <p class="contact-tagline">Feel free to ask any questions or reach out to us!</p>
      <form action="" method="post" class="contact-form">
        <label for="contact">Contact Number</label>
        <input type="tel" id="contact" name="contact" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Your Message</label>
        <textarea id="message" name="message" rows="6" required></textarea>

        <button type="submit" class="submit-btn">Send</button>
      </form>
    </section>

    <section class="illustration">
      <img src="assets/images/contact_us.png" alt="Contact Illustration" class="illustration-img">
    </section>
  </main>

  <footer>
    <p>© 2025 CeylonConnect. All rights reserved. | Designed by CST 23 – UWU</p>
  </footer>
</body>
</html>
