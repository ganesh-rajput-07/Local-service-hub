<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

include('../config/db_connect.php');
$page_title = "Manage Bookings";
include('admin_layout.php');

// ✅ Fetch Bookings with Join
$bookings = $conn->query("SELECT b.*, u.name as customer_name, v.name as vendor_name, s.title as service_name 
                          FROM bookings b
                          JOIN users u ON b.user_id = u.id
                          JOIN vendors v ON b.vendor_id = v.id
                          JOIN services s ON b.service_id = s.id
                          ORDER BY b.id DESC");
?>

<div class="container">
    <h2 class="mb-4">Manage Bookings</h2>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Vendor</th>
                <th>Service</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Address</th>
                <th>Booking Status</th>
                <th>Payment Status</th>
                <th>Payment Mode</th>
                <th>Created At</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($booking = mysqli_fetch_assoc($bookings)) { ?>
                <tr id="row-<?php echo $booking['id']; ?>">
                    <td><?php echo $booking['id']; ?></td>
                    <td><?php echo $booking['customer_name']; ?></td>
                    <td><?php echo $booking['vendor_name']; ?></td>
                    <td><?php echo $booking['service_name']; ?></td>
                    <td><?php echo $booking['quantity']; ?></td>
                    <td>₹<?php echo $booking['total_amount']; ?></td>
                    <td><?php echo $booking['address']; ?></td>
                    <td>
                        <select class="form-select form-select-sm booking-status" data-id="<?php echo $booking['id']; ?>">
                            <option value="pending" <?php if ($booking['booking_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="confirmed" <?php if ($booking['booking_status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                            <option value="completed" <?php if ($booking['booking_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                            <option value="cancelled" <?php if ($booking['booking_status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm payment-status" data-id="<?php echo $booking['id']; ?>">
                            <option value="pending" <?php if ($booking['payment_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="paid" <?php if ($booking['payment_status'] == 'paid') echo 'selected'; ?>>Paid</option>
                        </select>
                    </td>
                    <td><?php echo $booking['payment_mode']; ?></td>
                    <td><?php echo $booking['created_at']; ?></td>
                    <td>
                        <button class="btn btn-success btn-sm update-btn" data-id="<?php echo $booking['id']; ?>">Update</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div> <!-- Close flex-grow-1 from admin_layout -->
</div> <!-- Close d-flex from admin_layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('.update-btn').click(function () {
            let bookingId = $(this).data('id');
            let bookingStatus = $('.booking-status[data-id="' + bookingId + '"]').val();
            let paymentStatus = $('.payment-status[data-id="' + bookingId + '"]').val();

            $.ajax({
                url: 'update_booking_status.php',
                type: 'POST',
                data: {
                    booking_id: bookingId,
                    booking_status: bookingStatus,
                    payment_status: paymentStatus
                },
                success: function (response) {
                    alert(response);
                },
                error: function () {
                    alert('Error while updating booking.');
                }
            });
        });
    });
</script>

</body>
</html>
