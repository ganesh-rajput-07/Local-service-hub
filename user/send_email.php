<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require '../vendor/autoload.php'; // Path adjust kar lena

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER']; // Apna Gmail ID
        $mail->Password   = $_ENV['SMTP_PASS']; // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email sender & receiver
        $mail->setFrom($_ENV['SMTP_USER'], 'Local Service Hub');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        // echo 'Email sent successfully';
    } catch (Exception $e) {
        // echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
