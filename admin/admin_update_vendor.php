<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid Request!'); window.location.href='manage_vendors.php';</script>";
    exit();
}

$vendor_id = $_GET['id'];
$vendor_result = mysqli_query($conn, "SELECT * FROM vendors WHERE id='$vendor_id'");
$vendor = mysqli_fetch_assoc($vendor_result);

if (!$vendor) {
    echo "<script>alert('Vendor not found!'); window.location.href='manage_vendors.php';</script>";
    exit();
}

if (isset($_POST['update_vendor'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    mysqli_query($conn, "UPDATE vendors SET name='$name', email='$email', phone='$phone' WHERE id='$vendor_id'");

    echo "<script>alert('Vendor Updated Successfully!'); window.location.href='manage_vendors.php';</script>";
    exit();
}

$page_title = "Update Vendor";
include('admin_layout.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">Update Vendor</h2>

    <form method="POST" class="p-4 border rounded bg-white">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $vendor['name']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $vendor['email']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $vendor['phone']; ?>" required>
        </div>

        <button type="submit" name="update_vendor" class="btn btn-primary">Update Vendor</button>
    </form>

    <a href="manage_vendors.php" class="btn btn-secondary mt-4">Back to Vendor List</a>
</div>
