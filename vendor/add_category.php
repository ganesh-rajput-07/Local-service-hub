<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}
?>
<?php
$page_title = "Manage Categories";
include('layout.php');

// Add New Category
if (isset($_POST['add_category'])) {
    $title = $_POST['title'];
    $conn->query("INSERT INTO categories (title) VALUES ('$title')");
    echo "<script>alert('Category Added Successfully!'); window.location.href='add_category.php';</script>";
    exit();
}

// Delete Category
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM categories WHERE id='$delete_id'");
    echo "<script>alert('Category Deleted Successfully!'); window.location.href='add_category.php';</script>";
    exit();
}

// Fetch Categories
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>

<h2 class="mb-4">Manage Categories</h2>

<!-- Add Category Form -->
<form method="POST" class="mb-5 p-4 border rounded bg-white">
    <div class="mb-3">
        <label for="title" class="form-label">Category Name:</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <button type="submit" name="add_category" class="btn btn-primary w-100">Add Category</button>
</form>

<!-- Category List -->
<h4>Available Categories</h4>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>NO</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($categories->num_rows > 0) {
            $i = 1;
            while ($cat = $categories->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . $cat['title'] . "</td>";
                echo "<td><a href='add_category.php?delete_id=" . $cat['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this category?');\">Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='text-center text-muted'>No categories available.</td></tr>";
        }
        ?>
    </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>

