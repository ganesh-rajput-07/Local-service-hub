<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$total_amount = 0;

foreach ($cart as $item) {
    $service_id = $item['service_id'];
    $quantity = $item['quantity'];

    $service_result = $conn->query("SELECT price FROM services WHERE id = '$service_id'")->fetch_assoc();
    $total_amount += $service_result['price'] * $quantity;
}

if (isset($_POST['coupon_code'])) {
    $coupon_code = mysqli_real_escape_string($conn, $_POST['coupon_code']);

    $coupon_result = mysqli_query($conn, "SELECT * FROM coupons WHERE code = '$coupon_code' AND status = 'Active'");

    if (mysqli_num_rows($coupon_result) > 0) {
        $coupon = mysqli_fetch_assoc($coupon_result);

        if ($total_amount >= $coupon['minimum_balance']) {

            // ✅ Save coupon info in session exactly
            $_SESSION['coupon'] = [
                'code' => $coupon['code'],
                'discount_value' => $coupon['discount_value'],  // This will be % or flat amount
                'discount_type' => $coupon['discount_type']     // 'percentage' or 'flat'
            ];

            header('Location: cart.php');
            exit();

        } else {
            echo "<script>alert('Your cart amount must be at least ₹" . $coupon['minimum_balance'] . " to apply this coupon.'); window.location.href='cart.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid or expired coupon code.'); window.location.href='cart.php';</script>";
        exit();
    }
}
?>
