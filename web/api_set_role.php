<?php
include 'api_conn.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    $sql = "UPDATE user_tb SET role = 'admin' WHERE user_id = 1";
    $result = $conn->prepare($sql);

    if($conn->affected_rows > 0) {
        echo json_encode(array("status" => "success", "message" => "User promoted to admin successfully"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to promote user"));
    }
}
?>