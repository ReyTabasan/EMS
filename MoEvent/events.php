<?php
session_start();
include 'conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        // Create Event
        $event = mysqli_real_escape_string($conn, $_POST['event']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $participants = mysqli_real_escape_string($conn, $_POST['participants']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);

        // Insert event into the database
        $sql = "INSERT INTO events (event, location, participants, date, time) 
                VALUES ('$event', '$location', '$participants', '$date', '$time')";
        if (!mysqli_query($conn, $sql)) {
            die("Error inserting event: " . mysqli_error($conn));
        }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'accomplish') {
        // Accomplish Event
        $eventId = intval($_POST['event_id']);
        $imagePath = '';

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            $imagePath = $targetDir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

            // Validate file type
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                    // File uploaded successfully
                } else {
                    die("Error uploading the file.");
                }
            } else {
                die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            }
        }

        // Update event as done and add image
        $sql = "UPDATE events SET done = 1, image = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $imagePath, $eventId);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error updating event: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        // Edit Event
        $eventId = intval($_POST['event_id']);
        $event = mysqli_real_escape_string($conn, $_POST['event']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $participants = mysqli_real_escape_string($conn, $_POST['participants']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);

        // Update event in the database
        $sql = "UPDATE events SET event = '$event', location = '$location', participants = '$participants', date = '$date', time = '$time' WHERE ID = $eventId";
        if (!mysqli_query($conn, $sql)) {
            die("Error updating event: " . mysqli_error($conn));
        }
    }
}

// Fetch events
$sql = "SELECT * FROM events ORDER BY date ASC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body {
        margin: 0;
        overflow-x: hidden;
    }

    .container {
        padding: 20px;
    }

    .add-item-form {
        position: fixed;
        top: 0;
        right: -300px;
        width: 300px;
        height: 100%;
        background-color: #f9f9f9;
        border-left: 1px solid #ddd;
        padding: 20px;
        box-shadow: -2px 0px 5px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 100;
    }

    .add-item-form.show {
        right: 0;
    }

    .table-responsive {
        margin-top: 20px;
        overflow-x: auto;
    }

    .table {
        width: 100%;
    }

    .modal-content {
        text-align: center;
    }
</style>
<body>
    <?php include 'header.php'; ?>
    <?php include 'nav.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Event List</h2>
            <button class="btn btn-primary btn-sm" id="add-item-btn"><i class="fas fa-plus"></i> ADD EVENT</button>
        </div>

        <div class="add-item-form" id="add-item-form">
            <h4 id="form-title">Add Event</h4>
            <form method="POST" action="events.php">
                <input type="hidden" name="action" value="create" id="form-action">
                <input type="hidden" name="event_id" id="event-id">
                <div class="form-group">
                    <label for="event">Event Title:</label>
                    <input type="text" id="event" name="event" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="participants">Participants:</label>
                    <input type="text" id="participants" name="participants" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" id="submit-button">Add Event</button>
                <button type="button" class="btn btn-secondary" id="close-form-btn">Cancel</button>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Location</th>
                        <th>Participants</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr id="event-<?php echo $row['ID']; ?>">
                                <td style="color: blue;font-weight: bold;"><?php echo htmlspecialchars($row['event']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo htmlspecialchars($row['participants']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td style="color: green;font-weight: bold;"><?php 
                                    $time = date("h:i A", strtotime($row['time'])); 
                                    echo htmlspecialchars($time); 
                                    ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-event" data-id="<?php echo $row['ID']; ?>">Delete</button>
                                    <button class="btn btn-warning btn-sm edit-event" data-id="<?php echo $row['ID']; ?>" data-event="<?php echo htmlspecialchars($row['event']); ?>" data-location="<?php echo htmlspecialchars($row['location']); ?>" data-participants="<?php echo htmlspecialchars($row['participants']); ?>" data-date="<?php echo $row['date']; ?>" data-time="<?php echo $row['time']; ?>">Edit</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('add-item-btn').addEventListener('click', function () {
            document.getElementById('add-item-form').classList.toggle('show');
        });

        document.getElementById('close-form-btn').addEventListener('click', function () {
            document.getElementById('add-item-form').classList.remove('show');
        });

        // Edit Event
        $('.edit-event').on('click', function() {
            const eventId = $(this).data('id');
            const event = $(this).data('event');
            const location = $(this).data('location');
            const participants = $(this).data('participants');
            const date = $(this).data('date');
            const time = $(this).data('time');

            // Populate the form with the current event data
            $('#form-title').text('Edit Event');
            $('#form-action').val('edit');
            $('#event-id').val(eventId);
            $('#event').val(event);
            $('#location').val(location);
            $('#participants').val(participants);
            $('#date').val(date);
            $('#time').val(time);

            // Show the form
            $('#add-item-form').addClass('show');
        });

        $('#close-form-btn').on('click', function () {
            $('#add-item-form').removeClass('show');
        });

        // Delete Event
        $('.delete-event').on('click', function() {
            const eventId = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to undo this action!",
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_event.php',
                        type: 'POST',
                        data: { event_id: eventId },
                        success: function(response) {
                            if (response.trim() === 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'The event has been deleted.',
                                    'success'
                                ).then(() => {
                                    $('#event-' + eventId).remove();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong. Please try again.',
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
