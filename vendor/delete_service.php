<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: my_services.php');
    exit();
}

$vendor_id = $_SESSION['user_id'];
$service_id = $_GET['id'];

// Verify Service Belongs to Vendor
$service_result = $conn->query("SELECT * FROM services WHERE id = '$service_id' AND vendor_id = '$vendor_id'");

if ($service_result->num_rows == 0) {
    echo "<script>alert('Service not available'); window.location.href='my_services.php';</script>";
    exit();
}

$service = $service_result->fetch_assoc();

// Delete Service
$conn->query("DELETE FROM services WHERE id = '$service_id'");

$image_path = "../uploads/" . $service['image'];
if (file_exists($image_path)) {
    unlink($image_path);
}

echo "<script>alert('Service Deleted Successfully!'); window.location.href='my_services.php';</script>";
exit();
?>