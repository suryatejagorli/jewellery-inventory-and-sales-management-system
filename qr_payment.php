<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE ORDER ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int) $_GET['id'];

if ($order_id <= 0) {
    header("Location: index.php");
    exit();
}

/* =========================
   FETCH ORDER
========================= */
$stmt = mysqli_prepare($conn, "
    SELECT total_amount, is_emi, emi_paid_amount, payment_status
    FROM orders
    WHERE id = ?
");

if (!$stmt) {
    die("Database error.");
}

mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $total_amount, $is_emi, $emi_paid_amount, $payment_status);

if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    die("Order not found.");
}

mysqli_stmt_close($stmt);

/* =========================
   IF ALREADY PAID
========================= */
if ($payment_status === 'Paid') {
    header("Location: processing.php?id=" . $order_id . "&source=desktop");
    exit();
}

/* =========================
   EMI AMOUNT LOGIC
========================= */
if ((int)$is_emi === 1) {
    $display_amount = (float)$emi_paid_amount;
} else {
    $display_amount = (float)$total_amount;
}

/* =========================
   QR LINK
========================= */
$qr_data = "https://sbjewels.page.gd/mobile_pay.php?id=" . $order_id;
$qr_image = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($qr_data);
?>
<!DOCTYPE html>
<html>
<head>
<title>UPI Payment | SBJ Jewellery</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    background: radial-gradient(circle at center, #1a1a1a, #000);
    font-family: 'Segoe UI', sans-serif;
    color: white;
}

.payment-box {
    max-width: 480px;
    margin: 70px auto;
    background: #111;
    border-radius: 25px;
    padding: 40px;
    text-align: center;
    border: 2px solid #d4af37;
    box-shadow: 0 0 60px rgba(212,175,55,0.6);
}

.upi-header {
    font-size: 22px;
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 10px;
}

.amount {
    font-size: 30px;
    font-weight: bold;
    color: #28a745;
    margin: 20px 0;
}

.qr-container {
    background: #fff;
    padding: 25px;
    border-radius: 25px;
    border: 6px solid #d4af37;
    display: inline-block;
}

.qr-container img {
    width: 220px;
}

.note {
    margin-top: 25px;
    font-size: 14px;
    color: #bbb;
}

.status {
    margin-top: 20px;
    font-size: 14px;
    color: #ccc;
}

.check-btn {
    margin-top: 25px;
    padding: 12px 25px;
    background: #28a745;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    cursor: pointer;
}

.check-btn:hover {
    background: #1e7e34;
}
</style>

<script>
function checkPayment() {
    fetch("check_payment.php?id=<?= $order_id ?>")
    .then(res => res.text())
    .then(data => {
        if (data.trim() === "PAID") {
            window.location.href = "processing.php?id=<?= $order_id ?>&source=desktop";
        } else {
            alert("Payment not completed yet.");
        }
    })
    .catch(() => {
        alert("Unable to check status. Please try again.");
    });
}
</script>

</head>

<body>

<div class="payment-box">

<div class="upi-header">
💎 SBJ Jewellery Payment
</div>

<p>Order ID: <?= $order_id; ?></p>

<div class="amount">
₹ <?= number_format($display_amount, 2); ?>
</div>

<div class="qr-container">
    <img src="<?= $qr_image; ?>" alt="Payment QR">
</div>

<div class="note">
Scan this QR using mobile to continue payment
</div>

</div>
<script>
setInterval(function() {
    fetch("check_payment.php?id=<?= $order_id ?>")
    .then(res => res.text())
    .then(data => {
        if (data.trim() === "PAID") {
            window.location.href = "processing.php?id=<?= $order_id ?>&source=desktop";
        }
    });
}, 3000);
</script>
</body>
</html>