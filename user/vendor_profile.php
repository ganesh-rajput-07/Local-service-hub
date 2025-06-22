<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['vendor_id'])) {
    header('Location: dashboard.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$vendor_id = $_GET['vendor_id'];

$vendor_result = $conn->query("SELECT * FROM vendors WHERE id = '$vendor_id'");

if ($vendor_result->num_rows == 0) {
    echo "<script>alert('Vendor not found'); window.location.href='dashboard.php';</script>";
    exit();
}

$vendor = $vendor_result->fetch_assoc();

// Check if user is already following this vendor
$check_follow = $conn->query("SELECT * FROM vendor_followers WHERE vendor_id = '$vendor_id' AND user_id = '$user_id'");
$is_following = $check_follow->num_rows > 0;

// Follow/Unfollow action
if (isset($_GET['follow_action'])) {
    if ($_GET['follow_action'] == 'follow' && !$is_following) {
        $conn->query("INSERT INTO vendor_followers (vendor_id, user_id) VALUES ('$vendor_id', '$user_id')");
        header("Location: vendor_profile.php?vendor_id=$vendor_id");
        
        exit();
    }

    if ($_GET['follow_action'] == 'unfollow' && $is_following) {
        $conn->query("DELETE FROM vendor_followers WHERE vendor_id = '$vendor_id' AND user_id = '$user_id'");
        header("Location: vendor_profile.php?vendor_id=$vendor_id");
        exit();
    }
}

$page_title = "Vendor Profile";
include('layout.php');
?>

<h2 class="text-center mb-4">Vendor Profile</h2>

<div class="d-flex justify-content-center mb-4">
    <img src="../uploads/<?php echo $vendor['profile_image']; ?>" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #1E3A8A;">
</div>

<div class="card p-4 mb-4">
    <h4 class="mb-3 text-primary">Vendor Details</h4>
    <p><strong>Name:</strong> <?php echo $vendor['name']; ?></p>
    <p><strong>Username:</strong> @<?php echo $vendor['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $vendor['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $vendor['phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $vendor['address']; ?></p>
    <p><strong>Skills:</strong> <?php echo $vendor['skills']; ?></p>
    <p><strong>Description:</strong> <?php echo $vendor['description']; ?></p>
    <p><strong>Experience:</strong> <?php echo $vendor['experience']; ?></p>

    <h5 class="mt-4">Social Links</h5>
    <?php if (!empty($vendor['website'])) { ?><p><a href="<?php echo $vendor['website']; ?>" target="_blank" style='text-decoration: none;'><i class="fa fa-globe"></i> Website</a></p><?php } ?>
    <?php if (!empty($vendor['facebook'])) { ?><p><a href="<?php echo $vendor['facebook']; ?>" target="_blank" style='text-decoration: none;'><i class="fab fa-facebook"></i> Facebook</a></p><?php } ?>
    <?php if (!empty($vendor['instagram'])) { ?><p><a href="<?php echo $vendor['instagram']; ?>" target="_blank" style='text-decoration: none;'><i class="fab fa-instagram"></i> Instagram</a></p><?php } ?>
    <?php if (!empty($vendor['x'])) { ?><p><a href="<?php echo $vendor['x']; ?>" target="_blank" style='text-decoration: none;'><I class="fa fa-twitter"></I> X (Twitter)</a></p><?php } ?>
    <?php if (!empty($vendor['github'])) { ?><p><a href="<?php echo $vendor['github']; ?>" target="_blank" style='text-decoration: none;'> <I class="fa fa-github"></I> GitHub</a></p><?php } ?>

    <a href="vendor_profile.php?vendor_id=<?php echo $vendor_id; ?>&follow_action=<?php echo $is_following ? 'unfollow' : 'follow'; ?>" class="btn btn-<?php echo $is_following ? 'danger' : 'success'; ?> fw-bold mt-3">
        <?php echo $is_following ? 'Unfollow' : 'Follow'; ?>
    </a>
</div>

<script>
// Font Awesome CDN
let fontAwesome = document.createElement('link');
fontAwesome.href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css";
fontAwesome.rel = "stylesheet";
document.head.appendChild(fontAwesome);

// Bootstrap Icons (Optional, if you need elsewhere)
let bootstrapIcons = document.createElement('link');
bootstrapIcons.href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css";
bootstrapIcons.rel = "stylesheet";
document.head.appendChild(bootstrapIcons);
</script>

<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
<style>
body {
    background: var(--bg-color);
    color: var(--text-color);
    transition: background 0.3s ease, color 0.3s ease;
}

.card {
    background-color: var(--card-bg);
    color: var(--text-color);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}

.btn-success {
    background-color: #10b981;
    border: none;
}

.btn-danger {
    background-color: #ef4444;
    border: none;
}

.btn-secondary {
    background-color: #64748b;
    border: none;
}

.btn:hover {
    opacity: 0.9;
}

:root {
    --bg-color: #f8fafc;
    --text-color: #111827;
    --card-bg: #ffffff;
}

.dark-theme {
    --bg-color: #0f172a;
    --text-color: #f8fafc;
    --card-bg: #1e293b;
}

.theme-toggle {
    position: fixed;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-color);
}
</style>

<button class="theme-toggle" onclick="toggleTheme()">
    <i class="fa fa-moon" id="theme-icon"></i>
</button>

<script>
function toggleTheme() {
    document.body.classList.toggle('dark-theme');
    const icon = document.getElementById('theme-icon');
    if (document.body.classList.contains('dark-theme')) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}
</script>

<script>
// Ensure theme is preserved on reload (Optional)
window.addEventListener('DOMContentLoaded', () => {
    const isDark = localStorage.getItem('theme') === 'dark';
    if (isDark) {
        document.body.classList.add('dark-theme');
        document.getElementById('theme-icon').classList.replace('fa-moon', 'fa-sun');
    }
});

function toggleTheme() {
    const isDark = document.body.classList.toggle('dark-theme');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    const icon = document.getElementById('theme-icon');
    icon.classList.toggle('fa-sun', isDark);
    icon.classList.toggle('fa-moon', !isDark);
}
</script>
