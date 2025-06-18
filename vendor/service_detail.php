<?php
session_start();
include('../config/db_connect.php');

if (!isset($_GET['id'])) {
    header('Location: my_services.php');
    exit();
}

$service_id = $_GET['id'];

$service_result = $conn->query("SELECT s.*, v.name AS vendor_name, v.email AS vendor_email, v.phone AS vendor_phone, v.address AS vendor_address, v.skills, v.description AS vendor_description, v.experience FROM services s JOIN vendors v ON s.vendor_id = v.id WHERE s.id = '$service_id'");

if ($service_result->num_rows == 0) {
    echo "<script>alert('Service not found'); window.location.href='my_services.php';</script>";
    exit();
}

$service = $service_result->fetch_assoc();
$images = json_decode($service['image'], true);

if (!is_array($images)) {
    $images = [];
}

$page_title = "Service Details";
include('layout.php');
?>

<h2 class="mb-4">Service Details</h2>

<div class="row mb-4 align-items-start">
    <div class="col-md-6">
        <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($images as $index => $img) { ?>
                    <div class="carousel-item <?php if ($index == 0) echo 'active'; ?> text-center">
                        <div class="zoom-container">
                            <img src="../uploads/<?php echo $img; ?>" class="img-fluid zoom-image" style="max-height: 400px; object-fit: contain;">
                        </div>
                    </div>
                <?php } ?>
            </div>

            <?php if (count($images) > 1) { ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            <?php } ?>
        </div>
    </div>

    <div class="col-md-6">
        <h4><?php echo $service['title']; ?></h4>
        <p><strong>Description:</strong> <?php echo $service['description']; ?></p>
        <p><strong>Price:</strong> ₹<?php echo $service['price']; ?></p>
        <p><strong>Keywords:</strong> <?php echo $service['keywords']; ?></p>

        <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-warning me-2">Edit Service</a>
        <a href="delete_service.php?id=<?php echo $service['id']; ?>" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to delete this service?');">Delete Service</a>
        <a href="my_services.php" class="btn btn-secondary">Back to My Services</a>
    </div>
</div>

<h4 class="mb-3">Vendor Details</h4>
<div class="card p-4 mb-4">
    <p><strong>Name:</strong> <?php echo $service['vendor_name']; ?></p>
    <p><strong>Email:</strong> <?php echo $service['vendor_email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $service['vendor_phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $service['vendor_address']; ?></p>
    <p><strong>Skills:</strong> <?php echo $service['skills']; ?></p>
    <p><strong>Description:</strong> <?php echo $service['vendor_description']; ?></p>
    <p><strong>Experience:</strong> <?php echo $service['experience']; ?></p>
</div>

<a href="edit_profile.php" class="btn btn-outline-success">Edit Vendor Profile</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
