<?php
session_start();
if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    $_SESSION['reset_email'] = $email;

    header('Location: ../../../../otp/send_otp.php?email=' . $email . '&redirect=admin/admin_auth/admin/admin_auth/admin_reset_otp_verify.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../../../../assets/lsh.ico" type="image/x-icon">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Forgot Password (Admin)</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST" class="card p-4 shadow rounded">
                <div class="mb-3">
                    <label class="form-label">Enter Registered Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter your registered email">
                </div>
                <button type="submit" name="send_otp" class="btn btn-primary w-100">Send OTP</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
