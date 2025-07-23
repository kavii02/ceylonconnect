<?php
$host = "localhost";
$username = "root";
$password = "Mihini123"; // your real MySQL password
$dbname = "ceylonconnect"; // your correct database

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
