<?php
include 'conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'event'; 
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc' ? 'desc' : 'asc'; 

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM events WHERE id = '$delete_id'";
    if (!mysqli_query($conn, $sql)) {
        die("Error deleting event: " . mysqli_error($conn));
    }
}

$sql = "SELECT id, event, location, participants, date, time, done FROM events ORDER BY $sort_column $sort_order";  
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
    <title>User Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .event-title-btn {
            background-color: #333333;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            text-align: center; 
            transition: background-color 0.3s ease;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }

        .event-title-btn:hover {
            background-color: #555555;
        }

        button {
            background-color: #333333;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #555555;
        }

        .event-details {
            display: none;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        th {
            text-align: center;
        }

        th a {
            display: inline-block;
            text-decoration: none;
        }

        th i {
            margin-left: 5px;
            visibility: visible;
            color: #aaa;
        }

        th a:hover i {
            color: #333;
        }

        .admin-dropdown {
            position: absolute;
            top: 10px;
            right: 20px;
            z-index: 110;
        }

        #admin-toggle {
            background-color: transparent;
            border: none;
            outline: none;
            box-shadow: none;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 35px;
            right: 0;
            background-color: #fff;
            color: black;
            min-width: 160px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        .admin-dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<?php include 'userhead.php'; ?>
<div class="container mt-5">
    <h2>User Dashboard - Event List</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    <a href="?sort_column=event&sort_order=<?php echo $sort_order == 'asc' ? 'desc' : 'asc'; ?>" class="text-decoration-none">
                        Events
                        <i class="fas fa-sort<?php echo ($sort_column == 'event') ? '-' . ($sort_order == 'asc' ? 'up' : 'down') : ''; ?>"></i>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <button class="event-title-btn" onclick="toggleEventDetails(<?php echo $row['id']; ?>)">
                                <?php echo htmlspecialchars($row['event']); ?>
                            </button>
                            <div class="event-details" id="event-details-<?php echo $row['id']; ?>">
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                                <p><strong>Date:</strong> <?php echo date('Y-m-d', strtotime($row['date'])); ?></p>
                                <p><strong>Participants:</strong> <?php echo htmlspecialchars($row['participants']); ?></p>
                                <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($row['time'])); ?></p>
                                <p class="status-column">
                                    <?php if ($row['done'] == 1): ?>
                                        <button class="btn btn-success btn-sm" disabled>✔️ Done</button>
                                    <?php else: ?>
                                        <button class="btn btn-warning btn-sm" disabled>...Pending</button>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function toggleEventDetails(eventId) {
        var detailsDiv = document.getElementById('event-details-' + eventId);
        if (detailsDiv.style.display === 'none' || detailsDiv.style.display === '') {
            detailsDiv.style.display = 'block';
        } else {
            detailsDiv.style.display = 'none';
        }
    }
</script>

</body>
</html>
