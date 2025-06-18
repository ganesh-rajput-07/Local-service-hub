<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');

    exit();
}

$queries = mysqli_query($conn, "SELECT * FROM admin_queries ORDER BY created_at DESC");

$page_title = "Customer & Vendor Queries";
include('admin_layout.php');
?>

<div class="container mt-4">
    <h2>Customer & Vendor Queries</h2>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Sender</th>
                <th>Role</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($query = mysqli_fetch_assoc($queries)) {
                $sender_id = $query['sender_id'];
                $role = $query['sender_role'];

                if ($role == 'user') {
                    $sender_data = mysqli_query($conn, "SELECT name, email FROM users WHERE id = '$sender_id'");
                } else {
                    $sender_data = mysqli_query($conn, "SELECT name, email FROM vendors WHERE id = '$sender_id'");
                }
                $sender = mysqli_fetch_assoc($sender_data);
            ?>
                <tr>
                    <td><?php echo $sender['name']; ?> (<?php echo $sender['email']; ?>)</td>
                    <td><?php echo ucfirst($role); ?></td>
                    <td><?php echo $query['subject']; ?></td>
                    <td><?php echo ucfirst($query['status']); ?></td>
                    <td>
                        <a href="admin_chat.php?query_id=<?php echo $query['id']; ?>" class="btn btn-primary btn-sm">View & Reply</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
