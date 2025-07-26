document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (e) {
    if (!validateForm()) {
      e.preventDefault(); // Stop form submission if validation fails
    }
  });

  function validateForm() {
    const name = form["name"].value.trim();
    const nic = form["nic"].value.trim();
    const email = form["email"].value.trim();
    const password = form["password"].value;
    let phone = form["phone"].value.trim().replace(/\s+/g, ""); // Remove spaces

    // Validate +94 prefix without a zero after
    if (/^\+940/.test(phone)) {
      alert("❌ Invalid format: Use +94 followed by 7XXXXXXXX (no 0 after +94).");
      return false;
    }

    // Convert +94XXXXXXXXX to 07XXXXXXXX
    if (/^\+94\d{9}$/.test(phone)) {
      phone = "0" + phone.slice(3);
    }

    // ✅ Full name: Only letters and spaces, at least 3 characters
    const namePattern = /^[A-Za-z\s]{3,}$/;
    if (!namePattern.test(name)) {
      alert("❌ Please enter a valid full name (letters only, min 3 characters).");
      return false;
    }

    // ✅ NIC validation: old (9 digits + V/X) or new (12 digits)
    const nicPattern = /^(\d{9}[VvXx]|\d{12})$/;
    if (!nicPattern.test(nic)) {
      alert("❌ Please enter a valid NIC (e.g., 123456789V or 200012345678).");
      return false;
    }

    // ✅ Phone number validation: Must be 07XXXXXXXX
    const phonePattern = /^07\d{8}$/;
    if (!phonePattern.test(phone)) {
      alert("❌ Please enter a valid Sri Lankan phone number (e.g., 0771234567 or +94771234567).");
      return false;
    }

    // ✅ Email validation: basic format
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      alert("❌ Please enter a valid email address.");
      return false;
    }

    // ✅ Password length check
    if (password.length < 6) {
      alert("❌ Password must be at least 6 characters long.");
      return false;
    }

    return true; // ✅ All validations passed
  }
});
