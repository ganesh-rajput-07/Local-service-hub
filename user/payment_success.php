<?php
session_start();
include('../config/db_connect.php');
require_once '../vendor/autoload.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || !isset($_GET['payment_id'])) {
    header('Location: dashboard.php');
    exit();
}

$user_ids = $_SESSION['user_id'];

// Get User Details
$getUser = mysqli_query($conn, "SELECT name, email FROM users WHERE id = '$user_ids'");
$userData = mysqli_fetch_assoc($getUser);

$user_name = $userData['name'];
$user_email = $userData['email'];

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$total_amount = $_SESSION['total_amount'];
$full_address = $_SESSION['full_address'];
$payment_id = $_GET['payment_id'];

include 'send_email.php'; // Include email function file

foreach ($cart as $item) {
    $service_id = $item['service_id'];
    $quantity = $item['quantity'];

    // Insert Booking
    $conn->query("INSERT INTO bookings (user_id, vendor_id, service_id, quantity, total_amount, booking_status, payment_status, payment_mode, address)
        VALUES ('$user_id', (SELECT vendor_id FROM services WHERE id = '$service_id'), '$service_id', '$quantity', '{$total_amount}', 'pending', 'paid', 'upi', '$full_address')");

    $booking_id = $conn->insert_id;

    // Insert Payment
    $conn->query("INSERT INTO payments (booking_id, amount, payment_mode) VALUES ('$booking_id', '{$total_amount}', 'upi')");

    // Get Vendor Details
    $getVendor = mysqli_query($conn, "SELECT v.name, v.email FROM vendors v INNER JOIN services s ON v.id = s.vendor_id WHERE s.id = '$service_id'");
    $vendorData = mysqli_fetch_assoc($getVendor);

    $vendor_name = $vendorData['name'];
    $vendor_email = $vendorData['email'];

    // Vendor Email Body
    $vendor_body = '
    <div style="max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; font-family: Arial, sans-serif;">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="https://i.ibb.co/Zz3b5N3y/logo.png" alt="Local Service Hub" width="100%">
        </div>
        <h2 style="color: #333;">Hello, ' . $vendor_name . '</h2>
        <p style="color: #555;">You have received a new booking from ' . $user_name . '.</p>
        <p style="color: #555;">Booking ID: <strong>' . $booking_id . '</strong></p>
        <p style="color: #555;">Please check your dashboard and confirm the booking.</p>
        <a href="https://yourwebsite.com/vendor_dashboard" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 5px;">View Booking</a>
        <hr style="margin: 20px 0;">
        <p style="text-align: center; color: #888; font-size: 12px;">&copy; ' . date('Y') . ' Local Service Hub. All Rights Reserved.</p>
    </div>
    ';

    // Send Email to Vendor
    sendEmail($vendor_email, 'New Booking Received - Local Service Hub', $vendor_body);
}

// User Email Body
$body = '
<div style="max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="https://i.ibb.co/Zz3b5N3y/logo.png" alt="Local Service Hub" width="100%">
    </div>
    <h2 style="color: #333;">Hello, ' . $user_name . '</h2>
    <p style="color: #555;">Thank you for your booking with <strong>Local Service Hub</strong>.</p>
    <p style="color: #555;">Your booking ID is: <strong>' . $booking_id . '</strong></p>
    <p style="color: #555;">We will update you once your vendor confirms the service.</p>
    <a href="https://yourwebsite.com/bookings" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: #fff; text-decoration: none; border-radius: 5px;">View Booking</a>
    <hr style="margin: 20px 0;">
    <p style="text-align: center; color: #888; font-size: 12px;">&copy; ' . date('Y') . ' Local Service Hub. All Rights Reserved.</p>
</div>
';

// Send Email to User
sendEmail($user_email, 'Your Booking Confirmation', $body);

// Clear Session
unset($_SESSION['cart']);
unset($_SESSION['total_amount']);
unset($_SESSION['full_address']);
unset($_SESSION['payment_mode']);

// Redirect with Alert
echo "<script>alert('Payment Successful! Payment ID: $payment_id'); window.location.href='my_bookings.php';</script>";
exit();
?>
