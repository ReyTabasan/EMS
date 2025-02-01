<?php
session_start();
include 'conn.php';

// Fetch users from the database
$sql = "SELECT ID, username, email, usertype FROM user_tb";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<script>alert('Error fetching users!');</script>";
    exit;
}

// Initialize error and success messages
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $passwd = trim($_POST['password']);
    $usertype = trim($_POST['usertype']);

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM user_tb WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Username or email already exists. Please try a different one.";
    } else {
        // Insert the new user
        $hashed_password = password_hash($passwd, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user_tb (username, email, password, usertype) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $usertype);

        if ($stmt->execute()) {
            $success_message = "User added successfully!";
            // Refresh the user list
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Users List</title>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 100px;
            max-width: 800px;
        }
        .card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="card">
            <h2 class="text-center">Users List</h2>
            <div class="mb-3 text-end">
                <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#registerModal">Add User</button>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php elseif ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr id="event-<?php echo $row['ID']; ?>">
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['usertype']); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-event" data-id="<?php echo $row['ID']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">No users found!</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group mb-3">
                            <input type="text" name="username" placeholder="Username" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="usertype">User Type:</label>
                            <select name="usertype" class="form-control" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
    $('.delete-event').on('click', function () {
        const userId = $(this).data('id'); // Get the user ID

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this action!",
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete_user.php',
                    type: 'POST',
                    data: { event_id: userId },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            Swal.fire(
                                'Deleted!',
                                'The user has been deleted.',
                                'success'
                            ).then(() => {
                                $('#event-' + userId).remove(); // Remove the row from the table
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Unable to delete the user. Please try again.',
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            'Unable to process your request. Please try again later.',
                            'error'
                        );
                    },
                });
            }
        });
    });
});

    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
