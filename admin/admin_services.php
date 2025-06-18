<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

include('../config/db_connect.php');
$page_title = "Manage Services";
include('admin_layout.php');

$services = mysqli_query($conn, "SELECT s.*, c.title AS category_name, v.name AS vendor_name 
                                 FROM services s 
                                 LEFT JOIN categories c ON s.category_id = c.id 
                                 LEFT JOIN vendors v ON s.vendor_id = v.id 
                                 ORDER BY s.id DESC");


if (!$services) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<div class="container">
    <h2 class="mb-4">Manage Services</h2>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Price (₹)</th>
                <th>Category</th>
                <th>Vendor</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="service-table">
            <?php while ($service = mysqli_fetch_assoc($services)) { ?>
                <tr id="service-row-<?php echo $service['id']; ?>">
                    <td><?php echo $service['id']; ?></td>
                    <td><?php echo $service['title']; ?></td>
                    <td><?php echo $service['price']; ?></td>
                    <td><?php echo $service['category_name'] ?? 'N/A'; ?></td>
                    <td><?php echo $service['vendor_name'] ?? 'N/A'; ?></td>
                    <td>
    <a href="update_service.php?id=<?php echo $service['id']; ?>" class="btn btn-warning btn-sm">Update</a>
    <button class="btn btn-danger btn-sm delete-service" data-id="<?php echo $service['id']; ?>">Delete</button>
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
        $('.delete-service').click(function () {
            let serviceId = $(this).data('id');

            if (confirm('Are you sure you want to delete this service?')) {
                $.ajax({
                    url: 'delete_service.php',
                    type: 'POST',
                    data: { service_id: serviceId },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            $('#service-row-' + serviceId).remove();
                            alert('Service deleted successfully!');
                        } else {
                            alert('Failed to delete service.');
                        }
                    },
                    error: function () {
                        alert('Error deleting service.');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
