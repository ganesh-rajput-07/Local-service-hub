<?php
session_start();
include('../config/db_connect.php');

date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['otp_email'])) {
    die('Session expired. Please register again.');
}

$email   = $_SESSION['otp_email'];
$message = '';

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];

    // check latest valid OTP
    $result = $conn->query("SELECT * FROM otp_verifications WHERE email='$email' AND otp_code='$otp' AND expiry_time > NOW() AND is_verified = 0");

    if ($result && $result->num_rows > 0) {
        // mark verified
        $conn->query("UPDATE otp_verifications SET is_verified = 1 WHERE email='$email' AND otp_code='$otp'");

        $data = $_SESSION['register_data'];

        if ($data['role'] === 'user') {
            $conn->query("INSERT INTO users (name, email, phone, password, pincode, latitude, longitude) VALUES (
                '{$data['name']}',
                '{$data['email']}',
                '{$data['phone']}',
                '{$data['password']}',
                '{$data['pincode']}',
                '{$data['latitude']}',
                '{$data['longitude']}'
            )");
        } elseif ($data['role'] === 'vendor') {
            $conn->query("INSERT INTO vendors (name, email, phone, password, pincode, latitude, longitude) VALUES (
                '{$data['name']}',
                '{$data['email']}',
                '{$data['phone']}',
                '{$data['password']}',
                '{$data['pincode']}',
                '{$data['latitude']}',
                '{$data['longitude']}'
            )");
        }

        unset($_SESSION['register_data']);
        unset($_SESSION['otp_email']);

        echo "<script>alert('Registration successful! Redirecting to login...'); window.location.href='login.php';</script>";
        exit();
    } else {
        $message = "Invalid or expired OTP! <a href='../otp/send_otp.php?email=$email&redirect=auth/verify_otp.php'>Resend OTP</a>";
    }
}
?>

<!DOCTYPE html>
<html lang=\"en\" data-theme=\"dark\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Verify OTP | Local Service Hub</title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css\" rel=\"stylesheet\">
    <link rel=\"shortcut icon\" href=\"../image/logo.png\" type=\"image/x-icon\">
    <style>
        :root {
            --clr-bg: #0f172a;
            --clr-surface: #1e293b;
            --clr-text: #f1f5f9;
            --clr-muted: #94a3b8;
            --clr-primary-from: #7f5cff;
            --clr-primary-to: #d946ef;
        }
        [data-theme='light'] {
            --clr-bg: #f8fafc;
            --clr-surface: #ffffff;
            --clr-text: #0f172a;
            --clr-muted: #64748b;
        }
        body {
            background: var(--clr-bg);
            color: var(--clr-text);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            transition: background-color .4s ease, color .4s ease;
        }
        .card {
            background: var(--clr-surface);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .25);
            width: 100%;
            max-width: 420px;
        }
        h2, .form-label { color: var(--clr-text); }
        .form-label { font-weight: 500; }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--clr-muted);
            color: var(--clr-text);
        }
        .form-control:focus {
            border-color: transparent;
            box-shadow: 0 0 0 .2rem rgba(124, 58, 237, .35);
        }
        .btn-primary {
            background: linear-gradient(90deg, var(--clr-primary-from), var(--clr-primary-to));
            border: none;
            font-weight: 600;
        }
        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: var(--clr-surface);
            color: var(--clr-text);
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color .4s ease;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <button id='themeToggle' class='theme-toggle' aria-label='Toggle Theme'>
        <i class='fa-solid fa-moon'></i>
    </button>

    <div class='card'>
        <h2 class='mb-4 text-center'>Verify OTP</h2>
        <?php if ($message) { echo "<div class='alert alert-danger text-center py-2'>$message</div>"; } ?>
        <form method='POST'>
            <div class='mb-3'>
                <label class='form-label'>Enter OTP</label>
                <input type='text' name='otp' class='form-control' placeholder='Enter OTP sent to your email' required>
            </div>
            <button type='submit' name='verify' class='btn btn-primary w-100'>Verify OTP</button>
            <div class='text-center mt-3'>
                <a href='../otp/send_otp.php?email=<?php echo $email; ?>&redirect=auth/verify_otp.php'>Resend OTP</a>
            </div>
        </form>
    </div>

    <script>
        (function(){
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initial = saved ? saved : (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', initial);
        })();

        const toggle  = document.getElementById('themeToggle');
        const icon    = toggle.querySelector('i');

        function updateIcon(theme){
            if(theme === 'dark'){
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }else{
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }

        toggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateIcon(next);
        });

        updateIcon(document.documentElement.getAttribute('data-theme'));
    </script>
</body>
</html>