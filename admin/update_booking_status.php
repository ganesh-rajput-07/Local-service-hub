<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo 'Session expired';
    exit();
}

include('../config/db_connect.php');

if (isset($_POST['booking_id']) && isset($_POST['booking_status']) && isset($_POST['payment_status'])) {
    $booking_id = $_POST['booking_id'];
    $booking_status = $_POST['booking_status'];
    $payment_status = $_POST['payment_status'];

    $conn->query("UPDATE bookings SET booking_status='$booking_status', payment_status='$payment_status' WHERE id='$booking_id'");

    echo 'Booking updated successfully!';
} else {
    echo 'Invalid request';
}
?>
