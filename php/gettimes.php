<?php
// Include the database connection file
include 'Connection.php';

// Check if day is set and not empty
if (isset($_POST['day']) && !empty($_POST['day'])) {
    // Sanitize input to prevent SQL injection
    $day = mysqli_real_escape_string($connection, $_POST['day']);

    // Prepare and execute SQL query
    $sql = "SELECT time FROM upload_time WHERE day = '$day'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='slots-grid'>"; // Match with CSS grid layout
        while ($row = mysqli_fetch_assoc($result)) {
            $time = htmlspecialchars($row['time']);
            echo "<button class='slot-btn' data-time='$time'>$time</button>";
        }
        echo "</div>";
    } else {
        echo "<p>No available time slots found for this day.</p>";
    }
} else {
    echo "<p>No day selected. Please go back and select a day.</p>";
}

// Close the connection
mysqli_close($connection);
?>
