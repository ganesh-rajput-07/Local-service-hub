<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: admin_services.php');
    exit();
}

$service_id = $_GET['id'];

// Fetch current service details
$service_result = mysqli_query($conn, "SELECT * FROM services WHERE id='$service_id'");
$service = mysqli_fetch_assoc($service_result);

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM categories");

if (isset($_POST['update_service'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    $update = mysqli_query($conn, "UPDATE services SET title='$title', price='$price', category_id='$category_id' WHERE id='$service_id'");

    if ($update) {
        echo "<script>alert('Service updated successfully!'); window.location.href='admin_services.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update service.');</script>";
    }
}

$page_title = "Update Service";
include('admin_layout.php');
?>

<div class="container mt-4">
    <h2 class="mb-4">Update Service</h2>

    <form method="POST" class="p-4 border rounded bg-white">
        <div class="mb-3">
            <label>Service Title</label>
            <input type="text" name="title" class="form-control" required value="<?php echo $service['title']; ?>">
        </div>

        <div class="mb-3">
            <label>Price (₹)</label>
            <input type="number" name="price" class="form-control" required value="<?php echo $service['price']; ?>">
        </div>

        <div class="mb-3">
            <label>Select Category</label>
            <select name="category_id" class="form-control" required>
                <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $service['category_id']) echo 'selected'; ?>>
                        <?php echo $cat['title']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" name="update_service" class="btn btn-success w-100">Update Service</button>
    </form>

    <a href="admin_services.php" class="btn btn-secondary mt-3">Back to Services</a>
</div>

</div> <!-- Close flex-grow-1 from admin_layout -->
</div> <!-- Close d-flex from admin_layout -->
