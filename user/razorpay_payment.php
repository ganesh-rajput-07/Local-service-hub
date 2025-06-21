<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || !isset($_SESSION['total_amount'])) {
    header('Location: checkout.php');
    exit();
}
$total_amount = $_SESSION['total_amount'];
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment | Local Service Hub</title>
    <link rel="shortcut icon" href="../image/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* ---- Root Themes ---- */
        :root[data-theme="dark"]  { --bg:#0f172a; --tx:#f1f5f9; --surf:#1e293b; --grad-from:#7f5cff; --grad-to:#d946ef; }
        :root[data-theme="light"] { --bg:#f8fafc; --tx:#1e293b; --surf:#ffffff; --grad-from:#6366f1; --grad-to:#ec4899; }
        body{margin:0;min-height:100vh;background:var(--bg);color:var(--tx);font-family:'Poppins',sans-serif;transition:.4s;display:flex;align-items:center;justify-content:center;}
        .wrapper{display:flex;gap:2rem;max-width:900px;width:100%;padding:1rem;}
        @media(max-width:767.98px){.wrapper{flex-direction:column}.hero{order:-1;min-height:300px;}}
        .hero{flex:1 1 45%;background:center/cover no-repeat url('../image/p.png');border-radius:1rem;min-height:500px;position:relative;overflow:hidden}
        .hero::before{content:"";position:absolute;inset:0;opacity:.25;mix-blend-mode:screen}
        .payment-card{flex:1 1 50%;background:var(--surf);border-radius:1rem;padding:2.5rem 2rem;box-shadow:0 4px 30px rgba(0,0,0,.25);text-align:center;transition:.4s}
        .btn-gradient{background:linear-gradient(90deg,var(--grad-from),var(--grad-to));border:none;color:#fff;font-weight:600;padding:12px 25px;font-size:1.1rem;border-radius:8px;transition:opacity .3s}
        .btn-gradient:hover{opacity:.9}
        .theme-toggle{position:fixed;top:1rem;right:1rem;width:42px;height:42px;border-radius:50%;border:none;background:var(--surf);color:var(--tx);display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:1000;transition:.4s}
    </style>
</head>
<body>
<button id="themeToggle" class="theme-toggle" aria-label="toggle-theme"><i class="fa-solid fa-moon"></i></button>
<div class="wrapper">
   <div class="hero d-none d-md-block"></div>
   <div class="payment-card">
       <h2 class="fw-bold mb-4">Razorpay Payment</h2>
       <h4 class="mb-5">Payable Amount: ₹<?= $total_amount ?></h4>
       <button id="rzp-button" class="btn-gradient w-100">Pay Now with Razorpay</button>
       <a href="checkout.php" class="btn btn-outline-secondary w-100 mt-3">Back to Checkout</a>
   </div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Theme persistence
  (function(){const s=localStorage.getItem('lsh_theme');const p=window.matchMedia('(prefers-color-scheme: dark)').matches;const t=s?s:(p?'dark':'light');document.documentElement.setAttribute('data-theme',t);document.body.setAttribute('data-theme',t);})();
  const tbtn=document.getElementById('themeToggle'), ic=tbtn.querySelector('i');
  function upd(th){ic.className = th==='dark'?'fa-solid fa-moon':'fa-solid fa-sun';}
  upd(document.body.getAttribute('data-theme'));
  tbtn.onclick=()=>{const c=document.body.getAttribute('data-theme');const n=c==='dark'?'light':'dark';document.documentElement.setAttribute('data-theme',n);document.body.setAttribute('data-theme',n);localStorage.setItem('lsh_theme',n);upd(n);};

  // Razorpay config
  var options={
    key:"rzp_test_TU9l1lhxUwKpq4",amount:<?= $total_amount*100 ?>,currency:"INR",
    name:"Local Service Hub",description:"Service Payment",
    handler:function (response){window.location.href="payment_success.php?payment_id="+response.razorpay_payment_id;},
    modal:{ondismiss:function(){alert('Payment cancelled by user.');window.location.href='checkout.php';}},
    theme:{ color: getComputedStyle(document.documentElement).getPropertyValue('--grad-from').trim() }
  };
  var rzp=new Razorpay(options);
  document.getElementById('rzp-button').onclick=function(e){rzp.open();e.preventDefault();}
</script>
</body>
</html>