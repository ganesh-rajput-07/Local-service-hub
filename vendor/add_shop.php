<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "Add Shop";
include('layout.php');

$vendor_id = $_SESSION['user_id'];

if (isset($_POST['create_shop'])) {
    $shop_title = $_POST['shop_title'];
    $conn->query("INSERT INTO shops (title, vendor_id) VALUES ('$shop_title', '$vendor_id')");
    echo "<script>alert('Shop Created Successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<h2>Create Shop</h2>
<form method="POST" class="p-4 border rounded bg-white">
    <div class="mb-3">
        <label>Shop Name:</label>
        <input type="text" name="shop_title" class="form-control" required>
    </div>
    <button type="submit" name="create_shop" class="btn btn-primary w-100">Create Shop</button>
</form>

<a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>


