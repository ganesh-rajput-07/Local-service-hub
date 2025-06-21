<?php
include('../config/db_connect.php');
session_start();

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role']; // user, vendor, admin

    switch ($role) {
        case 'user':
            $result = $conn->query("SELECT * FROM users WHERE email='$email'");
            break;
        case 'vendor':
            $result = $conn->query("SELECT * FROM vendors WHERE email='$email'");
            break;
        case 'admin':
            $result = $conn->query("SELECT * FROM admins WHERE email='$email'");
            break;
        default:
            echo "Invalid role selected!";
            exit();
    }

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $role;

            // Redirect based on role
            if ($role === 'user') {
                header('Location: ../user/index.php');
            } elseif ($role === 'vendor') {
                $vendor_id      = $_SESSION['user_id'];
                $vendor_result  = mysqli_query($conn, "SELECT is_approved FROM vendors WHERE id = '$vendor_id'");
                $vendor         = mysqli_fetch_assoc($vendor_result);
                if ($vendor && $vendor['is_approved'] == 0) {
                    header('Location: ../vendor/complete_profile_first.php');
                } else {
                    header('Location: ../vendor/dashboard.php');
                }
            } elseif ($role === 'admin') {
                header('Location: ../admin/dashboard.php');
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Local Service Hub</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
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
            --clr-bg: #f1f5f9;
            --clr-surface: #ffffff;
            --clr-text: #1e293b;
            --clr-muted: #475569;
        }
        body {
            background: var(--clr-bg);
            color: var(--clr-text);
            font-family: "Inter", sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color .4s ease, color .4s ease;
        }
        .card-wrapper {
            display: flex;
            gap: 2rem;
            width: 100%;
            max-width: 900px;
            align-items: stretch;
        }
        .hero {
            flex: 1 1 45%;
            background: center/cover no-repeat url('../image/l.png');
            border-radius: 1rem;
            min-height: 520px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1s forwards;
        }
        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            /* background: linear-gradient(130deg, var(--clr-primary-from), var(--clr-primary-to)); */
            opacity: .25;
            mix-blend-mode: screen;
        }
        .form-card {
            flex: 1 1 50%;
            background: var(--clr-surface);
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, .3);
            backdrop-filter: blur(6px);
            transition: background-color .4s ease;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-gradient {
            background: linear-gradient(90deg, var(--clr-primary-from), var(--clr-primary-to));
            border: none;
            color: #fff;
            font-weight: 600;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(124, 58, 237, .5);
        }
        .form-control, .form-select {
            background: rgba(255,255,255,.05);
            border: 1px solid var(--clr-muted);
            color: var(--clr-text);
            transition: background-color .4s ease, color .4s ease;
        }
        [data-theme="light"] .form-control,
        [data-theme="light"] .form-select {
            background: rgba(0,0,0,.03);
        }
        [data-theme="dark"] .form-select option {
            background: var(--clr-surface);
            color: var(--clr-text);
        }
        .form-control:focus, .form-select:focus {
            border-color: transparent;
            box-shadow: 0 0 0 .25rem rgba(124, 58, 237, .25);
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
            transition: background-color .4s ease, transform .2s ease;
            z-index: 1000;
        }
        .theme-toggle:hover {
            transform: rotate(15deg);
        }
        @media (max-width: 767.98px) {
            .card-wrapper {
                flex-direction: column;
            }
            .hero {
                order: -1;
                min-height: 300px;
            }
            .theme-toggle {
                top: .5rem;
                right: .5rem;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px);} 
            to   { opacity: 1; transform: translateY(0);} 
        }
    </style>
</head>
<body>
    <!-- Theme Switcher -->
    <button id="themeToggle" class="theme-toggle" aria-label="Toggle theme">
        <i class="fa-solid fa-moon"></i>
    </button>

    <div class="card-wrapper">
        <!-- Hero -->
        <div class="hero d-none d-md-block"></div>

        <!-- Form -->
        <div class="form-card">
            <h2 class="fw-bold mb-4 text-center">Login</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger py-2" role="alert"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter password">
                </div>
                <div class="mb-4">
                    <label class="form-label">Select Role</label>
                    <select name="role" class="form-select" required>
                        <option value="user">Customer</option>
                        <option value="vendor">Vendor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="login" class="btn btn-gradient w-100 py-2">Login</button>
            </form>
            <div class="text-center mt-3">
                <p>Don't have an account? <a href="register.php">Register</a></p>
                <p>Forgot password? <a href="forgot_password.php">Reset Password</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        const themeToggle = document.getElementById('themeToggle');
        const icon        = themeToggle.querySelector('i');
        function updateIcon(theme) {
            if (theme === 'dark') {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateIcon(next);
        });
        // Initial icon state
        updateIcon(document.documentElement.getAttribute('data-theme'));
    </script>
</body>
</html>