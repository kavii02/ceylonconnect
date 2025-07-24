<?php
$servername = "localhost";
$username = "root";
$password = "";  // or your MySQL root password if you set one
$dbname = "ceylonconnect";  // your imported database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
