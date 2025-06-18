<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    // Fetch service details from database
    $service_result = $conn->query("SELECT id, title, price FROM services WHERE id = '$service_id'");
    if ($service_result->num_rows > 0) {
        $service = $service_result->fetch_assoc();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['service_id'] == $service_id) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'service_id' => $service['id'],
                'title' => $service['title'],
                'price' => $service['price'],
                'quantity' => $quantity
            ];
        }
    }
}

header('Location: cart.php');
exit();
?>
