<?php
include('../../../../config/db_connect.php');
date_default_timezone_set('Asia/Kolkata');
session_start();

if (!isset($_SESSION['reset_email'])) {
    die('Session expired. Please try again.');
}

$email = $_SESSION['reset_email'];
$message = '';

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];

    $result = $conn->query("SELECT * FROM otp_verifications WHERE email='$email' AND otp_code='$otp' AND expiry_time > NOW()");

    if ($result->num_rows > 0) {
        $conn->query("UPDATE otp_verifications SET is_verified=1 WHERE email='$email' AND otp_code='$otp'");
        header('Location: admin_set_new_password.php');
        exit();
    } else {
        $message = "Invalid or Expired OTP! <a href='../../../../otp/send_otp.php?email=$email&redirect=admin/admin_auth/admin_reset_otp_verify.php'>Resend OTP</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="../../../../assets/lsh.ico" type="image/x-icon">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Verify OTP</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if ($message != '') { ?>
                <div class="alert alert-danger text-center"><?php echo $message; ?></div>
            <?php } ?>
            <form method="POST" class="card p-4 shadow rounded">
                <div class="mb-3">
                    <label class="form-label">Enter OTP</label>
                    <input type="text" name="otp" class="form-control" required placeholder="Enter OTP sent to your email">
                </div>
                <button type="submit" name="verify" class="btn btn-primary w-100">Verify OTP</button>
                <div class="text-center mt-3">
                    <a href='../../../../otp/send_otp.php?email=<?php echo $email; ?>&redirect=admin/admin_auth/admin/admin_auth/admin_reset_otp_verify.php'>Resend OTP</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
