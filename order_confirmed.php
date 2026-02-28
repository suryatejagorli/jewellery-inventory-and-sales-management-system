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
   FETCH ORDER SECURELY
========================= */
$stmt = mysqli_prepare($conn, "
    SELECT id, total_amount, payment_status, payment_method
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

/* =========================
   SECURITY CHECK (COD ONLY)
========================= */
if ($order['payment_method'] !== 'Cash') {
    header("Location: index.php");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<style>
.confirm-box {
    max-width: 700px;
    margin: auto;
    margin-top: 60px;
    background: #1a1a1a;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 0 25px rgba(0,0,0,0.5);
    text-align: center;
}

.amount {
    font-size: 26px;
    font-weight: bold;
    color: #d4af37;
}

.note {
    color: #bbb;
    margin-top: 15px;
}

.btn-receipt {
    background: #d4af37;
    color: #000;
    font-weight: 600;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
}

.btn-receipt:hover {
    background: #c19b2e;
}
</style>

<body class="text-light">

<div class="container py-5">
<div class="confirm-box">

<h2 class="text-success mb-3">✅ Order Confirmed</h2>

<p>Your order has been placed successfully.</p>

<hr class="border-secondary">

<h5>Amount To Be Paid On Delivery:</h5>

<div class="amount">
₹ <?= number_format($order['total_amount'], 2); ?>
</div>

<p class="note">
You will pay this amount in cash when the product is delivered.
</p>

<hr class="border-secondary">

<a href="receipt.php?id=<?= $order_id; ?>" class="btn-receipt">
🧾 View Order Receipt
</a>

<br><br>

<a href="shop.php" class="btn btn-outline-secondary">
Back to Shop
</a>

</div>
</div>

<?php include('includes/footer.php'); ?>
