<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Unfollow Logic
if (isset($_GET['unfollow_id'])) {
    $vendor_id = $_GET['unfollow_id'];
    $conn->query("DELETE FROM vendor_followers WHERE user_id='$user_id' AND vendor_id='$vendor_id'");
    header('Location: my_following.php');
    exit();
}

// Fetch Followed Vendors
$result = $conn->query("SELECT v.id, v.name, v.username, v.profile_image, v.skills FROM vendor_followers vf JOIN vendors v ON vf.vendor_id = v.id WHERE vf.user_id = '$user_id' ORDER BY v.name ASC");

$page_title = "My Following";
include('layout.php');
?>
<!-- Theme Toggle Button -->
<button class="theme-toggle-btn" onclick="toggleTheme()">Switch Theme</button>

<div class="container mt-4">
    <h2 class="mb-4">Vendors You Follow</h2>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Skills</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($vendor = $result->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <img src="../uploads/<?php echo $vendor['profile_image']; ?>" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                            </td>
                            <td><?php echo $vendor['name']; ?> (@<?php echo $vendor['username']; ?>)</td>
                            <td><?php echo $vendor['skills']; ?></td>
                            <td>
                                <a href="vendor_profile.php?vendor_id=<?php echo $vendor['id']; ?>" class="btn btn-sm btn-primary mb-2">View Profile</a><br>
                                <a href="my_following.php?unfollow_id=<?php echo $vendor['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to unfollow this vendor?');">Unfollow</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">You are not following any vendors yet.</div>
        <a href="dashboard.php" class="btn btn-primary">Explore Vendors</a>
    <?php } ?>
</div>
<style>
    :root {
    --bg-gradient-dark: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    --bg-gradient-light: linear-gradient(135deg, #f8fafc, #e2e8f0, #cbd5e1);
    --text-dark: #ffffff;
    --text-light: #1e293b;
}

/* Body themes */
body[data-theme="dark"]  { background: var(--bg-gradient-dark); color: var(--text-dark);  }
body[data-theme="light"] { background: var(--bg-gradient-light); color: var(--text-light); }

/* Table colours */
[data-theme="dark"]  .table { color: var(--text-dark);  }
[data-theme="light"] .table { color: var(--text-light); }

/* Toggle button */
.theme-toggle-btn{
    position:fixed;
    bottom:20px; right:20px;
    background:#7b2ff7; color:#fff;
    padding:10px 20px; border:none;
    border-radius:30px; font-weight:500;
    box-shadow:0 0 10px rgba(0,0,0,.3);
    cursor:pointer; z-index:999;
    transition:background .3s;
}
.theme-toggle-btn:hover{ background:#9a43f9; }

</style>
<script>
function toggleTheme(){
    const current = document.body.getAttribute("data-theme") || "dark";
    const next    = current === "dark" ? "light" : "dark";
    document.body.setAttribute("data-theme", next);
    localStorage.setItem("following_theme", next);
}
window.onload = () => {
    const saved = localStorage.getItem("following_theme") || "dark";
    document.body.setAttribute("data-theme", saved);
};
</script>
