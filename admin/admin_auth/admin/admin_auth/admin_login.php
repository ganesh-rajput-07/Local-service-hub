<?php
session_start();
include('../../../../config/db_connect.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$email' AND password = '$password'");
    $admin = mysqli_fetch_assoc($query);

    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header('Location: ../../../admin_dashboard.php');
        exit();
    } else {
        echo "<script>alert('Invalid Credentials');</script>";
    }
}

$page_title = "Admin Login";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="shortcut icon" href="../../../../assets/lsh.ico" type="image/x-icon">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Admin Login</h2>
    <form method="POST" class="mx-auto mt-4" style="max-width: 400px;">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
    </form>
    <div class="text-center mt-4">
        <a href="admin_forgot_password.php">Forgot Password?</a> <br>
        <a href="admin_register.php">Register</a>
    </div>
</div>
</body>
</html>
