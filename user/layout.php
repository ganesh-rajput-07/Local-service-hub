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
    <link rel="shortcut icon" href="../image/logo.png" type="image/x-icon">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background: radial-gradient(circle at top left, #5b2eff, #8a2be2);
            color: #fff;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .hero .btn {
            margin-top: 20px;
            background-color: #8a2be2;
            border: none;
            color: #fff;
        }
        .section {
            padding: 60px 20px;
        }
        .card {
            background: var(--card-bg);
            border: none;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card i {
            font-size: 2rem;
            color: var(--link-color);
        }
        .footer {
            background: #0f0c29;
            padding: 30px;
            text-align: center;
        }
        a {
            color: var(--link-color);
            text-decoration: none;
        }
        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--link-color);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 30px;
            cursor: pointer;
            z-index: 1000;
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
        <a class="navbar-brand" href="index.php">Local Service Hub</a>
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
