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
$vendor = $conn->query("SELECT * FROM vendors WHERE id='$vendor_id'")->fetch_assoc();
$shop = $conn->query("SELECT * FROM shops WHERE vendor_id='$vendor_id'")->fetch_assoc();
?>

<h2 class="text-center mb-4">My Profile</h2>

<div class="d-flex justify-content-center mb-4">
    <img src="../uploads/<?php echo $vendor['profile_image']; ?>" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #1E3A8A;">
</div>

<div class="card p-4 mb-4">
    <h4 class="mb-3 text-primary">Personal Details</h4>
    <p><strong>Name:</strong> <?php echo $vendor['name']; ?></p>
    <p><strong>Username:</strong> <?php echo $vendor['username']?></p>
    <p><strong>Email:</strong> <?php echo $vendor['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $vendor['phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $vendor['address']; ?></p>
    <p><strong>Skills:</strong> <?php echo $vendor['skills']; ?></p>
    <p><strong>Description:</strong> <?php echo $vendor['description']; ?></p>
    <p><strong>Experience:</strong> <?php echo $vendor['experience']; ?></p>
   <p><strong>Shop Name:</strong> <?php echo isset($shop['title']) ? $shop['title'] : 'Shop Not Added'; ?></p>



    <h5 class="mt-4">Social Links</h5>
    <?php if (!empty($vendor['website'])) { ?>
        <p><a href="<?php echo $vendor['website']; ?>" target="_blank" style='text-decoration: none;'><i class="fa fa-globe"></i> Website</a></p>
    <?php } ?>
    <?php if (!empty($vendor['facebook'])) { ?>
        <p><a href="<?php echo $vendor['facebook']; ?>" target="_blank" style='text-decoration: none;'><i class="fab fa-facebook"></i> Facebook</a></p>
    <?php } ?>
    <?php if (!empty($vendor['instagram'])) { ?>
        <p><a href="<?php echo $vendor['instagram']; ?>" target="_blank" style='text-decoration: none;'><i class="fab fa-instagram"></i> Instagram</a></p>
    <?php } ?>
    <?php if (!empty($vendor['x'])) { ?>
        <p><a href="<?php echo $vendor['x']; ?>" target="_blank" style='text-decoration: none;'><i class="fab fa-x-twitter"></i> X (Twitter)</a></p>
    <?php } ?>
    <?php if (!empty($vendor['github'])) { ?>
        <p><a href="<?php echo $vendor['github']; ?>" target="_blank" style='text-decoration: none;'><i class="fab fa-github"></i> GitHub</a></p>
    <?php } ?>

    <div class="d-flex justify-content-between mt-4">
        <a href="edit_shop.php" class="btn btn-outline-warning btn-sm fw-bold">Edit Shop</a>
        <a href="complete_profile.php" class="btn btn-outline-success btn-sm fw-bold">Edit Profile</a>
    </div>
</div>

<script>
// Font Awesome CDN
let fontAwesome = document.createElement('link');
fontAwesome.href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css";
fontAwesome.rel = "stylesheet";
document.head.appendChild(fontAwesome);

// Bootstrap Icons (Optional, if you need elsewhere)
let bootstrapIcons = document.createElement('link');
bootstrapIcons.href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css";
bootstrapIcons.rel = "stylesheet";
document.head.appendChild(bootstrapIcons);
</script>

<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
