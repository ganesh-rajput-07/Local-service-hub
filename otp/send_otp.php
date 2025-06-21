<?php
date_default_timezone_set('Asia/Kolkata');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include('../config/db_connect.php');
session_start();

if (!isset($_GET['email']) || !isset($_GET['redirect'])) {
    echo "Invalid Request!";
    exit();
}

$email = $_GET['email'];
$redirect = $_GET['redirect'];
$otp = rand(100000, 999999);
$expiry = date('Y-m-d H:i:s', strtotime('+45 minutes'));

// ✅ Fetch User Name
$user_result = $conn->query("SELECT name FROM users WHERE email = '$email'");
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $user_name = $user_data['name'];
} else {
    $user_name = 'User'; // Fallback name if not found
}

// ✅ Check if existing OTP is present
$result = $conn->query("SELECT * FROM otp_verifications WHERE email='$email'");

if ($result->num_rows > 0) {
    $conn->query("UPDATE otp_verifications SET otp_code='$otp', expiry_time='$expiry', is_verified=0 WHERE email='$email'");
} else {
    $conn->query("INSERT INTO otp_verifications (email, otp_code, expiry_time, is_verified) VALUES ('$email', '$otp', '$expiry', 0)");
}

$_SESSION['otp_email'] = $email;

// ✅ HTML Email Body
$otp_body = '
<div style="max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="https://i.ibb.co/Zz3b5N3y/logo.png" alt="Local Service Hub" width="100%">
    </div>
    <h2 style="color: #333;">Hello, ' . htmlspecialchars($user_name) . '</h2>
    <p style="color: #555;">Your One Time Password (OTP) for verification is:</p>
    <div style="font-size: 28px; font-weight: bold; color: #28a745; text-align: center; margin: 20px 0;">' . $otp . '</div>
    <p style="color: #555;">This OTP is valid for the next 45 minutes.</p>
    <p style="color: #555;">If you did not request this, please ignore this email.</p>
    <hr style="margin: 20px 0;">
    <p style="text-align: center; color: #888; font-size: 12px;">&copy; ' . date('Y') . ' Local Service Hub. All Rights Reserved.</p>
</div>
';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'krishimitrasai@gmail.com';  // Your Gmail
    $mail->Password = 'wguj tdqr dohj hist';                         // Your Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('krishimitrasai@gmail.com', 'Local Service Hub');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code - Local Service Hub';
    $mail->Body = $otp_body;

    $mail->send();

    header('Location: ../' . $redirect);
    exit();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
