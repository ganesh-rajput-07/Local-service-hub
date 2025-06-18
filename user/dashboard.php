<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "Dashboard";
include('layout.php');

$user_id = $_SESSION['user_id'];

// Get user coordinates
$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT latitude, longitude FROM users WHERE id = '$user_id'"));
$user_lat = $user_data['latitude'];
$user_lon = $user_data['longitude'];

$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$radius = 25; // distance in kilometers

$category_query = $conn->query("SELECT * FROM categories");

// Distance formula using Haversine formula
$query = "SELECT s.*, c.title AS category_name, v.name AS vendor_name, v.username AS vendor_username, v.id AS vendor_id, 
(6371 * ACOS(COS(RADIANS($user_lat)) * COS(RADIANS(s.latitude)) * COS(RADIANS(s.longitude) - RADIANS($user_lon)) + SIN(RADIANS($user_lat)) * SIN(RADIANS(s.latitude)))) AS distance
FROM services s 
JOIN categories c ON s.category_id = c.id 
JOIN vendors v ON s.vendor_id = v.id 
WHERE s.status = 'active'";

if (!empty($search)) {
    $query .= " AND (s.title LIKE '%$search%' OR v.name LIKE '%$search%' OR v.username LIKE '%$search%')";
}

if (!empty($category_filter)) {
    $query .= " AND s.category_id = '$category_filter'";
}

// Filter by distance less than or equal to 25 km
$query .= " HAVING distance <= $radius ORDER BY distance ASC";

$services = $conn->query($query);
?>

<h2 class="mb-4">Available Services Near You</h2>

<!-- Search and Filter -->
<form method="GET" class="mb-4 d-flex">
    <input type="text" name="search" class="form-control me-2" placeholder="Search by service, vendor or @username" value="<?php echo htmlspecialchars($search); ?>">

    <select name="category" class="form-select me-2">
        <option value="">All Categories</option>
        <?php while ($cat = $category_query->fetch_assoc()) { ?>
            <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $category_filter) echo 'selected'; ?>><?php echo $cat['title']; ?></option>
        <?php } ?>
    </select>

    <button type="submit" class="btn btn-primary">Search</button>
</form>

<div class="row">
    <?php if ($services->num_rows > 0) {
        while ($service = $services->fetch_assoc()) {
            $images = json_decode($service['image'], true);
            $main_image = isset($images[0]) ? "../uploads/" . $images[0] : "../assets/default.png"; ?>

            <div class="col-md-4 mb-4">
                <div class="card service-card h-100">
                    <img src="<?php echo $main_image; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.onerror=null;this.src='../assets/default.png';">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $service['title']; ?></h5>
                        <span class="badge bg-primary mb-2"><?php echo $service['category_name']; ?></span>
                        <p class="card-text"><?php echo substr($service['description'], 0, 100) . "..."; ?></p>
                        <p><strong>Price:</strong> ₹<?php echo $service['price']; ?></p>
                        <p><strong>Vendor:</strong> <?php echo $service['vendor_name']; ?> (@<?php echo $service['vendor_username']; ?>)</p>
                        <p><strong>Distance:</strong> <?php echo round($service['distance'], 2); ?> km</p>
                        <a href="service_detail.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-outline-primary me-2" target="_blank">View Details</a>
                        <a href="vendor_profile.php?vendor_id=<?php echo $service['vendor_id']; ?>" target="_blank" class="btn btn-sm btn-outline-success">View Vendor</a>
                    </div>
                </div>
            </div>
    <?php }
    } else {
        echo "<div class='alert alert-info'><p class='text-muted'>No services available near you.</p>";
    } ?>
</div>
