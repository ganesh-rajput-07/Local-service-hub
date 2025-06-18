<?php
session_start();
include('../config/db_connect.php');

$query_id = $_GET['query_id'];
$admin_id = $_SESSION['admin_id'];

$messages_result = $conn->query("SELECT * FROM chats WHERE query_id = '$query_id' ORDER BY sent_at ASC");

while ($msg = $messages_result->fetch_assoc()) {
    if ($msg['sender_role'] == 'admin') {
        echo '<div class="message sent">' . htmlspecialchars($msg['message']) . '<br><small class="text-light">' . date('d M Y H:i', strtotime($msg['sent_at'])) . '</small></div>';
    } else {
        echo '<div class="message received">' . htmlspecialchars($msg['message']) . '<br><small class="text-light">' . date('d M Y H:i', strtotime($msg['sent_at'])) . '</small></div>';
    }
}
?>
