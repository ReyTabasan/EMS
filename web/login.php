<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <title>Login</title>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="p-4 bg-tansparent">
    <figure class="text-center">
  <blockquote class="blockquote">
  <h1 class="display-1 text-center mb-4">EMS</h1>
  </blockquote>
  <figcaption class="blockquote-footer">
    <cite title="Source Title">Event Management System</cite>
  </figcaption>
</figure>
        <form action="api_login.php" method="POST">

          <!-- Alert -->
        <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>
            
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-dark">Login</button>
        </form>
    </div>
</body>
</html>

