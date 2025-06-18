<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "My Services";
include('layout.php');

$vendor_id = $_SESSION['user_id'];
$services = $conn->query("SELECT s.*, c.title AS category_name FROM services s JOIN categories c ON s.category_id = c.id WHERE s.vendor_id = '$vendor_id' ORDER BY s.id DESC");
?>

<h2 class="mb-4">My Services</h2>

<div class="row">
    <?php if ($services->num_rows > 0) {
        while ($service = $services->fetch_assoc()) {
            $images = json_decode($service['image'], true);
            $main_image = isset($images[0]) ? "../uploads/" . $images[0] : "../assets/default.png"; ?>

            <div class="col-md-4 mb-4">
                <div class="card service-card h-100">
                    <img src="<?php echo $main_image; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.onerror=null;this.src='../assets/default.png';">
                    <div class="card-body">
                        <h5 class="card-title"> <?php echo $service['title']; ?> </h5>
                        <span class="badge bg-primary mb-2"> <?php echo $service['category_name']; ?> </span>
                        <p class="card-text"> <?php echo $service['description']; ?> </p>
                        <p><strong>Price:</strong> ₹<?php echo $service['price']; ?></p>
                        <a href="service_detail.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
    <?php }
    } else {
        echo "<p class='text-muted'>No services found.</p>";
    } ?>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
