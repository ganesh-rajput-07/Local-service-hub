<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "Vendor Profile";
include('layout.php');

$vendor_id = $_SESSION['user_id'];

$vendor_result = $conn->query("SELECT * FROM vendors WHERE id = '$vendor_id'");
$vendor = $vendor_result->fetch_assoc();

$shop_result = $conn->query("SELECT * FROM shops WHERE vendor_id = '$vendor_id'");
$shop = $shop_result->fetch_assoc();

if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $skills = $_POST['skills'];
    $description = $_POST['description'];
    $experience = $_POST['experience'];
    $shop_name = $_POST['shop_name'];

    $website = $_POST['website'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $x = $_POST['x'];
    $github = $_POST['github'];

    // Profile Image Upload (optional)
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = $_FILES['profile_image']['name'];
        $tmp = $_FILES['profile_image']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/$profile_image");
        $conn->query("UPDATE vendors SET profile_image='$profile_image' WHERE id='$vendor_id'");
    }

    $conn->query("UPDATE vendors SET name='$name',  username='$username', email='$email', phone='$phone', address='$address', skills='$skills', description='$description', experience='$experience', website='$website', facebook='$facebook', instagram='$instagram', x='$x', github='$github', is_approved=1 WHERE id='$vendor_id'");

    $conn->query("UPDATE shops SET title='$shop_name' WHERE vendor_id='$vendor_id'");

    echo "<script>alert('Profile Updated Successfully!'); window.location.href='profile.php';</script>";
    exit();
}
?>

<h2 class="mb-4">Vendor Profile</h2>

<div class="card p-4 mb-4">
    <p><strong>Name:</strong> <?php echo $vendor['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $vendor['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $vendor['phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $vendor['address']; ?></p>
    <p><strong>Skills:</strong> <?php echo $vendor['skills']; ?></p>
    <p><strong>Description:</strong> <?php echo $vendor['description']; ?></p>
    <p><strong>Experience:</strong> <?php echo $vendor['experience']; ?></p>
    
    <h5>Social Links</h5>
    <?php if (!empty($vendor['website'])) { ?><p><a href="<?php echo $vendor['website']; ?>" target="_blank">Website</a></p><?php } ?>
    <?php if (!empty($vendor['facebook'])) { ?><p><a href="<?php echo $vendor['facebook']; ?>" target="_blank">Facebook</a></p><?php } ?>
    <?php if (!empty($vendor['instagram'])) { ?><p><a href="<?php echo $vendor['instagram']; ?>" target="_blank">Instagram</a></p><?php } ?>
    <?php if (!empty($vendor['x'])) { ?><p><a href="<?php echo $vendor['x']; ?>" target="_blank">X (Twitter)</a></p><?php } ?>
    <?php if (!empty($vendor['github'])) { ?><p><a href="<?php echo $vendor['github']; ?>" target="_blank">GitHub</a></p><?php } ?>
</div>

<h4 class="mb-3">Update Full Profile</h4>
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
        <label>Email:</label>
        <input type="email" name="email" class="form-control" value="<?php echo $vendor['email']; ?>" required>
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
       <input type="text" name="shop_name" class="form-control" value="<?php echo isset($shop['title']) ? $shop['title'] : ''; ?>">

    </div>

    <div class="mb-3">
        <label>Website:</label>
        <input type="url" name="website" class="form-control" value="<?php echo $vendor['website']; ?>">
    </div>

    <div class="mb-3">
        <label>Facebook:</label>
        <input type="url" name="facebook" class="form-control" value="<?php echo $vendor['facebook']; ?>">
    </div>

    <div class="mb-3">
        <label>Instagram:</label>
        <input type="url" name="instagram" class="form-control" value="<?php echo $vendor['instagram']; ?>">
    </div>

    <div class="mb-3">
        <label>X (Twitter):</label>
        <input type="url" name="x" class="form-control" value="<?php echo $vendor['x']; ?>">
    </div>

    <div class="mb-3">
        <label>GitHub:</label>
        <input type="url" name="github" class="form-control" value="<?php echo $vendor['github']; ?>">
    </div>

    <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
</form>

<a href="profile.php" class="btn btn-secondary mt-4">Back to Profile</a>
