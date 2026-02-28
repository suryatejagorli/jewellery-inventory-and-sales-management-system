<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE ORDER ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = (int) $_GET['id'];

if ($order_id <= 0) {
    die("Invalid Order ID.");
}

/* =========================
   FETCH ORDER SECURELY
========================= */
$order_stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE id = ?");
mysqli_stmt_bind_param($order_stmt, "i", $order_id);
mysqli_stmt_execute($order_stmt);
$order_result = mysqli_stmt_get_result($order_stmt);

if (!$order_result || mysqli_num_rows($order_result) === 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($order_result);
mysqli_stmt_close($order_stmt);

/* =========================
   REDIRECT TO QR IF NEEDED
========================= */
if (
    ($order['payment_method'] === 'UPI' || $order['payment_method'] === 'EMI') &&
    $order['payment_status'] === 'Unpaid'
) {
    header("Location: qr_payment.php?id=" . $order_id);
    exit();
}

/* =========================
   FETCH ORDER ITEMS
========================= */
$item_stmt = mysqli_prepare($conn, "
    SELECT 
        oi.quantity, 
        oi.price, 
        p.name, 
        p.weight,
        p.purity, 
        p.huid_code
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");

mysqli_stmt_bind_param($item_stmt, "i", $order_id);
mysqli_stmt_execute($item_stmt);
$items = mysqli_stmt_get_result($item_stmt);

/* =========================
   STATUS LOGIC
========================= */
$isPacked = ($order['order_status'] === 'Packed');
$isEmi    = ((int)$order['is_emi'] === 1);
$isCash   = ($order['payment_method'] === 'Cash');

/* =========================
   SAFE VALUES
========================= */
$subtotal        = (float)($order['subtotal'] ?? 0);
$gst_amount      = (float)($order['gst_amount'] ?? 0);
$delivery_charge = (float)($order['delivery_charge'] ?? 0);
$total_amount    = (float)($order['total_amount'] ?? 0);

$emiPaid      = (float)($order['emi_paid_amount'] ?? 0);
$emiRemaining = (float)($order['emi_remaining_amount'] ?? 0);
$emiMonths    = (int)($order['emi_months'] ?? 0);

/* =========================
   PAYMENT DISPLAY LOGIC
========================= */
if ($isCash) {
    $amountPaid = 0;
    $remainingBalance = $total_amount;
}
elseif ($isEmi) {
    $amountPaid = $emiPaid;
    $remainingBalance = $emiRemaining;
}
else {
    $amountPaid = $total_amount;
    $remainingBalance = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Receipt | SBJ Jewellery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#f5f6f8;
    font-family: 'Segoe UI', sans-serif;
}

.receipt-box {
    max-width: 900px;
    margin: 30px auto;
    background: #ffffff;
    padding: 35px;
    border-radius: 14px;
    box-shadow: 0 10px 35px rgba(0,0,0,0.08);
}

.receipt-header {
    text-align: center;
    margin-bottom: 15px;
}

.receipt-header h2 {
    font-weight: 700;
    letter-spacing: 1px;
}

.emi-box {
    background: #eef7ff;
    border: 1px solid #cde5ff;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 14px;
    margin-top: 10px;
}

.amount-paid {
    font-size: 18px;
    font-weight: 700;
    color: #198754;
}

.remaining {
    font-size: 16px;
    font-weight: 700;
    color: #dc3545;
}

@media print {
    body { background: #ffffff !important; }
    .no-print { display: none; }

    .receipt-box {
        box-shadow: none;
        border-radius: 0;
        margin: 0;
        padding: 20px;
    }

    @page {
        size: A4;
        margin: 12mm;
    }
}
</style>
</head>

<body>

<div class="receipt-box">

<div class="receipt-header">
    <h2>SBJ Jewellery</h2>
    <small>Official Purchase Receipt</small>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($order['address']); ?></p>
    </div>
    <div class="col-md-6">
        <p><strong>Order Date:</strong> <?= date("d M Y, h:i A", strtotime($order['order_date'])); ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']); ?></p>
        <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['payment_status']); ?></p>
    </div>
</div>

<?php if ($isEmi) { ?>
<div class="emi-box">
<strong>EMI Details:</strong><br>
Down Payment: ₹ <?= number_format($emiPaid,2); ?> |
Remaining: ₹ <?= number_format($emiRemaining,2); ?> |
Duration: <?= $emiMonths; ?> Months
</div>
<?php } ?>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>Product</th>
<th>Weight (g)</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
</tr>
</thead>
<tbody>

<?php while ($row = mysqli_fetch_assoc($items)) { 
    $quantity = (int)$row['quantity'];
    $price = (float)$row['price'];
    $line_total = $price * $quantity;

    $purityLabel = ($row['purity'] == '916') ? 'SBJ 916 HM' :
                   (($row['purity'] == '999') ? 'SBJ 999' : '');
?>

<tr>
<td>
<strong><?= htmlspecialchars($row['name']); ?></strong><br>
<small>
<?= $purityLabel; ?><br>
HUID: <?= htmlspecialchars($row['huid_code']); ?>
</small>
</td>
<td><?= htmlspecialchars($row['weight']); ?></td>
<td><?= $quantity; ?></td>
<td>₹ <?= number_format($price,2); ?></td>
<td>₹ <?= number_format($line_total,2); ?></td>
</tr>

<?php } ?>

</tbody>
</table>

<div class="row justify-content-end">
<div class="col-md-5">

<p class="d-flex justify-content-between">
<span>Subtotal:</span>
<span>₹ <?= number_format($subtotal,2); ?></span>
</p>

<p class="d-flex justify-content-between">
<span>GST:</span>
<span>₹ <?= number_format($gst_amount,2); ?></span>
</p>

<p class="d-flex justify-content-between">
<span>Delivery:</span>
<span>₹ <?= number_format($delivery_charge,2); ?></span>
</p>

<hr>

<p class="d-flex justify-content-between">
<strong>Total Order Value:</strong>
<strong>₹ <?= number_format($total_amount,2); ?></strong>
</p>

<p class="d-flex justify-content-between amount-paid">
<span>Amount Paid:</span>
<span>₹ <?= number_format($amountPaid,2); ?></span>
</p>

<?php if ($remainingBalance > 0) { ?>
<p class="d-flex justify-content-between remaining">
<span>Remaining Balance:</span>
<span>₹ <?= number_format($remainingBalance,2); ?></span>
</p>
<?php } ?>

</div>
</div>

<hr>

<div class="text-center mt-4 no-print">
<?php if ($isPacked) { ?>
<button onclick="window.print()" class="btn btn-dark px-4">🖨 Print Receipt</button>
<?php } else { ?>
<button class="btn btn-dark px-4" disabled>🔒 Print Locked</button>
<?php } ?>
<a href="order_history.php" class="btn btn-secondary ms-2 px-4">Back</a>
</div>

</div>

</body>
</html>
