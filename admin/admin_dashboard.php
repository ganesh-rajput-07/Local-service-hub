<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
// Session check and redirect
    exit();
}

$page_title = "Admin Dashboard";
include('admin_layout.php');
?>

<div class="container">
    <h2 class="mb-4">Welcome, <?php echo $_SESSION['admin_name']; ?> </h2>

    <div class="row">
        <!-- Example Cards -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Users</h5>
                    <a href="admin_users.php" class="btn btn-light btn-sm mt-2">View Users</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Vendors</h5>
                    <a href="admin_vendors.php" class="btn btn-light btn-sm mt-2">View Vendors</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Services</h5>
                    <a href="admin_services.php" class="btn btn-light btn-sm mt-2">View Services</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Bookings</h5>
                    <a href="manage_bookings.php" class="btn btn-light btn-sm mt-2">View Bookings</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h5 class="card-title">Manage Coupons</h5>
                    <a href="list_coupon.php" class="btn btn-light btn-sm mt-2">View Coupons</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-dark shadow">
                <div class="card-body">
                    <h5 class="card-title">Customer Queries</h5>
                    <a href="admin_queries.php" class="btn btn-light btn-sm mt-2">View Queries</a>
                </div>
            </div>
        </div>
    </div>
</div>

</div> <!-- Close flex-grow-1 from admin_layout -->
</div> <!-- Close d-flex from admin_layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
