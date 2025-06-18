<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo 'unauthorized';
    exit();
}

include('../config/db_connect.php');

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $delete = mysqli_query($conn, "DELETE FROM users WHERE id='$user_id'");

    if ($delete) {
        echo 'success';
    } else {
        echo 'failed';
    }
} else {
    echo 'invalid';
}
?>
