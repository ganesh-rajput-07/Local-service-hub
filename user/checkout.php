<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart   = $_SESSION['cart']   ?? [];
$amount = $_SESSION['total_amount'] ?? 0;

if (!$cart) { header('Location: dashboard.php'); exit(); }

/* ---------- Handle submission ---------- */
if (isset($_POST['proceed_to_payment'])) {
    $street   = mysqli_real_escape_string($conn, $_POST['street']);
    $building = mysqli_real_escape_string($conn, $_POST['building']);
    $city     = mysqli_real_escape_string($conn, $_POST['city']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $state    = mysqli_real_escape_string($conn, $_POST['state']);
    $country  = mysqli_real_escape_string($conn, $_POST['country']);
    $pincode  = mysqli_real_escape_string($conn, $_POST['pincode']);

    $full_address = "Street: $street, Building No: $building, City: $city, District: $district, State: $state, Country: $country, Pincode: $pincode";
    $_SESSION['full_address'] = $full_address;
    $_SESSION['payment_mode'] = $_POST['payment_mode'];

    if ($_POST['payment_mode'] === 'cash') {
        foreach ($cart as $item) {
            $sid  = $item['service_id'];
            $qty  = $item['quantity'];
            $conn->query("INSERT INTO bookings (user_id, vendor_id, service_id, quantity, total_amount, booking_status, payment_status, payment_mode, address)
                           VALUES ('$user_id', (SELECT vendor_id FROM services WHERE id = '$sid'), '$sid', '$qty', '$amount', 'pending', 'paid', 'cash', '$full_address')");
            $bid = $conn->insert_id;
            $conn->query("INSERT INTO payments (booking_id, amount, payment_mode) VALUES ('$bid', '$amount', 'cash')");
        }
        unset($_SESSION['cart'], $_SESSION['total_amount'], $_SESSION['full_address'], $_SESSION['payment_mode']);
        echo "<script>alert('Order placed successfully!'); window.location.href='my_bookings.php';</script>";
        exit();
    } else {
        header('Location: razorpay_payment.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Checkout | Local Service Hub</title>
    <link rel="shortcut icon" href="../image/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root[data-theme="dark"]  { --bg:#0f172a; --tx:#f1f5f9; --surf:#1e293b; --grad-from:#7f5cff; --grad-to:#d946ef; }
        :root[data-theme="light"] { --bg:#f8fafc; --tx:#1e293b; --surf:#ffffff; --grad-from:#6366f1; --grad-to:#ec4899; }
        body{margin:0;min-height:100vh;background:var(--bg);color:var(--tx);font-family:'Poppins',sans-serif;transition:.4s;display:flex;align-items:center;justify-content:center;}
        .wrapper{display:flex;gap:2rem;max-width:1100px;width:100%;padding:1rem;}
        @media(max-width:767.98px){.wrapper{flex-direction:column}.hero{order:-1;min-height:300px;}}
        .hero{flex:1 1 45%;background:center/cover no-repeat url('../image/ss.png');border-radius:1rem;min-height:520px;position:relative;overflow:hidden}
        .hero::before{content:"";position:absolute;inset:0;opacity:.25;mix-blend-mode:screen}
        .card-checkout{flex:1 1 50%;background:var(--surf);border-radius:1rem;padding:2.5rem 2rem;box-shadow:0 4px 30px rgba(0,0,0,.25);transition:.4s}
        .btn-gradient{background:linear-gradient(90deg,var(--grad-from),var(--grad-to));border:none;color:#fff;font-weight:600}
        .btn-gradient:hover{opacity:.9}
        .theme-toggle{position:fixed;top:1rem;right:1rem;width:42px;height:42px;border-radius:50%;border:none;background:var(--surf);color:var(--tx);display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:1000;transition:.4s}
        input,select,textarea{background:rgba(255,255,255,.05);border:1px solid #94a3b8;color:inherit;transition:.4s}
        [data-theme="light"] input,[data-theme="light"] select,[data-theme="light"] textarea{background:rgba(0,0,0,.03);border-color:#cbd5e1}
    </style>
</head>
<body>
<button id="themeToggle" class="theme-toggle" aria-label="toggle-theme"><i class="fa-solid fa-moon"></i></button>
<div class="wrapper">
    <div class="hero d-none d-md-block"></div>

    <div class="card-checkout">
        <h2 class="fw-bold mb-4 text-center">Checkout</h2>
        <h4 class="mb-4 text-center">Total Amount: ₹<?= $amount ?></h4>
        <form method="POST">
            <h5 class="mb-3">Delivery Address</h5>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label">Street</label><input type="text" name="street" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Building No</label><input type="text" name="building" class="form-control" required></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label">City</label><input type="text" name="city" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">District</label><input type="text" name="district" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">State</label><input type="text" name="state" class="form-control" required></div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><label class="form-label">Country</label><input type="text" name="country" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Pincode</label><input type="text" name="pincode" class="form-control" required></div>
            </div>
            <div class="mb-4">
                <label class="form-label">Payment Method</label>
                <select name="payment_mode" class="form-select" required>
                    <option value="cash">Cash After Service</option>
                    <option value="upi">UPI / Razorpay</option>
                </select>
            </div>
            <button type="submit" name="proceed_to_payment" class="btn btn-gradient w-100 py-2">Proceed to Payment</button>
            <a href="dashboard.php" class="btn btn-outline-secondary w-100 mt-3">Back to Services</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Theme persistence
  (function(){
    const saved=localStorage.getItem('lsh_theme');
    const prefersDark=window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme=saved?saved:(prefersDark?'dark':'light');
    document.documentElement.setAttribute('data-theme',theme);
    document.body.setAttribute('data-theme',theme);
  })();
  const toggle=document.getElementById('themeToggle');const icon=toggle.querySelector('i');
  function setIcon(t){icon.className = t==='dark'?'fa-solid fa-moon':'fa-solid fa-sun';}
  setIcon(document.body.getAttribute('data-theme'));
  toggle.onclick=()=>{const cur=document.body.getAttribute('data-theme');const nxt=cur==='dark'?'light':'dark';document.documentElement.setAttribute('data-theme',nxt);document.body.setAttribute('data-theme',nxt);localStorage.setItem('lsh_theme',nxt);setIcon(nxt);};
</script>
</body>
</html>
