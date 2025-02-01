<?php
include 'api_conn.php';


$event = $_GET['event'];

$sql = "SELECT event, date, location, participants, time, done FROM events WHERE event = '$event'";
$result = $conn->query($sql);

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Event Details</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '</head>';
echo '<body>';

echo '<div class="container my-5">';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="card mb-3">';
        echo '<div class="card-header bg-light text-dark">';
        echo '<h5 class="card-title">' . $row['event'] . '</h5>';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<p><strong>Date:</strong> ' . $row['date'] . '</p>';
        echo '<p><strong>Location:</strong> ' . $row['location'] . '</p>';
        echo '<p><strong>Participants:</strong> ' . $row['participants'] . '</p>';
        echo '<p><strong>Time:</strong> ' . date('h:i A', strtotime($row['time'])) . '</p>';
        
        // Corrected the ternary logic for the "done" status
        echo '<p class="status-column">';
        if ($row['done'] == 1) {
            echo '<button class="btn btn-success btn-sm" disabled>✔️ Done</button>';
            } else {
                echo '<button class="btn btn-warning btn-sm" disabled>... Pending</button>';
            }
        echo '</p>';
        
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="alert alert-warning" role="alert">No events found</div>';
}

echo '</div>';

echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body>';
echo '</html>';

$conn->close();
?>
