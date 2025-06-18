<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

$page_title = "Manage Payments";
include('admin_layout.php');

// Fetch all payments
$payments = mysqli_query($conn, "SELECT b.*, 
                                        u.name AS user_name, u.email AS user_email, 
                                        v.name AS vendor_name, v.email AS vendor_email, v.username AS vendor_username 
                                 FROM bookings b
                                 JOIN users u ON b.user_id = u.id
                                 JOIN vendors v ON b.vendor_id = v.id
                                 ORDER BY b.created_at DESC");
?>

<div class="container mt-4">
    <h2>Payment Records</h2>
    <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>User</th>
            <th>User Email</th>
            <th>Vendor</th>
            <th>Vendor Username</th>
            <th>Vendor Email</th>
            <th>Service ID</th>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Payment Status</th>
            <th>Booking Time</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($payment = mysqli_fetch_assoc($payments)) { ?>
            <tr>
                <td><?php echo $payment['user_name']; ?></td>
                <td><?php echo $payment['user_email']; ?></td>
                <td><?php echo $payment['vendor_name']; ?></td>
                <td><?php echo $payment['vendor_username']; ?></td>
                <td><?php echo $payment['vendor_email']; ?></td>
                <td><?php echo $payment['service_id']; ?></td>
                <td>₹<?php echo $payment['total_amount']; ?></td>
                <td><?php echo $payment['payment_mode']; ?></td>
                <td><?php echo ucfirst($payment['payment_status']); ?></td>
                <td><?php echo date('d M Y H:i', strtotime($payment['created_at'])); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
