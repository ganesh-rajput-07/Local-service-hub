<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['reset_email'])) {
    die('Session expired. Please request OTP again.');
}

$email = $_SESSION['reset_email'];

if (isset($_POST['verify_otp'])) {
    $otp = $_POST['otp'];

    $result = mysqli_query($conn, "SELECT * FROM otp_verifications WHERE email = '$email' AND otp_code = '$otp' AND expiry_time > NOW() AND is_verified = 0");

    if (mysqli_num_rows($result) > 0) {
        mysqli_query($conn, "UPDATE otp_verifications SET is_verified = 1 WHERE email = '$email' AND otp_code = '$otp'");
        $_SESSION['otp_verified'] = true;

        header('Location: reset_password.php');
        exit();
    } else {
        echo "<script>alert('Invalid or expired OTP!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/lsh.ico" type="image/x-icon">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <form method="POST" class="p-4 border rounded bg-light" style="width: 400px;">
        <h3 class="mb-4 text-center">Verify OTP</h3>
        <div class="mb-3">
            <label class="form-label">Enter OTP</label>
            <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
        </div>
        <button type="submit" name="verify_otp" class="btn btn-success w-100">Verify</button>
    </form>
</div>
</body>
</html>
