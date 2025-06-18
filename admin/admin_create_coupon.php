<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');

    exit();
}

if (isset($_POST['create_coupon'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $minimum_order = $_POST['minimum_order'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    $insert = mysqli_query($conn, "INSERT INTO coupons (code, discount_type, discount_value, minimum_order, expiry_date, status)
        VALUES ('$code', '$discount_type', '$discount_value', '$minimum_order', '$expiry_date', '$status')");

    if ($insert) {
        echo "<script>alert('Coupon Created Successfully!'); window.location.href='admin_create_coupon.php';</script>";
    } else {
        echo "<script>alert('Failed to Create Coupon!'); window.history.back();</script>";
    }
}

$page_title = "Create Coupon";
include('admin_layout.php');
?>

<div class="container mt-4">
    <h2>Create Coupon</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Coupon Code</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Discount Type</label>
            <select name="discount_type" class="form-control" required>
                <option value="percent">Percentage</option>
                <option value="flat">Flat Amount</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Discount Value</label>
            <input type="number" name="discount_value" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Minimum Order Amount</label>
            <input type="number" name="minimum_order" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="create_coupon" class="btn btn-success">Create Coupon</button>
    </form>
</div>
