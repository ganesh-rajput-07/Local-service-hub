<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

if (isset($_POST['submit_query'])) {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert new query
    $conn->query("INSERT INTO admin_queries (sender_id, sender_role, subject, message, status) VALUES ('$user_id', '$user_role', '$subject', '$message', 'Open')");
    $query_id = $conn->insert_id;

    // First chat message to admin (id 0)
    $conn->query("INSERT INTO chats (sender_id, sender_role, receiver_id, receiver_role, query_id, message) VALUES ('$user_id', '$user_role', '0', 'admin', '$query_id', '$message')");

    header('Location: chat.php?partner_id=0&partner_role=admin');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raise Query | Local Service Hub</title>
    <link rel="shortcut icon" href="../image/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
          --clr-bg-dark: #0f172a;
          --clr-surface-dark: #1e293b;
          --clr-text-dark: #f1f5f9;
          --clr-bg-light: #f8fafc;
          --clr-surface-light: #ffffff;
          --clr-text-light: #1e293b;
          --clr-primary-from: #7f5cff; /* violet */
          --clr-primary-to:   #d946ef; /* pink */
        }

        /* ---- BODY ---- */
        body {
          margin: 0;
          min-height: 100vh;
          background: var(--clr-bg-dark);
          color: var(--clr-text-dark);
          display: flex;
          align-items: center;
          justify-content: center;
          font-family: "Inter", sans-serif;
          transition: background-color .4s ease, color .4s ease;
        }
        [data-theme="light"] body,
        body[data-theme="light"] {
          background: var(--clr-bg-light);
          color: var(--clr-text-light);
        }

        /* ---- LAYOUT WRAPPER ---- */
        .query-wrapper {
          display: flex;
          gap: 2rem;
          width: 100%;
          max-width: 1000px;
          padding: 1rem;
        }
        @media (max-width: 767.98px) {
          .query-wrapper { flex-direction: column; }
          .hero { order: -1; min-height: 300px; }
        }

        /* ---- HERO IMAGE ---- */
        .hero {
          flex: 1 1 45%;
          background: center/cover no-repeat url('../image/s.png');
          border-radius: 1rem;
          min-height: 520px;
          position: relative;
          overflow: hidden;
        }
        .hero::before {
          content: "";
          position: absolute;
          inset: 0;
          /* background: linear-gradient(130deg,var(--clr-primary-from),var(--clr-primary-to)); */
          opacity: .25;
          mix-blend-mode: screen;
        }

        /* ---- FORM CARD ---- */
        .form-card {
          flex: 1 1 50%;
          background: var(--clr-surface-dark);
          border-radius: 1rem;
          padding: 2.5rem 2rem;
          box-shadow: 0 4px 30px rgba(0,0,0,.3);
          transition: background-color .4s ease;
        }
        [data-theme="light"] .form-card,
        body[data-theme="light"] .form-card {
          background: var(--clr-surface-light);
        }
        .form-control, .form-label, .form-card h2, textarea {
          transition: color .4s ease, background-color .4s ease;
        }
        .form-control, textarea {
          background: rgba(255,255,255,.05);
          border: 1px solid #94a3b8;
          color: inherit;
        }
        [data-theme="light"] .form-control,
        [data-theme="light"] textarea {
          background: rgba(0,0,0,.03);
          border-color: #cbd5e1;
        }
        .btn-gradient {
          background: linear-gradient(90deg,var(--clr-primary-from),var(--clr-primary-to));
          border: none; color:#fff; font-weight:600;
          transition: transform .2s ease, box-shadow .2s ease;
        }
        .btn-gradient:hover { transform:translateY(-2px); box-shadow: 0 8px 20px rgba(124,58,237,.5);}  

        /* ---- THEME TOGGLE ---- */
        .theme-toggle {
          position: fixed;
          top: 1rem; right: 1rem;
          background: var(--clr-surface-dark);
          color: var(--clr-text-dark);
          border:none; width:42px; height:42px; border-radius:50%;
          display:flex; align-items:center; justify-content:center;
          cursor:pointer; z-index:1000;
          transition: background-color .4s ease;
        }
        [data-theme="light"] .theme-toggle { background: var(--clr-surface-light); color: var(--clr-text-light); }
    </style>
</head>
<body>
    <!-- Theme Toggle Btn -->
    <button id="themeToggle" class="theme-toggle" aria-label="Toggle theme"><i class="fa-solid fa-moon"></i></button>

    <div class="query-wrapper">
        <!-- Hero Illustration -->
        <div class="hero d-none d-md-block"></div>

        <!-- Query Form -->
        <div class="form-card">
            <h2 class="fw-bold mb-4 text-center">Raise Your Query</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label" for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="message">Message</label>
                    <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
                </div>
                <button type="submit" name="submit_query" class="btn btn-gradient w-100 py-2">Submit Query</button>
            </form>
                     <button class="btn btn-gradient w-40 py-2" style="margin-top:5px;"> <a href="index.php" style="color:white">Back</a></button>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme persistence
        (function(){
            const saved = localStorage.getItem('lsh_theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const cur = saved ? saved : (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', cur);
            document.body.setAttribute('data-theme', cur);
        })();

        const toggleBtn = document.getElementById('themeToggle');
        const icon = toggleBtn.querySelector('i');

        function updateIcon(theme){
            if(theme==='dark'){ icon.classList.replace('fa-sun','fa-moon'); }
            else { icon.classList.replace('fa-moon','fa-sun'); }
        }

        toggleBtn.addEventListener('click',()=>{
            const curr = document.body.getAttribute('data-theme');
            const next = curr==='dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            document.body.setAttribute('data-theme', next);
            localStorage.setItem('lsh_theme', next);
            updateIcon(next);
        });

        updateIcon(document.body.getAttribute('data-theme'));
    </script>
</body>
</html>
