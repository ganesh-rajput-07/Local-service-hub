<?php
session_start();
include('../config/db_connect.php');
include('../config/get_coordinates.php');

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // 'user' or 'vendor'
    $pincode = $_POST['pincode'];

    // Get coordinates from pincode
    $coordinates = getCoordinates($pincode);
    if ($coordinates) {
        $latitude = $coordinates['lat'];
        $longitude = $coordinates['lon'];
    } else {
        $latitude = '';
        $longitude = '';
    }

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.history.back();</script>";
    } else {
        // Insert temporary user with unverified OTP
        $conn->query("INSERT INTO otp_verifications (email, otp_code, expiry_time, is_verified) VALUES ('$email', '', NOW(), 0)");
        $_SESSION['register_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => $role,
            'pincode' => $pincode,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        header('Location: ../otp/send_otp.php?email=' . $email . '&redirect=auth/verify_otp.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Local Service Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/lsh.ico" type="image/x-icon">
    <style>
        body {
            background-color: #f1f1f1;
        }

        .form-container {
            margin-top: 80px;
        }

        .card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="container form-container">
        <h2 class="mb-4 text-center">User Registration</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" class="card">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter full name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required placeholder="Enter phone number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" class="form-control" required placeholder="Enter your area pincode">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="user">Customer</option>
                            <option value="vendor">Vendor</option>
                        </select>
                    </div>
                    <button type="submit" name="register" class="btn btn-success w-100">Register</button>
                </form>
                <div class="mt-3 text-center">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
