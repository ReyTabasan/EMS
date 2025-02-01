<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $passwd = $_POST['password'];


    $sql = "SELECT * FROM user_tb WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo json_encode(["success" => "User added successfull"]);
        } else {
            echo json_encode(["error" => "Invalid password"]);
        }
    }
}
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login Page</title>
</head>
<style>
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

        .alert {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</style>
<body>
    <div class="container" >
                <form action="api_login.php" method="POST">
                    <h1>Login</h1>
                    <div class="">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                    <a href="register.php">Register</a>
                    </div>
                </form>
    </div>
    
</body>