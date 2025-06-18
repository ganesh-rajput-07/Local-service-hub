<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');

    exit();
}
include('../config/db_connect.php');

$id = $_GET['id'];
$category = mysqli_fetch_assoc($conn->query("SELECT * FROM categories WHERE id='$id'"));

if (isset($_POST['update_category'])) {
    $new_name = $_POST['category_name'];
    $conn->query("UPDATE categories SET name='$new_name' WHERE id='$id'");
    echo "<script>alert('Category Updated Successfully'); window.location.href='manage_category.php';</script>";
}

$page_title = "Edit Category";
include('admin_layout.php');
?>

<div class="container">
    <h2 class="mb-4">Edit Category</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" name="category_name" value="<?php echo $category['title']; ?>" required class="form-control">
        </div>
        <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
        <a href="manage_category.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</div> <!-- Close flex-grow-1 from admin_layout -->
</div> <!-- Close d-flex from admin_layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
