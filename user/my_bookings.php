<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = "My Bookings";
include('layout.php');


$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT b.*, s.title FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.user_id='$user_id' ORDER BY b.id DESC");
?>
<button class="theme-toggle-btn" onclick="toggleTheme()">Switch Theme</button>
<div class="container mt-4 mb-5">
    <h2 class="mb-4">My Bookings</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-4">Back to Dashboard</a>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Booking Status</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>₹<?php echo $row['total_amount']; ?></td>
                            <td>
                                <?php
                                $status_class = 'bg-secondary';
                                if ($row['booking_status'] == 'confirmed') $status_class = 'bg-success';
                                elseif ($row['booking_status'] == 'cancelled') $status_class = 'bg-danger';
                                elseif ($row['booking_status'] == 'completed') $status_class = 'bg-primary';
                                ?>
                                <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['booking_status']); ?></span>
                            </td>
                            <td>
                                <?php
                                $payment_class = ($row['payment_status'] == 'paid') ? 'bg-success' : 'bg-warning text-dark';
                                ?>
                                <span class="badge <?php echo $payment_class; ?>"><?php echo ucfirst($row['payment_status']); ?></span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">No bookings found!</div>
    <?php } ?>
</div>
<style>
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
