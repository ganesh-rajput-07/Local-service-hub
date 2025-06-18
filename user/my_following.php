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
