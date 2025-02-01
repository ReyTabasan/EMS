<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "event_db";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection Error: " . mysqli_connect_error());
}
?>
