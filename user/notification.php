<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch notifications
$notifications = $conn->query("SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC");

// Mark notifications as read
$conn->query("UPDATE notifications SET is_read = 1 WHERE user_id = '$user_id'");

$page_title = "Notifications";
include('layout.php');
?>

<h2 class="mb-4">My Notifications</h2>

<?php if ($notifications->num_rows > 0) { ?>
    <ul class="list-group">
        <?php while ($note = $notifications->fetch_assoc()) { ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <strong><?php echo $note['title']; ?>:</strong> <?php echo $note['message']; ?>
                    <br><small class="text-muted"><?php echo date('d M Y, H:i', strtotime($note['created_at'])); ?></small>
                </div>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p class="text-muted">You have no notifications.</p>
<?php } ?>

<a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>

