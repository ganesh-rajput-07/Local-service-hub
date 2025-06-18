<?php
session_start();
include('../config/db_connect.php');

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    $user_result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($user_result) > 0) {
        $otp = rand(100000, 999999);
        $expiry_time = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        mysqli_query($conn, "INSERT INTO otp_verifications (email, otp_code, expiry_time, is_verified) VALUES ('$email', '$otp', '$expiry_time', 0)");

       header("Location: ../otp/send_otp.php?email=$email&otp=$otp&redirect=auth/verify_otp_reset.php");
        $_SESSION['reset_email'] = $email;
        exit();
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/lsh.ico" type="image/x-icon">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <form method="POST" class="p-4 border rounded bg-light" style="width: 400px;">
        <h3 class="mb-4 text-center">Forgot Password</h3>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <button type="submit" name="send_otp" class="btn btn-primary w-100">Send OTP</button>
    </form>
</div>
</body>
</html>
