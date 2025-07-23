<?php
// For debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include 'Connection.php';

if (isset($_POST['day']) && !empty($_POST['day'])) {
    $day = mysqli_real_escape_string($connection, $_POST['day']);
    
    $sql = "SELECT time FROM upload_time WHERE day = '$day'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $time = htmlspecialchars($row['time']);
            echo "<button class='slot-btn' data-time='$time'>$time</button>";
        }
    } else {
        echo "<p>No available time slots for $day.</p>";
    }
} else {
    echo "<p>No day selected.</p>";
}

mysqli_close($connection);
?>
