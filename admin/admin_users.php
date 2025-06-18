<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

include('../config/db_connect.php');
$page_title = "Manage Users";
include('admin_layout.php');

// ✅ Fetch All Users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<div class="container">
    <h2 class="mb-4">Manage Users</h2>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="user-table">
            <?php while ($user = mysqli_fetch_assoc($users)) { ?>
                <tr id="user-row-<?php echo $user['id']; ?>">
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td>
    <a href="update_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Update</a>
    <button class="btn btn-danger btn-sm delete-user" data-id="<?php echo $user['id']; ?>">Delete</button>
</td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div> <!-- Close flex-grow-1 from admin_layout -->
</div> <!-- Close d-flex from admin_layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('.delete-user').click(function () {
            let userId = $(this).data('id');

            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: 'delete_user.php',
                    type: 'POST',
                    data: { user_id: userId },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            $('#user-row-' + userId).remove();
                            alert('User deleted successfully!');
                        } else {
                            alert('Failed to delete user.');
                        }
                    },
                    error: function () {
                        alert('Error deleting user.');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
