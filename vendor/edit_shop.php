<?php
session_start();    
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

?>
<?php
$page_title = "Edit Shop Name";
include('layout.php');

$vendor_id = $_SESSION['user_id'];
$shop = $conn->query("SELECT * FROM shops WHERE vendor_id='$vendor_id'")->fetch_assoc();

if (isset($_POST['update_shop'])) {
    $shop_name = $_POST['shop_name'];
    $conn->query("UPDATE shops SET title='$shop_name' WHERE id='{$shop['id']}'");
    echo "<script>alert('Shop Name Updated!'); window.location.href='profile.php';</script>";
    exit();
}
?>

<h2>Edit Shop Name</h2>
<form method="POST" class="p-4 border rounded bg-white">
    <div class="mb-3">
        <label>Shop Name:</label>
        <input type="text" name="shop_name" class="form-control" value="<?php echo $shop['title']; ?>" required>
    </div>
    <button type="submit" name="update_shop" class="btn btn-primary w-100">Update Shop</button>
</form>

<a href="profile.php" class="btn btn-secondary mt-4">Back to Profile</a>


