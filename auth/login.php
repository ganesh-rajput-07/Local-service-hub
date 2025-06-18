<?php
include('../config/db_connect.php');
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // user, vendor, admin

    if ($role == 'user') {
        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    } elseif ($role == 'vendor') {
        $result = $conn->query("SELECT * FROM vendors WHERE email='$email'");
    } elseif ($role == 'admin') {
        $result = $conn->query("SELECT * FROM admins WHERE email='$email'");
    } else {
        echo "Invalid role selected!";
        exit();
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $role;

            // Redirect to the correct dashboard
            if ($role == 'user') {
                header('Location: ../user/dashboard.php');
            } elseif ($role == 'vendor') {
                 $vendor_id = $_SESSION['user_id'];
    $vendor_result = mysqli_query($conn, "SELECT is_approved FROM vendors WHERE id = '$vendor_id'");
    $vendor = mysqli_fetch_assoc($vendor_result);

    if ($vendor['is_approved'] == 0) {
        header('Location: ../vendor/complete_profile_first.php'); // Force vendor to complete profile
        exit();
    } else {
        header('Location: ../vendor/dashboard.php');
        exit();
    }
            } elseif ($role == 'admin') {
                header('Location: ../admin/dashboard.php');
            }
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/lsh.ico" type="image/x-icon">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card p-4 shadow-sm" style="width: 400px;">
    <h2 class="text-center mb-4">Login</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Select Role</label>
            <select name="role" class="form-select" required>
                <option value="user">Customer</option>
                <option value="vendor">Vendor</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>
    <div class="text-center mt-3">
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <p>Forgot password? <a href="forgot_password.php">Reset Password</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

