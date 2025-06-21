<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified'])) {
    die('Unauthorized Access.');
}

$email = $_SESSION['reset_email'];

if (isset($_POST['reset_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    mysqli_query($conn, "UPDATE users SET password = '$new_password' WHERE email = '$email'");

    unset($_SESSION['reset_email']);
    unset($_SESSION['otp_verified']);

    echo "<script>alert('Password reset successfully!'); window.location.href='../auth/login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Local Service Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../image/logo.png" type="image/x-icon">
    <style>
        :root {
            --clr-bg: #0f172a;
            --clr-surface: #1e293b;
            --clr-text: #f1f5f9;
            --clr-muted: #94a3b8;
            --clr-primary-from: #7f5cff;
            --clr-primary-to: #d946ef;
        }
        [data-theme="light"] {
            --clr-bg: #f8fafc;
            --clr-surface: #ffffff;
            --clr-text: #0f172a;
            --clr-muted: #64748b;
        }
        body {
            background-color: var(--clr-bg);
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, .2);
            width: 100%;
            max-width: 420px;
            color: var(--clr-text);
        }
        h3, .form-label {
            color: var(--clr-text);
        }
        .form-label {
            font-weight: 500;
        }
        .form-control {
            background-color: rgba(255,255,255,0.05);
            color: var(--clr-text);
            border: 1px solid var(--clr-muted);
        }
        .form-control:focus {
            border-color: transparent;
            box-shadow: 0 0 0 .2rem rgba(124, 58, 237, 0.3);
        }
        .btn-success {
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
            z-index: 999;
        }
    </style>
</head>
<body>
    <button id="themeToggle" class="theme-toggle" aria-label="Toggle Theme">
        <i class="fa-solid fa-moon"></i>
    </button>

    <div class="card">
        <h3 class="mb-4 text-center">Reset Password</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
            </div>
            <button type="submit" name="reset_password" class="btn btn-success w-100">Reset Password</button>
        </form>
    </div>

    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved) {
                document.documentElement.setAttribute('data-theme', saved);
            } else {
                document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
            }
        })();

        const toggle = document.getElementById('themeToggle');
        const icon = toggle.querySelector('i');

        function updateIcon(theme) {
            if (theme === 'dark') {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
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
