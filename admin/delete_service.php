<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo 'unauthorized';
    exit();
}

include('../config/db_connect.php');

if (isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    $delete = mysqli_query($conn, "DELETE FROM services WHERE id='$service_id'");

    if ($delete) {
        echo 'success';
    } else {
        echo 'failed';
    }
} else {
    echo 'invalid';
}
?>
