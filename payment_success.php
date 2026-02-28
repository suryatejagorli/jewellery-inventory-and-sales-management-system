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
   VERIFY ORDER STATUS
========================= */
$stmt = mysqli_prepare($conn, "
    SELECT id, payment_status
    FROM orders
    WHERE id = ?
");

mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

/* Ensure payment is actually completed */
if (
    strtolower($order['payment_status']) !== 'paid' &&
    strtolower($order['payment_status']) !== 'partially paid'
) {
    header("Location: payment.php?id=" . $order_id);
    exit();
}
?>

<?php include('includes/header.php'); ?>

<style>
body {
    background: linear-gradient(135deg, #000000, #1c1c1c);
    font-family: 'Segoe UI', sans-serif;
    animation: fadePage 0.4s ease;
}

/* Page Fade In */
@keyframes fadePage {
    from { opacity:0; }
    to { opacity:1; }
}

.success-box {
    max-width: 600px;
    margin: auto;
    margin-top: 80px;
    background: #111;
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 0 35px rgba(212,175,55,0.3);
    border: 1px solid #d4af37;
    text-align: center;
}

/* Animated Circle */
.checkmark-circle {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid #28a745;
    margin: 0 auto 25px auto;
    position: relative;
    animation: pop 0.4s ease-out forwards;
}

/* Checkmark */
.checkmark-circle::after {
    content: '';
    position: absolute;
    left: 30px;
    top: 48px;
    width: 28px;
    height: 55px;
    border-right: 6px solid #28a745;
    border-bottom: 6px solid #28a745;
    transform: rotate(45deg);
    animation: draw 0.6s ease forwards;
    animation-delay: 0.3s;
    opacity: 0;
}

@keyframes pop {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}

@keyframes draw {
    0% { height: 0; opacity: 0; }
    100% { height: 55px; opacity: 1; }
}

.success-title {
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 10px;
}

.success-text {
    font-size: 16px;
    color: #bbb;
}

.btn-receipt {
    background-color: #d4af37;
    color: #000;
    padding: 14px 28px;
    border-radius: 10px;
    font-size: 18px;
    font-weight: 600;
    margin-top: 25px;
    transition: 0.3s ease;
}

.btn-receipt:hover {
    background-color: #c19b2e;
    box-shadow: 0 0 15px #d4af37;
}

.btn-secondary {
    margin-top: 15px;
}
</style>

<body class="text-light">

<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="success-box">

<div class="checkmark-circle"></div>

<h2 class="success-title">Payment Confirmed!</h2>

<p class="success-text">
Your transaction has been successfully processed.
</p>

<p class="success-text">
You can now view and print your official receipt.
</p>

<a href="receipt.php?id=<?= $order_id; ?>" class="btn btn-receipt">
🧾 View / Print Receipt
</a>

<br>

<a href="shop.php" class="btn btn-outline-secondary">
Continue Shopping
</a>

</div>
</div>

<?php include('includes/footer.php'); ?>
