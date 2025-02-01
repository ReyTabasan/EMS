<?php
include 'conn.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch item details
    $sql = "SELECT * FROM events WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $item = mysqli_fetch_assoc($result);
} else {
    echo "Event not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #17a2b8;
            color: white;
            font-size: 1.5rem;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
        }

        .card-body p {
            font-size: 1.1rem;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .card-body p strong {
            color: #17a2b8;
        }

        .btn-back {
            background-color: #17a2b8;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px 24px;
            border-radius: 5px;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-back:hover {
            background-color: #138496;
            transform: scale(1.05);
        }

        .card-body img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Event Details
            </div>
            <div class="card-body">
                <p><strong>Model:</strong> <?= htmlspecialchars($item['model']) ?></p>
                <p><strong>Brand:</strong> <?= htmlspecialchars($item['brand']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($item['status']) ?></p>

                <a href="item.php">
                    <button class="btn-back">Back to Items List</button>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
