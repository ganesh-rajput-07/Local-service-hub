<?php
include('../../../../config/db_connect.php'); // Path adjusted
date_default_timezone_set('Asia/Kolkata');
session_start();

if (!isset($_SESSION['otp_email'])) {
    die('Session expired. Please register again.');
}

$email = $_SESSION['otp_email'];
$message = '';

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];

    // ✅ Check latest OTP for this email
    $result = $conn->query("SELECT * FROM otp_verifications WHERE email='$email' AND otp_code='$otp' AND expiry_time > NOW()");

    if ($result->num_rows > 0) {
        // ✅ OTP Verified
        $conn->query("UPDATE otp_verifications SET is_verified=1 WHERE email='$email' AND otp_code='$otp'");

        $data = $_SESSION['admin_register'];

        $conn->query("INSERT INTO admins (name, email, password) VALUES ('{$data['name']}', '{$data['email']}', '{$data['password']}')");

        unset($_SESSION['admin_register']);
        unset($_SESSION['otp_email']);

        echo "<script>alert('Admin Registered Successfully! Redirecting to login...'); window.location.href='admin_login.php';</script>";
        exit();
    } else {
        $message = "Invalid or Expired OTP! <a href='../../../otp/send_otp.php?email=$email&redirect=admin_auth/admin/admin_verify_otp.php'>Resend OTP</a>";
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
    <style>
        body {
            background-color: #f1f1f1;
        }

        .form-container {
            margin-top: 80px;
        }

        .card {
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
    </style>
</head>

<body>

    <div class="container form-container">
        <h2 class="mb-4 text-center">Verify Admin OTP</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if ($message != '') { ?>
                    <div class="alert alert-danger text-center"><?php echo $message; ?></div>
                <?php } ?>
                <form method="POST" class="card">
                    <div class="mb-3">
                        <label class="form-label">Enter OTP</label>
                        <input type="text" name="otp" class="form-control" required placeholder="Enter OTP sent to your email">
                    </div>
                    <button type="submit" name="verify" class="btn btn-primary w-100">Verify OTP</button>
                    <div class="text-center mt-3">
                        <a href="../../../otp/send_otp.php?email=<?php echo $email; ?>&redirect=admin_auth/admin/admin_auth/admin_verify_otp.php">Resend OTP</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
