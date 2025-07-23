<?php
$email = $_GET['email'] ?? '';
if (empty($email)) {
    die("❌ Email not provided. Please register again.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CeylonConnect - OTP Verification</title>
  <link rel="stylesheet" href="styles/otp-verification.css" />
</head>
<body>

  <div class="logo-group">
    <img src="photos/logo.png" alt="CeylonConnect Logo" class="logo-img" />
    <h1 class="logo-header">CeylonConnect</h1>
  </div>

  <form action="verify.php" method="POST">
    <div class="card">
      <div class="left">
        <h2>Verify your email</h2>
        <p>Please enter the 4-digit code sent to your email address.</p>

        <div class="otp-inputs">
          <input type="text" name="digit1" maxlength="1" required />
          <input type="text" name="digit2" maxlength="1" required />
          <input type="text" name="digit3" maxlength="1" required />
          <input type="text" name="digit4" maxlength="1" required />
        </div>

        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>" />

        <button type="submit">Verify</button>
        <p class="resend">Didn’t receive the code? <a href="registration.html">Resend</a></p>
      </div>

      <div class="right">
        <img src="photos/verify.png" alt="Illustration" />
      </div>
    </div>
  </form>

  <p class="footer-bottom">
    © 2025 CeylonConnect. All rights reserved. | Designed by CST 23 – UWU
  </p>

</body>
</html>
