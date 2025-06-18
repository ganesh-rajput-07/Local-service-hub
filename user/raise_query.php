<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

if (isset($_POST['submit_query'])) {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert Query
    $conn->query("INSERT INTO admin_queries (sender_id, sender_role, subject, message, status) VALUES ('$user_id', '$user_role', '$subject', '$message', 'Open')");
    $query_id = $conn->insert_id;

    // Add Query Message to Chat
    $conn->query("INSERT INTO chats (sender_id, sender_role, receiver_id, receiver_role, query_id, message) VALUES ('$user_id', '$user_role', '0', 'admin', '$query_id', '$message')");

    header('Location: chat.php?partner_id=0&partner_role=admin'); // Redirect to chat with admin
    exit();
}

$page_title = "Raise Query";
include('layout.php');
?>

<div class="container mt-5">
    <h2 class="mb-4">Raise Your Query</h2>
    <form method="POST" class="mx-auto" style="max-width: 500px;">
        <div class="form-group mb-3">
            <label for="subject">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
        </div>
        <button type="submit" name="submit_query" class="btn btn-primary">Submit Query</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
