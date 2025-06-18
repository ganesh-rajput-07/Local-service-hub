<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: my_services.php');
    exit();
}

$page_title = "Edit Service (Multi-Image)";
include('layout.php');

$vendor_id = $_SESSION['user_id'];
$service_id = $_GET['id'];

$service_result = $conn->query("SELECT * FROM services WHERE id = '$service_id' AND vendor_id = '$vendor_id'");

if ($service_result->num_rows == 0) {
    echo "<script>alert('Service not available'); window.location.href='my_services.php';</script>";
    exit();
}

$service = $service_result->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories");
$existing_images = json_decode($service['image'], true);

if (isset($_POST['update_service'])) {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $keywords = $_POST['keywords'];
    $main_image_index = $_POST['main_image'] ?? 0;

    $new_images = $existing_images;

    // Upload new images if added
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $name) {
            $tmp = $_FILES['images']['tmp_name'][$key];
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $new_image_name = 'service_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            if (move_uploaded_file($tmp, "../uploads/$new_image_name")) {
                $new_images[] = $new_image_name;
            }
        }
    }

    // Set selected main image to index 0
    if (isset($new_images[$main_image_index])) {
        $main_image = $new_images[$main_image_index];
        unset($new_images[$main_image_index]);
        array_unshift($new_images, $main_image);
    }

    $images_json = json_encode(array_values($new_images));

    $conn->query("UPDATE services SET category_id='$category_id', title='$title', description='$description', price='$price', keywords='$keywords', image='$images_json' WHERE id='$service_id'");

    echo "<script>alert('Service Updated Successfully!'); window.location.href='my_services.php';</script>";
    exit();
}
?>

<h2 class="mb-4">Edit Service (Multi-Image)</h2>

<form method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-white">

    <!-- Category Selection -->
    <div class="mb-3">
        <label>Select Category:</label>
        <select name="category_id" class="form-control" required>
            <option value="">Select Category</option>
            <?php while ($cat = $categories->fetch_assoc()) { ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $service['category_id']) echo 'selected'; ?>>
                    <?php echo $cat['title']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Service Title -->
    <div class="mb-3">
        <label>Service Title:</label>
        <input type="text" name="title" class="form-control" value="<?php echo $service['title']; ?>" required>
    </div>

    <!-- Service Description -->
    <div class="mb-3">
        <label>Description:</label>
        <textarea name="description" class="form-control" required><?php echo $service['description']; ?></textarea>
    </div>

    <!-- Service Price -->
    <div class="mb-3">
        <label>Price (₹):</label>
        <input type="number" name="price" class="form-control" value="<?php echo $service['price']; ?>" required>
    </div>

    <!-- Service Keywords -->
    <div class="mb-3">
        <label>Keywords (Comma Separated):</label>
        <input type="text" name="keywords" class="form-control" value="<?php echo $service['keywords']; ?>">
    </div>

    <!-- Current Images -->
    <div class="mb-3">
        <label>Current Images (Select Main Image):</label><br>
        <?php foreach ($existing_images as $index => $img) { ?>
            <div class="form-check form-check-inline mb-2">
                <input class="form-check-input" type="radio" name="main_image" value="<?php echo $index; ?>" <?php if ($index == 0) echo 'checked'; ?>>
                <img src="../uploads/<?php echo $img; ?>" style="height: 100px; object-fit: cover; margin-left: 10px;">
            </div>
        <?php } ?>
    </div>

    <!-- Upload New Images -->
    <div class="mb-3">
        <label>Upload New Images (Optional):</label>
        <input type="file" name="images[]" class="form-control" multiple>
    </div>

    <button type="submit" name="update_service" class="btn btn-primary w-100">Update Service</button>
</form>

<a href="my_services.php" class="btn btn-secondary mt-4">Back to My Services</a>
