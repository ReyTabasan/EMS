<?php
    
include "api_conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $usertype = $_POST['role'];
    
    
    $sql = "INSERT INTO user_tb (username, email, password, role) VALUES ('$username', '$email', '$password', '$usertype')";
    if ($conn->query($sql) === TRUE) {

        header("Location: admin.php ");

    } else {

        echo 'Error';
    }
}
$conn->close();

?>