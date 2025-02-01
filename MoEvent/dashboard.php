<?php
include 'conn.php';

$sql_events = "SELECT COUNT(*) AS event FROM events";
$result_events = mysqli_query($conn, $sql_events);
$total_events = mysqli_fetch_assoc($result_events)['event'];

$sql_sched = "SELECT COUNT(*) AS date FROM events";
$result_sched = mysqli_query($conn, $sql_sched);
$total_sched = mysqli_fetch_assoc($result_sched)['date'];

$sql_users = "SELECT COUNT(*) AS username FROM events";
$result_users = mysqli_query($conn, $sql_users);
$total_users = mysqli_fetch_assoc($result_users)['username'];

// Fetch total accomplished events
$sql_accomplished = "SELECT COUNT(*) AS accomplished FROM events WHERE done = 1";
$result_accomplished = mysqli_query($conn, $sql_accomplished);
$total_accomplished = mysqli_fetch_assoc($result_accomplished)['accomplished'];

// Fetch event data for the calendar
$sql_calendar_events = "SELECT event, date FROM events";
$result_calendar_events = mysqli_query($conn, $sql_calendar_events);

$events = [];
while ($row = mysqli_fetch_assoc($result_calendar_events)) {
    $events[] = [
        'title' => $row['event'],
        'start' => $row['date']
    ];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            background-color: #343a40;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .card-body:hover {
            background-color: #495057;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }

        .card-text {
            font-size: 1.2rem;
            color: #007bff;
        }

        .time-widget {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .calendar-widget {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'nav.php'; ?>

    <div class="content">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <!-- Total Events Card -->
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                    <a href="events.php" class="text-decoration-none">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-3x text-light mb-3"></i>
                                <h5 class="card-title text-light">Total Events</h5>
                                <p class="card-text display-4"><?php echo $total_events; ?></p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Schedules Card -->
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                    <a href="events.php" class="text-decoration-none">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-3x text-light mb-3"></i>
                                <h5 class="card-title text-light">Schedules</h5>
                                <p class="card-text display-4"><?php echo $total_sched; ?></p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Total Users Card -->
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                    <a href="users.php" class="text-decoration-none">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-light mb-3"></i>
                                <h5 class="card-title text-light">Total Users</h5>
                                <p class="card-text display-4"><?php echo $total_users; ?></p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Total Events Accomplished Card -->
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                    <a href="accomplish.php" class="text-decoration-none">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-3x text-light mb-3"></i>
                                <h5 class="card-title text-light">Total Events Accomplished</h5>
                                <p class="card-text display-4"><?php echo $total_accomplished; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Time Widget -->
            <div class="time-widget">
                <h4 id="current-time"></h4>
                <span id="current-date"></span>
            </div>

            <!-- Calendar Widget -->
            <div class="calendar-widget">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>

    <script>
        // Time Widget
        function updateTime() {
            const now = new Date();
            const time = now.toLocaleTimeString();
            const date = now.toLocaleDateString();
            document.getElementById('current-time').innerText = time;
            document.getElementById('current-date').innerText = date;
        }
        setInterval(updateTime, 1000);
        updateTime();

        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: <?php echo json_encode($events); ?>  // Pass PHP events array to JavaScript
            });
        });
    </script>
</body>
</html>
