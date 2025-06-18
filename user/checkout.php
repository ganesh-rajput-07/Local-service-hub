<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_amount = isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : 0;

if (empty($cart)) {
    header('Location: dashboard.php');
    exit();
}

if (isset($_POST['proceed_to_payment'])) {
    // Get Address Components
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $building = mysqli_real_escape_string($conn, $_POST['building']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    // Full Address String
    $full_address = "Street: $street, Building No: $building, City: $city, District: $district, State: $state, Country: $country, Pincode: $pincode";

    $_SESSION['full_address'] = $full_address;
    $_SESSION['payment_mode'] = $_POST['payment_mode'];

    if ($_POST['payment_mode'] == 'cash') {
        // Insert bookings directly
        foreach ($cart as $item) {
            $service_id = $item['service_id'];
            $quantity = $item['quantity'];

            $conn->query("INSERT INTO bookings (user_id, vendor_id, service_id, quantity, total_amount, booking_status, payment_status, payment_mode, address)
                VALUES ('$user_id', (SELECT vendor_id FROM services WHERE id = '$service_id'), '$service_id', '$quantity', '{$total_amount}', 'pending', 'paid', 'cash', '$full_address')");

            $booking_id = $conn->insert_id;

            $conn->query("INSERT INTO payments (booking_id, amount, payment_mode) VALUES ('$booking_id', '{$total_amount}', 'cash')");
        }

        unset($_SESSION['cart']);
        unset($_SESSION['total_amount']);
        unset($_SESSION['full_address']);
        unset($_SESSION['payment_mode']);

        echo "<script>alert('Order Placed Successfully!'); window.location.href='my_bookings.php';</script>";
        exit();
    } elseif ($_POST['payment_mode'] == 'upi') {
        header('Location: razorpay_payment.php');
        exit();
    }
}

$page_title = "Checkout";
include('layout.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">Checkout</h2>
    <h4>Total Amount: ₹<?php echo $total_amount; ?></h4>

    <form method="POST" class="p-4 border rounded bg-white shadow-sm">

        <h5 class="mb-3">Enter Delivery Address</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Street:</label>
                <input type="text" name="street" class="form-control" placeholder="Street Name" required>
            </div>
            <div class="col-md-6">
                <label>Building No:</label>
                <input type="text" name="building" class="form-control" placeholder="Building/Flat No" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>City:</label>
                <input type="text" name="city" class="form-control" placeholder="City Name" required>
            </div>
            <div class="col-md-4">
                <label>District:</label>
                <input type="text" name="district" class="form-control" placeholder="District" required>
            </div>
            <div class="col-md-4">
                <label>State:</label>
                <input type="text" name="state" class="form-control" placeholder="State" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Country:</label>
                <input type="text" name="country" class="form-control" placeholder="Country" required>
            </div>
            <div class="col-md-4">
                <label>Pincode:</label>
                <input type="text" name="pincode" class="form-control" placeholder="Pincode" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Select Payment Method:</label>
            <select name="payment_mode" class="form-control" required>
                <option value="cash">Cash After Service</option>
                <option value="upi">UPI / Razorpay</option>
            </select>
        </div>

        <button type="submit" name="proceed_to_payment" class="btn btn-primary w-100">Proceed to Payment</button>
    </form>
</div>
