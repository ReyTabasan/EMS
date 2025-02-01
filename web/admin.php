<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <!-- Toggle Button for Sidebar -->
                <button class="navbar-toggler d-inline-block btn btn-outline-light shadow-none border border-light p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <i class="navbar-toggler-icon"></i>
                </button>

                <!-- Logout Button -->
                <form action="api_logout.php" method="POST" class="ms-auto">
                    <button type="submit" class="btn btn-link text-danger fs-4" title="Logout">
                        <i class="bi bi-power"></i>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Sidebar -->
        <div class="offcanvas offcanvas-start" id="sidebar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Dashboard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#manageEvents">Manage Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#manageUsers">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#manageCourses">Manage Courses</a>
                    </li>
                </ul>
                <button class="btn btn-dark mt-3" data-bs-toggle="modal" data-bs-target="#addEventModal">Add Event</button>
                <button class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
                <button class="btn btn-dark mt-2" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>
            </div>
        </div>

        <!-- Main Content (Centered) -->
        <main class="d-flex justify-content-center align-items-center vh-90">
            <div class="w-75">
                <!-- Manage Events -->
                <section id="manageEvents" class="mt-4">
                    <h2 class="h4">Manage Events</h2>
                    <table class="table table-light table-striped-columns">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Participants</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'api_conn.php';
                            $sql = "SELECT * FROM events";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['ID']}</td>
                                        <td>{$row['event']}</td>
                                        <td>{$row['date']}</td>
                                        <td>{$row['location']}</td>
                                        <td>{$row['participants']}</td>
                                        <td>{$row['time']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-primary'>Edit</button>
                                            <button class='btn btn-sm btn-danger'>Delete</button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No events found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>

                <!-- Manage Users -->
                <section id="manageUsers" class="mt-4">
                    <h2 class="h4">Manage Users</h2>
                    <table class="table table-light table-striped-columns">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM user_tb";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['ID']}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['usertype']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-success'>Verify</button>
                                            <button class='btn btn-sm btn-danger'>Delete</button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>

                <!-- Manage Courses -->
                <section id="manageCourses" class="mt-4">
                    <h2 class="h4">Manage Courses</h2>
                    <table class="table table-light table-striped-columns">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM courses";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['ID']}</td>
                                        <td>{$row['course_name']}</td>
                                        <td>
                                            <button class='btn btn-sm btn-danger'>Delete</button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center'>No courses found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api_add_event.php">
                        <div class="mb-3">
                            <label for="event-title" class="form-label">Event Title</label>
                            <input type="text" name="event" class="form-control" id="event-title" placeholder="Enter event title">
                        </div>
                        <div class="mb-3">
                            <label for="event-location" class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" id="event-location" placeholder="Enter event location">
                        </div>
                        <div class="form-group">
                            <label for="participants">Participants:</label>
                            <input type="text" id="participants" name="participants" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="event-date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" id="event-date">
                        </div>
                        <div class="form-group">
                            <label for="time">Time:</label>
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark">Add Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api_register.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select" id="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api_add_course.php">
                        <div class="mb-3">
                            <label for="course-name" class="form-label">Course Name</label>
                            <input type="text" name="course_name" class="form-control" id="course-name" placeholder="Enter course name">
                        </div>
                        <button type="submit" class="btn btn-dark">Add Course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>