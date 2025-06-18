<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: admin_users.php');
    exit();
}

$user_id = $_GET['id'];

// Fetch user details
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_result);

if (isset($_POST['update_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    $update = mysqli_query($conn, "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address', pincode='$pincode' WHERE id='$user_id'");

    if ($update) {
        echo "<script>alert('User updated successfully!'); window.location.href='admin_users.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update user.');</script>";
    }
}

$page_title = "Update User";
include('admin_layout.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">Update User</h2>

    <form method="POST" class="p-4 border rounded bg-white">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo $user['name']; ?>">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?php echo $user['email']; ?>">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required value="<?php echo $user['phone']; ?>">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="<?php echo $user['address']; ?>">
        </div>

        <div class="mb-3">
            <label>Pincode</label>
            <input type="text" name="pincode" class="form-control" value="<?php echo $user['pincode']; ?>">
        </div>

        <button type="submit" name="update_user" class="btn btn-success w-100">Update User</button>
    </form>

    <a href="admin_users.php" class="btn btn-secondary mt-3">Back to Users</a>
</div>

</div> <!-- Close flex-grow-1 from admin_layout -->
</div> <!-- Close d-flex from admin_layout -->
