<?php
session_start();
include('../config/db_connect.php');

$admin_id = $_SESSION['admin_id'];
$my_role = 'admin';
$message = mysqli_real_escape_string($conn, $_POST['message']);
$query_id = $_POST['query_id'];

// Fetch partner details
$query = $conn->query("SELECT sender_id, sender_role FROM admin_queries WHERE id = '$query_id'")->fetch_assoc();
$partner_id = $query['sender_id'];
$partner_role = $query['sender_role'];

$conn->query("INSERT INTO chats (sender_id, sender_role, receiver_id, receiver_role, query_id, message) VALUES ('$admin_id', '$my_role', '$partner_id', '$partner_role', '$query_id', '$message')");
?>
