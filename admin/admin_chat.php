<?php
session_start();
include('../config/db_connect.php');

// Session Check
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_auth/admin/admin_auth/admin_login.php');
    exit();
}

$my_id = $_SESSION['admin_id'];
$my_role = 'admin';

// Get Chat Partners: Users and Vendors
$chat_partners = $conn->query("SELECT id, name, 'user' AS role FROM users
                               UNION 
                               SELECT id, name, 'vendor' AS role FROM vendors
                               ORDER BY name ASC");

// Selected Partner
$partner_id = $_GET['partner_id'] ?? null;
$partner_role = $_GET['partner_role'] ?? null;
$messages = [];
$query_message = null;

if ($partner_id && $partner_role) {
    // Mark all messages as read
    mysqli_query($conn, "UPDATE chats SET is_read = 1 WHERE receiver_id = '$my_id' AND receiver_role = '$my_role' AND sender_id = '$partner_id' AND sender_role = '$partner_role' AND is_read = 0");

    // Check if partner has any query
    $query_result = $conn->query("SELECT * FROM admin_queries WHERE sender_id = '$partner_id' AND sender_role = '$partner_role' ORDER BY created_at DESC LIMIT 1");

    if ($query_result && $query_result->num_rows > 0) {
        $query_data = $query_result->fetch_assoc();
        $query_message = $query_data['message'];
        $query_time = $query_data['created_at'];
    }

    // Fetch Chat Messages
    $messages_result = $conn->query("SELECT * FROM chats WHERE 
        (sender_id = '$my_id' AND sender_role = '$my_role' AND receiver_id = '$partner_id' AND receiver_role = '$partner_role') 
        OR 
        (sender_id = '$partner_id' AND sender_role = '$partner_role' AND receiver_id = '$my_id' AND receiver_role = '$my_role') 
        ORDER BY sent_at ASC");

    while ($msg = $messages_result->fetch_assoc()) {
        $messages[] = $msg;
    }
}

// Send Message
if (isset($_POST['send_message']) && $partner_id && $partner_role) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $conn->query("INSERT INTO chats (sender_id, sender_role, receiver_id, receiver_role, message) VALUES 
        ('$my_id', '$my_role', '$partner_id', '$partner_role', '$message')");

    header("Location: admin_chat.php?partner_id=$partner_id&partner_role=$partner_role");
    exit();
}

$page_title = "Admin Chat";
include('admin_layout.php');
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
    .query-message {
        align-self: flex-start;
        text-align: left;
        background-color: #343a40;
        color: white;
        padding: 10px;
        border-radius: 15px;
        margin-bottom: 10px;
    }
</style>

<h2 class="mb-4">Admin Chat</h2>

<div class="row">
    <!-- Chat Partner List -->
    <div class="col-md-3">
        <h5>Users / Vendors</h5>
        <ul class="list-group">
            <?php while ($partner = $chat_partners->fetch_assoc()) { 
                $partner_id_temp = $partner['id'];
                $partner_role_temp = $partner['role'];

                $unread_result = mysqli_query($conn, "SELECT COUNT(*) AS unread_count FROM chats WHERE sender_id = '$partner_id_temp' AND sender_role = '$partner_role_temp' AND receiver_id = '$my_id' AND receiver_role = '$my_role' AND is_read = 0");
                $unread = mysqli_fetch_assoc($unread_result)['unread_count'];
            ?>
                <a href="admin_chat.php?partner_id=<?php echo $partner_id_temp; ?>&partner_role=<?php echo $partner_role_temp; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php if ($partner_id == $partner_id_temp && $partner_role == $partner_role_temp) echo 'active'; ?>">
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

                <!-- Show Query Message First -->
                <?php if ($query_message) { ?>
                    <div class="query-message">
                        <strong>Query Raised:</strong><br>
                        <?php echo htmlspecialchars($query_message); ?><br>
                        <small><?php echo date('d M Y H:i', strtotime($query_time)); ?></small>
                    </div>
                <?php } ?>

                <!-- Show Chat Messages -->
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
            <p class="text-muted">Select a user or vendor to start chatting.</p>
        <?php } ?>
    </div>
</div>

</div> <!-- Close flex-grow-1 from admin_layout -->
</div> <!-- Close d-flex from admin_layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
