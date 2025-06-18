<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo 'unauthorized';
    exit();
}

include('../config/db_connect.php');

if (isset($_POST['vendor_id'])) {
    $vendor_id = $_POST['vendor_id'];

    $delete = mysqli_query($conn, "DELETE FROM vendors WHERE id='$vendor_id'");

    if ($delete) {
        echo 'success';
    } else {
        echo 'failed';
    }
} else {
    echo 'invalid';
}
?>
