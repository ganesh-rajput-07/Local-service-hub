<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    header('Location: ../auth/login.php');
    exit();
}

$my_id = $_SESSION['user_id'];
$my_role = $_SESSION['user_role'];

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
:root {
    --chat-bg: linear-gradient(135deg, #5842bf, #715bf6);
    --chat-text-color: #fff;
}

body.dark-mode {
    --chat-bg: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    --chat-text-color: #f1f1f1;
    background-color: #121212;
}

.chat-container {
    height: 500px;
    overflow-y: scroll;
    padding: 15px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    background: var(--chat-bg);
    color: var(--chat-text-color);
}

.message {
    max-width: 75%;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 15px;
    word-wrap: break-word;
    position: relative;
    background-color: white;
    color: black;
}

.sent {
    align-self: flex-end;
    background-color: #fff;
    color: #000;
    border-top-right-radius: 0;
}

.received {
    align-self: flex-start;
    background-color: #5e48e8;
    color: #fff;
    border-top-left-radius: 0;
}

.chat-sidebar {
    height: 100%;
    overflow-y: auto;
    background-color: #f8f9fa;
    padding: 10px;
    border-right: 1px solid #ddd;
}

@media (max-width: 768px) {
    .chat-sidebar {
        display: block !important;
        margin-bottom: 15px;
    }
}


:root {
    --bg-dark: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    --bg-light: linear-gradient(135deg, #f1f5f9, #e2e8f0, #cbd5e1);
    --text-dark: #ffffff;
    --text-light: #1e293b;
}

/* THEME STYLES */
body[data-theme='dark'] {
    background: var(--bg-dark);
    color: var(--text-dark);
}
body[data-theme='light'] {
    background: var(--bg-light);
    color: var(--text-light);
}

.theme-toggle-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #7b2ff7;
    color: white;
    border: none;
    padding: 10px 20px;
    font-weight: 500;
    border-radius: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    z-index: 1000;
    transition: background 0.3s ease;
}
.theme-toggle-btn:hover {
    background: #9a43f9;
}

/* TABLE ADAPTATION */
body[data-theme='dark'] table {
    background-color: rgba(255,255,255,0.05);
    color: #f8f9fa;
}
body[data-theme='dark'] .table-dark {
    background-color: #1f2937 !important;
}
body[data-theme='dark'] .table-bordered td,
body[data-theme='dark'] .table-bordered th {
    border-color: rgba(255,255,255,0.2);
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
}


</style>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <h2 class="mb-2">Chat</h2>
    <button class="theme-toggle-btn" onclick="toggleTheme()">Switch Theme</button>

</div>


<div class="row">
    <div class="col-md-3 chat-sidebar">
        <h5 style="color:black;"><?php echo ($my_role == 'user') ? 'Vendors / Admin' : 'Users / Admin'; ?></h5>
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

    <div class="col-md-9 d-flex flex-column">
        <?php if ($partner_id && $partner_role) { ?>
            <div class="chat-container mb-3" id="chatBox">
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
            <p class="text-muted" style="color: green;background: aliceblue;">Select a partner to start chatting.</p>
        <?php } ?>
    </div>
</div>

<script>
function toggleTheme() {
    document.body.classList.toggle('dark-mode');
}

window.onload = function () {
    var chatBox = document.getElementById("chatBox");
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
}
</script>
<script>
    function toggleTheme() {
        const body = document.body;
        const current = body.getAttribute("data-theme") || "light";
        const next = current === "dark" ? "light" : "dark";
        body.setAttribute("data-theme", next);
        localStorage.setItem("booking-theme", next);
    }

    // Load saved theme
    window.onload = () => {
        const saved = localStorage.getItem("booking-theme") || "light";
        document.body.setAttribute("data-theme", saved);
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
