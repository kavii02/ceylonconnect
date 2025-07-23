<?php
include 'Connection.php';

if (!$connection) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

if (isset($_GET['day'])) {
    $day = mysqli_real_escape_string($connection, $_GET['day']);

    $sql = "SELECT time FROM upload_time WHERE day = '$day'";
    $result = mysqli_query($connection, $sql);

    $slots = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $slots[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($slots);
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}
?>
