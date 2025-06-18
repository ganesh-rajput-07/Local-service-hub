<?php
if (!isset($page_title)) {
    $page_title = "Admin Panel";
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/lsh.ico" type="image/x-icon">
    <title><?php echo $page_title; ?> | Local Service Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar .active {
            background-color: #495057;
        }

        .header {
            background-color: #343a40;
            color: white;
            padding: 15px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h4>Admin Panel - Local Service Hub</h4>
    </div>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <h5 class="text-white mb-4">Navigation</h5>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_auth/admin/admin_auth/admin_register.php">Add Admin</a>
            <a href="admin_queries.php">Customer Queries</a>
            <a href="admin_users.php">Manage Users</a>
            <a href="admin_vendors.php">Manage Vendors</a>
            <a href="admin_services.php">Manage Services</a>
            <a href="admin_create_coupon.php">Manage Coupons</a>
            <a href="list_coupon.php">View Coupons</a>
            <a href="admin_payment.php">LSH Payment</a>
            <a href="manage_category.php">Manage Categories</a>
            <a href="manage_bookings.php">Manage Bookings</a>
            <a href="admin_auth/admin/admin_auth/admin_logout.php" class="text-danger">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
