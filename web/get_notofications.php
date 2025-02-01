<?php
include 'api_conn.php';

$event = $_GET['event'];  

$sql = "SELECT id, message, date_sent FROM notifications WHERE event = '$event' ORDER BY date_sent DESC";
$result = $conn->query($sql);

$notifications = array();

while ($row = $result->fetch_assoc()) {
    $notifications[] = array(
        'id' => $row['id'],
        'message' => $row['message'],
        'date_sent' => $row['date_sent']
    );
}

echo json_encode($notifications);  

$conn->close();
?>
