<?php
session_start();
include('../config/db_connect.php');
include('../config/get_coordinates.php'); // Required for location

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "Add Service";
include('layout.php');

$vendor_id = $_SESSION['user_id'];
$categories = $conn->query("SELECT * FROM categories");

if (isset($_POST['add_service'])) {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
    $pincode = $_POST['pincode'];

    // Get Coordinates
    $coordinates = getCoordinates($pincode);
    $latitude = $coordinates['lat'];
    $longitude = $coordinates['lon'];

    // Multiple Image Upload
    $images = [];
    foreach ($_FILES['images']['name'] as $key => $image_name) {
        if (!empty($image_name)) {
            $tmp = $_FILES['images']['tmp_name'][$key];
            $unique_name = time() . '_' . rand(1000, 9999) . '_' . $image_name;
            move_uploaded_file($tmp, "../uploads/$unique_name");
            $images[] = $unique_name;
        }
    }

    // Check if at least one image is uploaded
    if (count($images) == 0) {
        echo "<script>alert('Please upload at least one image.');</script>";
    } else {
        $images_json = json_encode($images);

        $sql = "INSERT INTO services (vendor_id, category_id, title, description, price, image, keywords, average_rating, status, created_at, pincode, latitude, longitude) 
                VALUES ('$vendor_id', '$category_id', '$title', '$description', '$price', '$images_json', '$keywords', 0, 'active', NOW(), '$pincode', '$latitude', '$longitude')";

        if ($conn->query($sql)) {
            echo "<script>alert('Service Added Successfully!'); window.location.href='my_services.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to add service.');</script>";
        }
    }
}
?>

<h2 class="mb-4">Add New Service</h2>

<form method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-white">
    <!-- Category Selection -->
    <div class="mb-3">
        <label>Select Category:</label>
        <select name="category_id" class="form-control" required>
            <option value="">Select Category</option>
            <?php while ($cat = $categories->fetch_assoc()) { ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Service Title -->
    <div class="mb-3">
        <label>Service Title:</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <!-- Service Description -->
    <div class="mb-3">
        <label>Description:</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>

    <!-- Service Pincode -->
    <div class="mb-3">
        <label>Service Pincode</label>
        <input type="text" name="pincode" class="form-control" required placeholder="Enter service location pincode">
    </div>

    <!-- Service Price -->
    <div class="mb-3">
        <label>Price (₹):</label>
        <input type="number" name="price" class="form-control" required>
    </div>

    <!-- Service Keywords -->
    <div class="mb-3">
        <label>Keywords (Comma Separated):</label>
        <input type="text" name="keywords" class="form-control">
    </div>

    <!-- Service Images -->
    <div class="mb-3">
        <label>Upload Images (Multiple):</label>
        <input type="file" name="images[]" class="form-control" multiple required>
    </div>

    <button type="submit" name="add_service" class="btn btn-primary w-100">Add Service</button>
</form>

<a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
