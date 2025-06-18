<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'vendor') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "Notifications";
include('layout.php');

$vendor_id = $_SESSION['user_id'];

// Fetch notifications
$notifications = $conn->query("SELECT * FROM notifications WHERE vendor_id='$vendor_id' ORDER BY created_at DESC");

// Mark all as read
$conn->query("UPDATE notifications SET is_read = 1 WHERE vendor_id='$vendor_id' AND is_read = 0");
?>

<h2>Notifications</h2>

<?php
if ($notifications->num_rows > 0) {
    while ($note = $notifications->fetch_assoc()) {
        echo "<div class='alert alert-info mb-2'><strong>" . $note['title'] . ":</strong> " . $note['message'] . "</div>";
    }
} else {
    echo "<p class='text-muted'>No notifications.</p>";
}
?>

<a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>

