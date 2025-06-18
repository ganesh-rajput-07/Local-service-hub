<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}
include('../config/db_connect.php');
$page_title = "Manage Categories";
include('admin_layout.php');

// Add Category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $conn->query("INSERT INTO categories (name) VALUES ('$category_name')");
    echo "<script>alert('Category Added Successfully'); window.location.href='manage_category.php';</script>";
}

// Fetch Categories
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>

<div class="container">
    <h2 class="mb-4">Manage Categories</h2>

    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="category_name" required class="form-control" placeholder="Enter Category Name">
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_category" class="btn btn-success">Add Category</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                <tr>
                    <td><?php echo $category['id']; ?></td>
                    <td><?php echo $category['title']; ?></td>
                    <td>
                        <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_category.php?id=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
