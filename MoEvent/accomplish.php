<?php
include 'conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    // Handle the image upload
    if ($_POST['action'] == 'accomplish') {
        $eventId = intval($_POST['event_id']);
        $imagePath = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            $imagePath = $targetDir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

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

        $sql = "UPDATE events SET done = 1, image = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $imagePath, $eventId);

        if ($stmt->execute()) {
            echo "success";
            exit;
        } else {
            echo "Error updating event: " . $stmt->error;
            exit;
        }
        $stmt->close();
    }

    // Handle the image removal
    if ($_POST['action'] == 'remove_image') {
        $eventId = intval($_POST['event_id']);
        $imagePath = $_POST['image_path'];

        // Delete the image file
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Update the database to set the image field to NULL
        $sql = "UPDATE events SET image = NULL WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $eventId);

        if ($stmt->execute()) {
            echo "success";
            exit;
        } else {
            echo "Error removing image: " . $stmt->error;
            exit;
        }

        $stmt->close();
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
    <title>Accomplish Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'nav.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4">Mark Event as Accomplished</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Event</th>
                        <th>Location</th>
                        <th>Participants</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr id="event-<?php echo $row['ID']; ?>">
                                <td>
                                    <?php if (!empty($row['image'])): ?>
                                        <a href="#" class="view-image" data-image="<?php echo $row['image']; ?>">
                                            <img src="<?php echo $row['image']; ?>" alt="Event Image" class="img-thumbnail" style="max-width: 100px;">
                                        </a>
                                        <button class="btn btn-danger btn-sm remove-image" data-id="<?php echo $row['ID']; ?>" data-image="<?php echo $row['image']; ?>">Remove Image</button>
                                    <?php else: ?>
                                        <span>No image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['event']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo htmlspecialchars($row['participants']); ?></td>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['time'])); ?></td>
                                <td>
                                    <?php if ($row['done'] == 0): ?>
                                        <button class="btn btn-primary btn-sm upload-image" data-id="<?php echo $row['ID']; ?>">Upload Image</button>
                                    <?php endif; ?>
                                </td>
                                <td class="status-column">
                                    <?php if ($row['done'] == 1): ?>
                                        <button class="btn btn-success btn-sm" disabled>✔️ Done</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Uploading Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Upload Image for Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="event_id" id="event_id">
                        <input type="hidden" name="action" value="accomplish">
                        <div class="form-group">
                            <label for="image">Select Image</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Upload & Mark as Done</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Viewing Image -->
    <div class="modal fade" id="viewImageModal" tabindex="-1" role="dialog" aria-labelledby="viewImageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewImageModalLabel">Event Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="viewImage" src="" alt="Event Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Show modal on upload image button click
            $('.upload-image').on('click', function() {
                const eventId = $(this).data('id');
                $('#event_id').val(eventId);
                $('#imageModal').modal('show');
            });

            // Handle form submission for image upload
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.trim() === 'success') {
                            Swal.fire(
                                'Success!',
                                'The image has been uploaded and the event marked as done.',
                                'success'
                            ).then(() => {
                                location.reload();
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
            });

            // Show image in modal on clicking the thumbnail
            $('.view-image').on('click', function() {
                const imageUrl = $(this).data('image');
                $('#viewImage').attr('src', imageUrl);
                $('#viewImageModal').modal('show');
            });

            // Remove image on button click
            $('.remove-image').on('click', function() {
                const eventId = $(this).data('id');
                const imagePath = $(this).data('image');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to remove this image?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '',
                            type: 'POST',
                            data: {
                                action: 'remove_image',
                                event_id: eventId,
                                image_path: imagePath
                            },
                            success: function(response) {
                                if (response.trim() === 'success') {
                                    Swal.fire(
                                        'Removed!',
                                        'The image has been removed.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
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
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
