<?php
$host = "localhost";
$username = "root";
$password = "Mihini123"; // XAMPP default
$dbname = "ceylonconnect"; // Use your actual DB name from Workbench

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
