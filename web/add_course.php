<?php
include "api_conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course = $_POST['course_name'];

    $sql = "INSERT INTO courses (course_name) VALUES ('$course')";
    if ($conn->query($sql)) {
        header("Location: admin.php");
    } else {
        echo json_encode(['error' => 'Course creation failed']);
    }
}
$conn->close();
?>