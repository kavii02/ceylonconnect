<?php
require 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nic = $_POST["nic"] ?? '';
    $password = $_POST["password"] ?? '';

    // Sanitize
    $nic = $conn->real_escape_string($nic);

    // Prepare SQL to fetch hashed password and verification status
    $stmt = $conn->prepare("SELECT name, password, is_verified FROM users WHERE nic = ?");
    $stmt->bind_param("s", $nic);
    $stmt->execute();
    $stmt->bind_result($name, $hashed_password, $is_verified);

    if ($stmt->fetch()) {
        if ($is_verified == 0) {
            echo "<h3>⛔ Please verify your email before logging in.</h3>";
            echo "<p><a href='otp-verification.php'>Verify Now</a></p>";
        } elseif (password_verify($password, $hashed_password)) {
            echo "<h3>✅ Login successful! Welcome, $name.</h3>";
            echo "<p><a href='dashboard.html'>Go to Dashboard</a></p>";
        } else {
            echo "<h3>❌ Incorrect password.</h3>";
            echo "<p><a href='login.html'>Try Again</a></p>";
        }
    } else {
        echo "<h3>❌ NIC not found.</h3>";
        echo "<p><a href='registration.html'>Create an Account</a></p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<h3>⛔ Invalid request method.</h3>";
}
?>
