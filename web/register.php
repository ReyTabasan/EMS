<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <script src="/bootstrap/js/bootstrap.js "></script>
    <title>Register</title>
</head>
<body>
    <div class="container-fluid d-flex align-items-center   " >
                <form method="POST" action="api_register.php">
                    <h1>Register</h1>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="text" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Register</button>
                        <a href="login.php">Do you have an account? Log in here</a>
                </form>
    </div>
    
</body>
</html> 