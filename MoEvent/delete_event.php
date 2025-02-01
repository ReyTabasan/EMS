<?php
include 'conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['event_id'])) {
    $eventId = intval($_POST['event_id']);
    
    // Prepare and execute the delete query
    $sql = "DELETE FROM events WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error deleting event: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid event ID.";
}
?>
