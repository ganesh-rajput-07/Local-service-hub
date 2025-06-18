<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

$vendor_id = $_SESSION['user_id'];

$vendor_result = $conn->query("SELECT * FROM vendors WHERE id = '$vendor_id'");
$vendor = $vendor_result->fetch_assoc();

if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $skills = $_POST['skills'];
    $description = $_POST['description'];
    $experience = $_POST['experience'];
    $shop_name = $_POST['shop_name'];

    // Optional Image Upload
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = $_FILES['profile_image']['name'];
        $tmp = $_FILES['profile_image']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/$profile_image");
        $conn->query("UPDATE vendors SET profile_image='$profile_image' WHERE id='$vendor_id'");
    }

    // Update Profile
    $conn->query("UPDATE vendors SET name='$name', username='$username', phone='$phone', address='$address', skills='$skills', description='$description', experience='$experience', is_approved=1 WHERE id='$vendor_id'");

    // Create shop if not exists
    $check_shop = $conn->query("SELECT * FROM shops WHERE vendor_id = '$vendor_id'");
    if ($check_shop->num_rows > 0) {
        $conn->query("UPDATE shops SET title='$shop_name' WHERE vendor_id='$vendor_id'");
    } else {
        $conn->query("INSERT INTO shops (vendor_id, title) VALUES ('$vendor_id', '$shop_name')");
    }

    echo "<script>alert('Profile Completed Successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}

$page_title = "Complete Your Profile";
include('layout.php');
?>

<h2 class="mb-4">Complete Your Vendor Profile</h2>

<form method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-white">

    <div class="mb-3">
        <label>Profile Photo:</label>
        <input type="file" name="profile_image" class="form-control">
    </div>

    <div class="mb-3">
        <label>Name:</label>
        <input type="text" name="name" class="form-control" value="<?php echo $vendor['name']; ?>" required>
    </div>

    <div class="mb-3">
        <label>Username:</label>
        <input type="text" name="username" class="form-control" value="<?php echo $vendor['username']; ?>" required>
    </div>

    <div class="mb-3">
        <label>Phone:</label>
        <input type="text" name="phone" class="form-control" value="<?php echo $vendor['phone']; ?>" required>
    </div>

    <div class="mb-3">
        <label>Address:</label>
        <input type="text" name="address" class="form-control" value="<?php echo $vendor['address']; ?>">
    </div>

    <div class="mb-3">
        <label>Skills (Comma Separated):</label>
        <input type="text" name="skills" class="form-control" value="<?php echo $vendor['skills']; ?>">
    </div>

    <div class="mb-3">
        <label>Description:</label>
        <textarea name="description" class="form-control"><?php echo $vendor['description']; ?></textarea>
    </div>

    <div class="mb-3">
        <label>Experience:</label>
        <input type="text" name="experience" class="form-control" value="<?php echo $vendor['experience']; ?>">
    </div>

    <div class="mb-3">
        <label>Shop Name:</label>
        <input type="text" name="shop_name" class="form-control" required>
    </div>

    <button type="submit" name="update_profile" class="btn btn-success w-100">Submit Profile</button>
</form>
