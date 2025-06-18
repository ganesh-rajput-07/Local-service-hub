<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

$coupons = mysqli_query($conn, "SELECT * FROM coupons");

$page_title = "Coupon List";
include('admin_layout.php');
?>

<div class="container mt-4">
    <h2>All Coupons</h2>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Code</th>
                <th>Discount Type</th>
                <th>Discount Value</th>
                <th>Min Order</th>
                <th>Expiry Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($coupon = mysqli_fetch_assoc($coupons)) { ?>
                <tr>
                    <td><?php echo $coupon['code']; ?></td>
                    <td><?php echo ucfirst($coupon['discount_type']); ?></td>
                    <td><?php echo $coupon['discount_value']; ?></td>
                    <td>₹<?php echo $coupon['minimum_order']; ?></td>
                    <td><?php echo $coupon['expiry_date']; ?></td>
                    <td><?php echo ucfirst($coupon['status']); ?></td>
                    <td>
                        <a href="admin_update_coupon.php?id=<?php echo $coupon['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                        <button class="btn btn-danger btn-sm delete-coupon" data-id="<?php echo $coupon['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery CDN -->

<script>
    $(document).ready(function () {
        $('.delete-coupon').click(function () {
            let couponId = $(this).data('id');

            if (confirm('Are you sure you want to delete this coupon?')) {
                $.ajax({
                    url: 'delete_coupon.php',
                    type: 'POST',
                    data: { coupon_id: couponId },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            location.reload();
                            alert('Coupon deleted successfully!');
                        } else {
                            alert('Failed to delete coupon.');
                        }
                    },
                    error: function () {
                        alert('Error deleting coupon.');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
