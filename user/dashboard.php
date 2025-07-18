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

// Get user data
$user_stmt = $conn->prepare("SELECT latitude, longitude, pincode FROM users WHERE id = ?");
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();

$user_lat = $user_data['latitude'];
$user_lon = $user_data['longitude'];
$user_pincode = $user_data['pincode'];

$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';

$category_query = $conn->query("SELECT * FROM categories");

$search_param = "%{$search}%";

// Fetch all services
$query = "SELECT s.*, c.title AS category_name, v.name AS vendor_name, v.username AS vendor_username, v.id AS vendor_id 
FROM services s 
JOIN categories c ON s.category_id = c.id 
JOIN vendors v ON s.vendor_id = v.id 
WHERE s.status = 'active'";

if (!empty($search)) {
    $query .= " AND (s.title LIKE ? OR v.name LIKE ? OR v.username LIKE ? )";
}

if (!empty($category_filter)) {
    $query .= " AND s.category_id = ?";
}

$query .= " ORDER BY s.id DESC";

$stmt = $conn->prepare($query);

if (!empty($search) && !empty($category_filter)) {
    $stmt->bind_param('sssi', $search_param, $search_param, $search_param, $category_filter);
} elseif (!empty($search)) {
    $stmt->bind_param('sss', $search_param, $search_param, $search_param);
} elseif (!empty($category_filter)) {
    $stmt->bind_param('i', $category_filter);
}

$stmt->execute();
$result = $stmt->get_result();

$services_in_pincode = [];
$services_other = [];

while ($service = $result->fetch_assoc()) {
    if ($service['pincode'] == $user_pincode) {
        $services_in_pincode[] = $service;
    } else {
        $services_other[] = $service;
    }
}
?>

<h2 class="mb-4">Available Services</h2>

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
    <button type="button" class="theme-toggle-btn" onclick="toggleTheme()">Switch Theme</button>
</form>

<?php if (count($services_in_pincode) > 0) { ?>
    <h4 class="mb-3">Services in Your Pincode (<?php echo $user_pincode; ?>)</h4>
    <div class="row">
        <?php foreach ($services_in_pincode as $service) {
            $images = json_decode($service['image'], true);
            $main_image = isset($images[0]) ? "../uploads/" . $images[0] : "../assets/default.png"; ?>

            <div class="col-md-4 mb-4">
                <div class="flip-card service-flip">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <img src="<?php echo $main_image; ?>" alt="Service Image" class="img-fluid" onerror="this.onerror=null;this.src='../assets/default.png';">
                            <h5 class="mt-3 text-center fw-bold"><?php echo $service['title']; ?></h5>
                        </div>
                        <div class="flip-card-back">
                            <span class="badge bg-primary mb-2"><?php echo $service['category_name']; ?></span>
                            <p><?php echo substr($service['description'], 0, 100) . "..."; ?></p>
                            <p><strong>Price:</strong> ₹<?php echo $service['price']; ?></p>
                            <p><strong>Vendor:</strong> <?php echo $service['vendor_name']; ?> (@<?php echo $service['vendor_username']; ?>)</p>
                            <a href="service_detail.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-service-detail me-2">View Details</a>
                            <a href="vendor_profile.php?vendor_id=<?php echo $service['vendor_id']; ?>" class="btn btn-sm btn-service-vendor">View Vendor</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<?php if (count($services_other) > 0) { ?>
    <h4 class="mb-3">Other Available Services</h4>
    <div class="row">
        <?php foreach ($services_other as $service) {
            $images = json_decode($service['image'], true);
            $main_image = isset($images[0]) ? "../uploads/" . $images[0] : "../assets/default.png"; ?>

            <div class="col-md-4 mb-4">
                <div class="flip-card service-flip">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <img src="<?php echo $main_image; ?>" alt="Service Image" class="img-fluid" onerror="this.onerror=null;this.src='../assets/default.png';">
                            <h5 class="mt-3 text-center fw-bold"><?php echo $service['title']; ?></h5>
                        </div>
                        <div class="flip-card-back">
                            <span class="badge bg-primary mb-2"><?php echo $service['category_name']; ?></span>
                            <p><?php echo substr($service['description'], 0, 100) . "..."; ?></p>
                            <p><strong>Price:</strong> ₹<?php echo $service['price']; ?></p>
                            <p><strong>Vendor:</strong> <?php echo $service['vendor_name']; ?> (@<?php echo $service['vendor_username']; ?>)</p>
                            <a href="service_detail.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-service-detail me-2">View Details</a>
                            <a href="vendor_profile.php?vendor_id=<?php echo $service['vendor_id']; ?>" class="btn btn-sm btn-service-vendor">View Vendor</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<?php if (count($services_in_pincode) == 0 && count($services_other) == 0) {
    echo "<div class='alert alert-info'><p class='text-muted'>No services available.</p></div>";
} ?>

<style>
    .me-2{
        margin: .5rem;
    }
    .service-flip {
        perspective: 1000px;
    }
    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 340px;
        transition: transform 0.8s;
        transform-style: preserve-3d;
    }
    .flip-card:hover .flip-card-inner,
    .flip-card:focus-within .flip-card-inner {
        transform: rotateY(180deg);
    }
    .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 1rem;
        overflow: hidden;
        padding: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #1f2937, #4b5563);
        color: #f8fafc;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .flip-card-back {
        transform: rotateY(180deg);
    }
    .flip-card-front img {
        height: 280px;
        width: 100%;
        object-fit: contain;
        border-radius: 0.75rem;
    }
    .flip-card-front h5 {
        margin-top: 1rem;
        font-weight: 600;
    }
    .btn-service-detail {
    background: linear-gradient(135deg, blueviolet, #7b2ff7);
    color: white;
    border: none;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-service-detail:hover {
    background: linear-gradient(135deg, #7b2ff7, blueviolet);
    transform: scale(1.05);
}

.btn-service-vendor {
    background-color: #00c29e;
    color: white;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 8px;
    border: none;
    transition: all 0.3s ease;
}

.btn-service-vendor:hover {
    background-color: #00a58a;
    transform: scale(1.05);
}
h2{
    text-align:center;
}
:root {
    --bg-gradient-dark: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    --bg-gradient-light: linear-gradient(135deg, #f8fafc, #e2e8f0, #cbd5e1);
    --text-color-dark: #ffffff;
    --text-color-light: #1e293b;
}

/* Body theme switch */
body[data-theme="dark"] {
    background: var(--bg-gradient-dark);
    color: var(--text-color-dark);
}
body[data-theme="light"] {
    background: var(--bg-gradient-light);
    color: var(--text-color-light);
}

/* Toggle button styling */
.theme-toggle-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #7b2ff7;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    font-weight: 500;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    cursor: pointer;
    z-index: 999;
    transition: background 0.3s ease;
}
.theme-toggle-btn:hover {
    background: #9a43f9;
}

/* Flip card adjustments for theme */
[data-theme="light"] .flip-card-front,
[data-theme="light"] .flip-card-back {
    background: var(--bg-gradient-light);
    color: var(--text-color-light);
}
[data-theme="dark"] .flip-card-front,
[data-theme="dark"] .flip-card-back {
    background: var(--bg-gradient-dark);
    color: var(--text-color-dark);
}

</style>
<script>
    // Save theme to localStorage
    function toggleTheme() {
        const current = document.body.getAttribute("data-theme") || "dark";
        const nextTheme = current === "dark" ? "light" : "dark";
        document.body.setAttribute("data-theme", nextTheme);
        localStorage.setItem("dashboard-theme", nextTheme);
    }

    // Load previously saved theme on page load
    window.onload = () => {
        const savedTheme = localStorage.getItem("dashboard-theme") || "dark";
        document.body.setAttribute("data-theme", savedTheme);
    };
</script>
