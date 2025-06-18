<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified'])) {
    die('Unauthorized Access.');
}

$email = $_SESSION['reset_email'];

if (isset($_POST['reset_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    mysqli_query($conn, "UPDATE users SET password = '$new_password' WHERE email = '$email'");

    unset($_SESSION['reset_email']);
    unset($_SESSION['otp_verified']);

    echo "<script>alert('Password reset successfully!'); window.location.href='../auth/login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/lsh.ico" type="image/x-icon">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <form method="POST" class="p-4 border rounded bg-light" style="width: 400px;">
        <h3 class="mb-4 text-center">Reset Password</h3>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
        </div>
        <button type="submit" name="reset_password" class="btn btn-success w-100">Reset Password</button>
    </form>
</div>
</body>
</html>
