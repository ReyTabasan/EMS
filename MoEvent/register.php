<?php
include "conn.php";

$success_message = '';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $passwd = trim($_POST['password']);
    $userty = trim($_POST['usertype']);

    $stmt = $conn->prepare("SELECT id FROM user_tb WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Username or email already exists. Please try a different one.";
    } else {
        $hashed_password = password_hash($passwd, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO user_tb (username, email, password, usertype) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $userty);

        if ($stmt->execute()) {
            $success_message = "Registration successful! You can now <a href='index.php'>Login</a>.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Register</title>
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
            color: grey;
            font-weight: bold;
            text-align: center;
        }

        .form-group a {
            color: #3498db;
            text-decoration: none;
        }

        .form-group a:hover {
            text-decoration: underline;
        }

        .custom-select {
            width: 100%;
            padding: 10px 20px;
            font-size: 16px;
            color: #333;
            border: 2px solid #ccc;
            border-radius: 20px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .custom-select:focus {
            border-color: #3498db;
            background-color: #fff;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.6);
            outline: none;
        }

        .custom-select option {
            padding: 10px;
            background-color: #fff;
            color: #333;
        }

        .custom-select:hover {
            border-color: #3498db;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <h1 class="text-center">Register</h1>

            <!-- Success message -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success text-center">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="register.php">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="usertype">User Type:</label>
                    <select name="usertype" class="custom-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div><br>
                <div class="form-group">
                    <button type="submit" class="btn btn-outline-dark">Register</button>
                </div>
                <div class="form-group text-center mt-3">
                    <p>Already have an account? <a href="index.php">Login here</a></p>
                </div>
            </form>
            
            <!-- Error message -->
            <?php
            if (isset($error_message)) {
                echo "<div class='alert alert-danger text-center'>$error_message</div>";
            }
            ?>

        </div>
    </div>

</body>
</html>
