<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "My Bookings";
include('layout.php');

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT b.*, s.title FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.user_id='$user_id' ORDER BY b.id DESC");
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">My Bookings</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-4">Back to Dashboard</a>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Booking Status</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>₹<?php echo $row['total_amount']; ?></td>
                            <td>
                                <?php
                                $status_class = 'bg-secondary';
                                if ($row['booking_status'] == 'confirmed') $status_class = 'bg-success';
                                elseif ($row['booking_status'] == 'cancelled') $status_class = 'bg-danger';
                                elseif ($row['booking_status'] == 'completed') $status_class = 'bg-primary';
                                ?>
                                <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['booking_status']); ?></span>
                            </td>
                            <td>
                                <?php
                                $payment_class = ($row['payment_status'] == 'paid') ? 'bg-success' : 'bg-warning text-dark';
                                ?>
                                <span class="badge <?php echo $payment_class; ?>"><?php echo ucfirst($row['payment_status']); ?></span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">No bookings found!</div>
    <?php } ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
