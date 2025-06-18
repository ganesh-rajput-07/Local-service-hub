<?php
session_start();
include('../config/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['coupon_id'])) {
    $coupon_id = $_POST['coupon_id'];
    $delete_result = mysqli_query($conn, "DELETE FROM coupons WHERE id='$coupon_id'");

    if ($delete_result) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'invalid';
}
?>
