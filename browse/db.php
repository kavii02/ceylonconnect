<?php
$host = "localhost";
$username = "root";
$password = ""; // XAMPP default
$dbname = "ceylonconnect_db"; // Use your actual DB name from Workbench

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
