<?php
// Database connection
$conn = new mysqli("localhost", "root", "Mihini123", "ceylonconnect");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get inputs
$email = strtolower(trim($_POST['email'] ?? ''));
$otp = ($_POST['digit1'] ?? '') . ($_POST['digit2'] ?? '') . ($_POST['digit3'] ?? '') . ($_POST['digit4'] ?? '');

// Basic validation
if (empty($email) || strlen($otp) !== 4) {
    die("❌ Invalid input. Please enter the correct OTP.");
}

// Check for correct OTP and unverified user
$sql = "SELECT * FROM user WHERE LOWER(email) = ? AND otp_code = ? AND is_verified = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Correct OTP → mark verified
    $update = "UPDATE user SET is_verified = 1, otp_code = NULL WHERE LOWER(email) = ?";
    $update_stmt = $conn->prepare($update);
    $update_stmt->bind_param("s", $email);
    $update_stmt->execute();

    // ✅ Redirect to login page
    header("Location:../Login/Login1.html");  // Change to your actual login page
    exit();
} else {
    // ❌ Invalid OTP
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Verification Failed</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #ffecec;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }
            .box {
                background: #fff0f0;
                padding: 40px 60px;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                text-align: center;
            }
            .box h2 {
                color: #cc0000;
            }
            .box p {
                margin-top: 10px;
            }
            .box a {
                text-decoration: underline;
                color: #cc0000;
            }
        </style>
    </head>
    <body>
        <div class='box'>
            <h2>❌ Verification Failed</h2>
            <p>The OTP you entered is incorrect or has expired.</p>
            <p><a href='javascript:history.back()'>Try Again</a></p>
        </div>
    </body>
    </html>";
}

$conn->close();
?>
