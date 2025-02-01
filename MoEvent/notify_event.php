<?php
include 'conn.php';

if (isset($_GET['event_id'])) {
    $event_id = mysqli_real_escape_string($conn, $_GET['event_id']);
    
    // Update the 'notified' column to 1 (true)
    $sql = "UPDATE events SET notified = 1 WHERE id = '$event_id'";

    if (mysqli_query($conn, $sql)) {
        echo "Notification sent successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
