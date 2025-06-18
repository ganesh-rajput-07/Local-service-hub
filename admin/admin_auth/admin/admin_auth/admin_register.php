<?php
session_start();
include('../../../../config/db_connect.php');

if (isset($_POST['send_otp'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $_SESSION['admin_register'] = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'password' => $password
    ];

    header('Location: ../../../../otp/send_otp.php?email=' . $email . '&redirect=admin/admin_auth/admin/admin_auth/admin_verify_otp.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
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
        <h2 class="mb-4 text-center">Admin Registration</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" class="card">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required placeholder="Enter your phone number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter password">
                    </div>
                    <button type="submit" name="send_otp" class="btn btn-success w-100">Send OTP</button>
                </form>
            </div>
        </div>
    </div>
<div class="text-center mt-4">
    <a href="admin_login.php">Already have an account ?</a>
</div>
</body>

</html>
