<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

include('../config/db_connect.php');
$page_title = "Manage Vendors";
include('admin_layout.php');

// ✅ Fetch All Vendors
$vendors = mysqli_query($conn, "SELECT * FROM vendors ORDER BY id DESC");
?>

<div class="container">
    <h2 class="mb-4">Manage Vendors</h2>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="vendor-table">
            <?php while ($vendor = mysqli_fetch_assoc($vendors)) { ?>
                <tr id="vendor-row-<?php echo $vendor['id']; ?>">
                    <td><?php echo $vendor['id']; ?></td>
                    <td><?php echo $vendor['name']; ?></td>
                    <td><?php echo $vendor['email']; ?></td>
                    <td><?php echo $vendor['phone']; ?></td>
                    <td>
    <a href="admin_update_vendor.php?id=<?php echo $vendor['id']; ?>" class="btn btn-warning btn-sm">Update</a>
    <button class="btn btn-danger btn-sm delete-vendor" data-id="<?php echo $vendor['id']; ?>">Delete</button>
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
        $('.delete-vendor').click(function () {
            let vendorId = $(this).data('id');

            if (confirm('Are you sure you want to delete this vendor?')) {
                $.ajax({
                    url: 'delete_vendor.php',
                    type: 'POST',
                    data: { vendor_id: vendorId },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            $('#vendor-row-' + vendorId).remove();
                            alert('Vendor deleted successfully!');
                        } else {
                            alert('Failed to delete vendor.');
                        }
                    },
                    error: function () {
                        alert('Error deleting vendor.');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
