<?php
include('config/db_connect.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: auth/login.php');
    exit();
}

$service_id = $_GET['service_id'];
$service = $conn->query("SELECT * FROM services WHERE id='$service_id'")->fetch_assoc();

if (isset($_POST['confirm_booking'])) {
    $user_id = $_SESSION['user_id'];
    $vendor_id = $service['vendor_id'];
    $quantity = $_POST['quantity'];
    $payment_mode = $_POST['payment_mode'];
    $total_amount = $quantity * $service['price'];

    $conn->query("INSERT INTO bookings (user_id, vendor_id, service_id, quantity, total_amount, booking_status, payment_status, payment_mode) 
                  VALUES ('$user_id', '$vendor_id', '$service_id', '$quantity', '$total_amount', 'pending', 'pending', '$payment_mode')");

    echo "<script>alert('Booking Successful!'); window.location.href='user/dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Book Service: <?php echo $service['title']; ?></h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo $service['title']; ?></h5>
            <p class="card-text"><?php echo $service['description']; ?></p>
            <p class="card-text"><strong>Price:</strong> ₹<?php echo $service['price']; ?></p>
            <p class="card-text"><strong>Average Rating:</strong> <?php echo number_format($service['average_rating'], 1); ?> ⭐</p>
        </div>
    </div>

    <form method="POST" class="p-4 border rounded bg-white">
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Mode</label>
            <select name="payment_mode" class="form-select" required>
                <option value="cash">Cash on Service</option>
                <option value="upi">UPI</option>
                <option value="card">Card</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>

        <button type="submit" name="confirm_booking" class="btn btn-primary w-100">Confirm Booking</button>
    </form>

    <a href="user/dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
