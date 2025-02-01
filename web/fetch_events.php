<?php
include 'api_conn.php';

$sql = "SELECT event FROM events";
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
echo json_encode($events);

$conn->close();
?>