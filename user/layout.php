<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

include('../config/db_connect.php');

// Cart count calculation
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/lsh.ico" type="image/x-icon">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F1F5F9;
        }
        .navbar {
            background-color: #1E3A8A;
        }
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        .nav-link {
            color: white !important;
            position: relative;
        }
        .footer {
            background-color: #1E3A8A;
            color: white;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .service-card {
            transition: transform 0.2s;
        }
        .service-card:hover {
            transform: scale(1.02);
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
                <li class="nav-item"><a href="my_bookings.php" class="nav-link">My Bookings</a></li>
                <li class="nav-item"><a href="chat.php" class="nav-link">Chat</a></li>
                <li class="nav-item position-relative">
                    <a href="cart.php" class="nav-link">
                        Cart
                        <?php if ($cart_count > 0) { ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php } ?>
                    </a>
                </li>
                <li class="nav-item"><a href="my_following.php" class="nav-link">Followings</a></li>
                <li class="nav-item"><a href="raise_query.php" class="nav-link">Support</a></li>
                <li class="nav-item"><a href="../auth/logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4 mb-5">
