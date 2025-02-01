<?php
include "api_conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event = $_POST['event'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $participants = $_POST['participants'];
    $time = $_POST['time'];

    $query = "INSERT INTO events (event, date, location, participants, time) VALUES ('$event', '$date', '$location', '$participants', '$time')";
    if ($conn->query($query)) {
        header("Location: admin.php");
    } else {
        echo json_encode(['error' => 'Event creation failed']);
    }
}
$conn->close();
?>