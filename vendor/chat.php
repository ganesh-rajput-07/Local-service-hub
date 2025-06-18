<?php
session_start();
include('../config/db_connect.php');

// Session Check
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header('Location: ../auth/login.php');
    exit();
}

$my_id = $_SESSION['user_id'];
$my_role = $_SESSION['user_role']; // 'user' or 'vendor'

// Chat Partner List
if ($my_role == 'user') {
    $chat_partners = $conn->query("SELECT DISTINCT v.id, v.name, 'vendor' AS role FROM bookings b JOIN vendors v ON b.vendor_id = v.id WHERE b.user_id = '$my_id'
                                   UNION 
                                   SELECT id, name, 'admin' AS role FROM admins");
} elseif ($my_role == 'vendor') {
    $chat_partners = $conn->query("SELECT DISTINCT u.id, u.name, 'user' AS role FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.vendor_id = '$my_id'
                                   UNION 
                                   SELECT id, name, 'admin' AS role FROM admins");
}

$partner_id = $_GET['partner_id'] ?? null;
$partner_role = $_GET['partner_role'] ?? null;
$messages = [];

if ($partner_id && $partner_role) {
    // Mark all messages as read
    mysqli_query($conn, "UPDATE chats SET is_read = 1 WHERE receiver_id = '$my_id' AND receiver_role = '$my_role' AND sender_id = '$partner_id' AND sender_role = '$partner_role' AND is_read = 0");

    $messages_result = $conn->query("SELECT * FROM chats WHERE 
        (sender_id = '$my_id' AND sender_role = '$my_role' AND receiver_id = '$partner_id' AND receiver_role = '$partner_role') 
        OR 
        (sender_id = '$partner_id' AND sender_role = '$partner_role' AND receiver_id = '$my_id' AND receiver_role = '$my_role') 
        ORDER BY sent_at ASC");

    while ($msg = $messages_result->fetch_assoc()) {
        $messages[] = $msg;
    }
}

if (isset($_POST['send_message']) && $partner_id && $partner_role) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $conn->query("INSERT INTO chats (sender_id, sender_role, receiver_id, receiver_role, message) VALUES ('$my_id', '$my_role', '$partner_id', '$partner_role', '$message')");

    header("Location: chat.php?partner_id=$partner_id&partner_role=$partner_role");
    exit();
}

$page_title = "Chat";
include('layout.php');
?>

<style>
    .chat-container {
        height: 500px;
        overflow-y: scroll;
        background-color: #f1f1f1;
        padding: 15px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
    }
    .message {
        max-width: 60%;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 15px;
        word-wrap: break-word;
    }
    .sent {
        align-self: flex-end;
        text-align: right;
        background-color: #28a745;
        color: white;
    }
    .received {
        align-self: flex-start;
        text-align: left;
        background-color: #fd7e14;
        color: white;
    }
</style>

<h2 class="mb-4">Chat</h2>

<div class="row">
    <!-- Chat Partner List -->
    <div class="col-md-3">
        <h5><?php echo ($my_role == 'user') ? 'Vendors / Admin' : 'Users / Admin'; ?></h5>
        <ul class="list-group">
            <?php while ($partner = $chat_partners->fetch_assoc()) { 
                $partner_id_temp = $partner['id'];
                $partner_role_temp = $partner['role'];

                $unread_result = mysqli_query($conn, "SELECT COUNT(*) AS unread_count FROM chats WHERE sender_id = '$partner_id_temp' AND sender_role = '$partner_role_temp' AND receiver_id = '$my_id' AND receiver_role = '$my_role' AND is_read = 0");
                $unread = mysqli_fetch_assoc($unread_result)['unread_count'];
            ?>
                <a href="chat.php?partner_id=<?php echo $partner_id_temp; ?>&partner_role=<?php echo $partner_role_temp; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php if ($partner_id == $partner_id_temp && $partner_role == $partner_role_temp) echo 'active'; ?>">
                    <?php echo $partner['name']; ?> (<?php echo ucfirst($partner['role']); ?>)
                    <?php if ($unread > 0) { ?>
                        <span class="badge bg-danger rounded-circle" style="width: 12px; height: 12px;">&nbsp;</span>
                    <?php } ?>
                </a>
            <?php } ?>
        </ul>
    </div>

    <!-- Chat Window -->
    <div class="col-md-9 d-flex flex-column">
        <?php if ($partner_id && $partner_role) { ?>
            <div class="chat-container mb-3">
                <?php foreach ($messages as $msg) { ?>
                    <div class="message <?php echo ($msg['sender_id'] == $my_id && $msg['sender_role'] == $my_role) ? 'sent' : 'received'; ?>">
                        <?php echo htmlspecialchars($msg['message']); ?><br>
                        <small class="text-light"><?php echo date('d M Y H:i', strtotime($msg['sent_at'])); ?></small>
                    </div>
                <?php } ?>
            </div>

            <form method="POST" class="d-flex">
                <input type="text" name="message" class="form-control me-2" placeholder="Type your message..." required>
                <button type="submit" name="send_message" class="btn btn-success">Send</button>
            </form>
        <?php } else { ?>
            <p class="text-muted">Select a partner to start chatting.</p>
        <?php } ?>
    </div>
</div>

</div> <!-- Close flex-grow-1 from layout -->
</div> <!-- Close d-flex from layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
