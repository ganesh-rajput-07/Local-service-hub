<?php
include('../../../../config/db_connect.php');
session_start();

if (!isset($_SESSION['reset_email'])) {
    die('Session expired. Please try again.');
}

$email = $_SESSION['reset_email'];

if (isset($_POST['reset_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $update = $conn->query("UPDATE admins SET password='$new_password' WHERE email='$email'");

    if ($update) {
        unset($_SESSION['reset_email']);
        echo "<script>alert('Password Reset Successful! Redirecting to login...'); window.location.href='admin_login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to reset password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="../../../../assets/lsh.ico" type="image/x-icon">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Set New Password</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST" class="card p-4 shadow rounded">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required placeholder="Enter new password">
                </div>
                <button type="submit" name="reset_password" class="btn btn-success w-100">Reset Password</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
