<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (isset($_POST['action'])) {
    foreach ($cart as $index => $item) {
        if ($item['service_id'] == $_POST['service_id']) {
            if ($_POST['action'] == 'increment') {
                $_SESSION['cart'][$index]['quantity'] += 1;
            } elseif ($_POST['action'] == 'decrement' && $_SESSION['cart'][$index]['quantity'] > 1) {
                $_SESSION['cart'][$index]['quantity'] -= 1;
            } elseif ($_POST['action'] == 'remove') {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex cart
            }
            break;
        }
    }
    // Clear coupon if cart changes
    unset($_SESSION['coupon']);
    header('Location: cart.php');
    exit();
}

$services = [];
$total_amount = 0;

foreach ($cart as $item) {
    $service_id = $item['service_id'];
    $quantity = $item['quantity'];

    $service_result = $conn->query("SELECT * FROM services WHERE id = '$service_id'")->fetch_assoc();

    $service_result['quantity'] = $quantity;
    $service_result['subtotal'] = $service_result['price'] * $quantity;

    $total_amount += $service_result['subtotal'];
    $services[] = $service_result;
}

// Apply coupon if present
$discount = 0;
$discount_display = '';
if (isset($_SESSION['coupon'])) {
    $coupon = $_SESSION['coupon'];

    if ($coupon['discount_type'] == 'percent') {
        $discount = ($total_amount * $coupon['discount_value']) / 100;
        $discount_display = $coupon['discount_value'] . '% (₹' . $discount . ')';
    } else {
        $discount = $coupon['discount_value'];
        $discount_display = '₹' . $discount;
    }
}


$final_amount = $total_amount - $discount;
if ($final_amount < 0) {
    $final_amount = 0;
}

// On form submission, redirect to payment page
if (isset($_POST['checkout'])) {
    $_SESSION['total_amount'] = $final_amount;
    header('Location: checkout.php');
    exit();
}

$page_title = "Cart";
include('layout.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">Your Cart</h2>

    <?php if (count($services) > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Service</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service) { ?>
                        <tr>
                            <td><?php echo $service['title']; ?></td>
                            <td>₹<?php echo $service['price']; ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                    <input type="hidden" name="action" value="decrement">
                                    <button class="btn btn-sm btn-secondary">-</button>
                                </form>
                                <span class="mx-2"><?php echo $service['quantity']; ?></span>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                    <input type="hidden" name="action" value="increment">
                                    <button class="btn btn-sm btn-secondary">+</button>
                                </form>
                            </td>
                            <td>₹<?php echo $service['subtotal']; ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <h4>Total Amount: ₹<?php echo $total_amount; ?></h4>

        <form action="apply_coupon.php" method="POST" class="mb-3 mt-3">
            <input type="text" name="coupon_code" placeholder="Enter Coupon Code" required class="form-control mb-2">
            <button type="submit" class="btn btn-success">Apply Coupon</button>
        </form>

        <?php if (isset($_SESSION['coupon'])) { ?>
            <div class="alert alert-info">
                Coupon <strong><?php echo $_SESSION['coupon']['code']; ?></strong> applied. Discount: <?php echo $discount_display; ?>
            </div>
        <?php } ?>

        <h4>Final Amount: ₹<?php echo $final_amount; ?></h4>

        <form method="POST">
            <button type="submit" name="checkout" class="btn btn-success mt-3">Proceed to Checkout</button>
        </form>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Continue Shopping</a>

    <?php } else { ?>
        <div class="alert alert-info">Your cart is empty.</div>
        <a href="dashboard.php" class="btn btn-primary">Go Back to Services</a>
    <?php } ?>
</div>
