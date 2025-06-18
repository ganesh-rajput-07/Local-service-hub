<?php
include('../config/db_connect.php');

// Function to add notification
function addNotification($user_id, $vendor_id, $title, $message) {
    global $conn;

    $user = $user_id != null ? "'$user_id'" : 'NULL';
    $vendor = $vendor_id != null ? "'$vendor_id'" : 'NULL';

    $conn->query("INSERT INTO notifications (user_id, vendor_id, title, message, is_read) VALUES ($user, $vendor, '$title', '$message', 0)");
}
?>
