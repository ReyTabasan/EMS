<?php
include 'conn.php';
if (isset($_GET['id'])) {
    $event_id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM events WHERE id = '$event_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Event not found");
    }
} else {
    die("No event ID provided");
}

// Update event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $event = mysqli_real_escape_string($conn, $_POST['event']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);

    $update_sql = "UPDATE events SET event = '$event', location = '$location', date = '$date', time = '$time' WHERE id = '$event_id'";
    if (!mysqli_query($conn, $update_sql)) {
        die("Error updating event: " . mysqli_error($conn));
    }
    header("Location: events.php"); // Redirect to the event list after update
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Edit Event</h2>
    <form method="POST" action="edit_event.php?id=<?php echo $event_id; ?>">
        <input type="hidden" name="action" value="edit">
        <div class="form-group">
            <label for="event">Event Title:</label>
            <input type="text" id="event" name="event" class="form-control" value="<?php echo htmlspecialchars($row['event']); ?>" required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($row['location']); ?>" required>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($row['date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="time">Time:</label>
            <input type="time" id="time" name="time" class="form-control" value="<?php echo htmlspecialchars($row['time']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>

</body>
</html>
