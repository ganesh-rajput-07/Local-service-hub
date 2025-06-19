<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || !isset($_SESSION['total_amount'])) {
    header('Location: checkout.php');
    exit();
}

$total_amount = $_SESSION['total_amount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-box {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        #rzp-button {
            background-color: #1E3A8A;
            border: none;
            padding: 12px 25px;
            font-size: 18px;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        #rzp-button:hover {
            background-color: #153e75;
        }
    </style>
</head>
<body>

<div class="payment-box">
    <h2 class="mb-4">Razorpay Payment</h2>
    <h4 class="mb-4">Payable Amount: ₹<?php echo $total_amount; ?></h4>

    <button id="rzp-button">Pay Now with Razorpay</button>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "rzp_test_fRtqZZVid7nJAj", // Replace with your Razorpay Key ID
        "amount": <?php echo $total_amount * 100; ?>, // Amount in paise
        "currency": "INR",
        "name": "Local Service Hub",
        "description": "Service Payment",
        "handler": function (response) {
            window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id;
        },
        "modal": {
            "ondismiss": function () {
                alert('Payment cancelled by user.');
                window.location.href = "checkout.php";
            }
        },
        "theme": {
            "color": "#1E3A8A"
        }
    };

    var rzp1 = new Razorpay(options);

    document.getElementById('rzp-button').onclick = function (e) {
        rzp1.open();
        e.preventDefault();
    }
</script>

</body>
</html>
