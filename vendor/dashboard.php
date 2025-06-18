<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

?><?php
$page_title = "Vendor Dashboard";
include('layout.php');

$vendor_id = $_SESSION['user_id'];

// Get total categories
$total_categories_query = $conn->query("SELECT COUNT(*) AS total FROM categories");
$total_categories = $total_categories_query->fetch_assoc()['total'];

// Get total services
$total_services_query = $conn->query("SELECT COUNT(*) AS total FROM services WHERE vendor_id = '$vendor_id'");
$total_services = $total_services_query->fetch_assoc()['total'];

// Get total bookings
$total_bookings_query = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE vendor_id = '$vendor_id'");
$total_bookings = $total_bookings_query->fetch_assoc()['total'];

// Get unread notifications
$unread_notifications_query = $conn->query("SELECT COUNT(*) AS total FROM notifications WHERE vendor_id = '$vendor_id' AND is_read = 0");
$unread_notifications = $unread_notifications_query->fetch_assoc()['total'];
?>

<h2 class="mb-4">Vendor Dashboard</h2>
<div class="row g-4">
    <!-- Total Categories -->
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Total Categories</h5>
                <p class="card-text fs-1"><?php echo $total_categories; ?></p>
                <a href="add_category.php" class="btn btn-light btn-sm mt-auto">Manage Categories</a>
            </div>
        </div>
    </div>

    <!-- Total Services -->
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Total Services</h5>
                <p class="card-text fs-1"><?php echo $total_services; ?></p>
                <a href="my_services.php" class="btn btn-light btn-sm mt-auto">Manage Services</a>
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="col-md-3">
        <div class="card text-white bg-warning shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Total Bookings</h5>
                <p class="card-text fs-1"><?php echo $total_bookings; ?></p>
                <a href="my_bookings.php" class="btn btn-light btn-sm mt-auto">View Bookings</a>
            </div>
        </div>
    </div>

    <!-- Unread Notifications -->
    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Unread Notifications</h5>
                <p class="card-text fs-1"><?php echo $unread_notifications; ?></p>
                <a href="notifications.php" class="btn btn-light btn-sm mt-auto">View Notifications</a>
            </div>
        </div>
    </div>
</div>

