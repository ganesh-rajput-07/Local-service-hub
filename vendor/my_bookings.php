<?php
session_start();
include('../config/db_connect.php');
require('../notifications/add_notification.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

$vendor_id = $_SESSION['user_id'];

// Fetch vendor bookings
$result = $conn->query("SELECT b.*, s.title, u.name AS customer_name FROM bookings b 
                        JOIN services s ON b.service_id = s.id 
                        JOIN users u ON b.user_id = u.id 
                        WHERE b.vendor_id='$vendor_id' ORDER BY b.id DESC");

// Booking Actions
if (isset($_GET['action']) && isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $action = $_GET['action'];

    if ($action == 'accept') {
        $conn->query("UPDATE bookings SET booking_status='confirmed' WHERE id='$booking_id'");
    } elseif ($action == 'reject') {
        $conn->query("UPDATE bookings SET booking_status='cancelled' WHERE id='$booking_id'");
    } elseif ($action == 'complete') {
        $conn->query("UPDATE bookings SET booking_status='completed' WHERE id='$booking_id'");
    } elseif ($action == 'mark_paid') {
        $conn->query("UPDATE bookings SET payment_status='paid' WHERE id='$booking_id'");
    }

    header('Location: my_bookings.php');
    exit();
}

$page_title = "My Bookings";
include('layout.php');
?>

<h2 class="mb-4">My Bookings</h2>

<?php if ($result->num_rows > 0) { ?>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Service: <?php echo $row['title']; ?></h5>
                <p>Customer: <?php echo $row['customer_name']; ?></p>
                <p>Address: <?php echo $row['address']; ?></p>
                <p>Quantity: <?php echo $row['quantity']; ?></p>
                <p>Total Amount: ₹<?php echo $row['total_amount']; ?></p>
                <p>Status: <?php echo ucfirst($row['booking_status']); ?></p>
                <p>Payment: <?php echo ucfirst($row['payment_status']); ?> (<?php echo $row['payment_mode']; ?>)</p>

                <?php if ($row['booking_status'] == 'pending') { ?>
                    <a href="my_bookings.php?action=accept&booking_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Accept</a>
                    <a href="my_bookings.php?action=reject&booking_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                <?php } elseif ($row['booking_status'] == 'confirmed') { ?>
                    <a href="my_bookings.php?action=complete&booking_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Mark as Completed</a>
                <?php } elseif ($row['booking_status'] == 'completed' && $row['payment_status'] == 'pending' && $row['payment_mode'] == 'cash_after_service') { ?>
                    <a href="my_bookings.php?action=mark_paid&booking_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Mark as Paid</a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <div class="alert alert-primary text-center p-3">
        No Bookings Found.
    </div>
<?php } ?>

<a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
