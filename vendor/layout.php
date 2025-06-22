<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

include('../config/db_connect.php');

$vendor_id = $_SESSION['user_id'];
$notification_count = $conn->query("SELECT COUNT(*) AS total FROM notifications WHERE vendor_id='$vendor_id' AND is_read = 0")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #F1F5F9; }
        .navbar { background-color: #1E3A8A; }
        .navbar-brand, .nav-link { color: white !important; }
        .footer { background-color: #1E3A8A; color: white; padding: 10px 0; text-align: center; position: fixed; bottom: 0; width: 100%; }
        .service-card { transition: transform 0.2s; }
        .service-card:hover { transform: scale(1.02); }
          :root[data-theme="dark"] {
            --bg-color: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            --text-color: #ffffff;
            --card-bg: #1e1e2f;
            --link-color: #8a2be2;
        }
        :root[data-theme="light"] {
            --bg-color: #ffffff;
            --text-color: #000000;
            --card-bg: #f8f9fa;
            --link-color: #5b2eff;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            overflow-x: hidden;
            transition: background 0.3s, color 0.3s;
        }
        .navbar {
            background: rgba(12, 10, 50, 0.95);
        }
    </style>
</head>
<body>      

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Local Service Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">

                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="add_category.php" class="nav-link">Add Category</a></li>
                <li class="nav-item"><a href="add_shop.php" class="nav-link">Add Shop</a></li>
                <li class="nav-item"><a href="add_service.php" class="nav-link">Add Service</a></li>
                <li class="nav-item"><a href="my_services.php" class="nav-link">My Services</a></li>
                <li class="nav-item"><a href="my_bookings.php" class="nav-link">Bookings</a></li>
                <li class="nav-item"><a href="chat.php" class="nav-link">Chat</a></li>
                <li class="nav-item"><a href="profile.php" class="nav-link">Profile</a></li>
                <li class="nav-item"><a href="raise_query.php" class="nav-link">Support</a></li>


                <li class="nav-item"><a href="../auth/logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4 mb-5">
