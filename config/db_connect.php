<?php
date_default_timezone_set('Asia/Kolkata');

$host = 'localhost';
$db = 'local_service_hub';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
