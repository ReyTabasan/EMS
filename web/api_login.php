<?php
session_start();
include 'api_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin';
        header("Location: admin.php ");
        exit();
    }

    $sql = "SELECT * FROM user_tb WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row['password'] === $password) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = 'user';
            header("Location: login.php?error=Incorrect Pasword or Username!");
        } else {
            header("Location: login.php?error=Invalid password.");
        }
    } else {
        header("Location: login.php?error=Incorrect Pasword or Username!");
    }
}

$conn->close();
?>
