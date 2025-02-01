<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM user_tb WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_array($result);

        if ($row) {
            if (password_verify($password, $row["password"])) {

                $_SESSION["username"] = $username;
                if ($row["usertype"] == "user") {
                    header("Location: userdash.php");
                } elseif ($row["usertype"] == "admin") {
                    header("Location: dashboard.php");
                }
                exit;
            } else {

                $showError = true;
            }
        } else {
            $showError = true;
        }
    } else {
        echo "<script> alert('Error executing query!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login Page</title>

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 100px;
            max-width: 400px;
        }

        .card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        h1 {
            color: #2c3e50;
            font-size: 36px;
            font-family: 'Courier New', Courier, monospace;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .form-group label {
            font-weight: bold;
            color: #34495e;
        }

        .alert-custom {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <h1 class="text-center">Login</h1>

            <?php if (isset($showError) && $showError): ?>
                <div class="alert-custom" id="error-message">
                    Incorrect Username or Password. Please try again.
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="index.php" method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <button type="submit" value="Login" class="btn btn-outline-dark">Login</button><br>
                    
                </div>
            </form>
        </div>
    </div>


    <script>

        if (document.getElementById('error-message')) {
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 5000);
        }
    </script>

    <!-- Add the correct Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
