<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid Request!'); window.location.href='coupon_list.php';</script>";
    exit();
}

$coupon_id = $_GET['id'];
$coupon_result = mysqli_query($conn, "SELECT * FROM coupons WHERE id='$coupon_id'");
$coupon = mysqli_fetch_assoc($coupon_result);

if (!$coupon) {
    echo "<script>alert('Coupon not found!'); window.location.href='coupon_list.php';</script>";
    exit();
}

if (isset($_POST['update_coupon'])) {
    $code = $_POST['code'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $minimum_order = $_POST['minimum_order'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE coupons SET code='$code', discount_type='$discount_type', discount_value='$discount_value', minimum_order='$minimum_order', expiry_date='$expiry_date', status='$status' WHERE id='$coupon_id'");

    echo "<script>alert('Coupon Updated Successfully!'); window.location.href='coupon_list.php';</script>";
    exit();
}

$page_title = "Update Coupon";
include('admin_layout.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">Update Coupon</h2>

    <form method="POST" class="p-4 border rounded bg-white">
        <div class="mb-3">
            <label>Coupon Code</label>
            <input type="text" name="code" class="form-control" value="<?php echo $coupon['code']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Discount Type</label>
            <select name="discount_type" class="form-control" required>
                <option value="flat" <?php if ($coupon['discount_type'] == 'flat') echo 'selected'; ?>>Flat</option>
                <option value="percent" <?php if ($coupon['discount_type'] == 'percent') echo 'selected'; ?>>Percentage</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Discount Value</label>
            <input type="number" name="discount_value" class="form-control" value="<?php echo $coupon['discount_value']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Minimum Order Amount</label>
            <input type="number" name="minimum_order" class="form-control" value="<?php echo $coupon['minimum_order']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="<?php echo $coupon['expiry_date']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="active" <?php if ($coupon['status'] == 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if ($coupon['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" name="update_coupon" class="btn btn-primary">Update Coupon</button>
    </form>

    <a href="list_coupon.php" class="btn btn-secondary mt-4">Back to Coupon List</a>
</div>
