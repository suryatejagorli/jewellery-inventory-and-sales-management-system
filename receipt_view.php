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
    die("Invalid Order ID.");
}

/* =========================
   FETCH ORDER SECURELY
========================= */
$order_stmt = mysqli_prepare($conn, "
    SELECT id, customer_name, phone, address, payment_method,
           order_status, order_date
    FROM orders
    WHERE id = ?
");

mysqli_stmt_bind_param($order_stmt, "i", $order_id);
mysqli_stmt_execute($order_stmt);
$order_result = mysqli_stmt_get_result($order_stmt);

if (!$order_result || mysqli_num_rows($order_result) === 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($order_result);
mysqli_stmt_close($order_stmt);

/* =========================
   STRICT PERMISSION CHECK
========================= */
$allowed_status = ['Package Prepared', 'Delivered'];

if (!in_array($order['order_status'], $allowed_status)) {
    echo "
    <div style='
        margin:120px auto;
        max-width:500px;
        text-align:center;
        font-family:Arial;
        color:#ffc107;
    '>
        <h3>⏳ Receipt Not Available</h3>
        <p>Your order is confirmed but not yet packed.</p>
        <p>Please wait until admin prepares the package.</p>
        <a href='order_history.php'>⬅ Back to Order History</a>
    </div>";
    exit();
}

/* =========================
   FETCH ORDER ITEMS SECURELY
========================= */
$item_stmt = mysqli_prepare($conn, "
    SELECT oi.quantity, oi.price, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");

mysqli_stmt_bind_param($item_stmt, "i", $order_id);
mysqli_stmt_execute($item_stmt);
$items_result = mysqli_stmt_get_result($item_stmt);
?>

<?php include('includes/header.php'); ?>

<style>
@media print {
    .no-print { display: none; }
}

.receipt-card {
    background:#000;
    border:1px solid #333;
}
</style>

<body class="text-light">

<div class="container py-5">
<div class="card receipt-card shadow">
<div class="card-body">

<h3 class="text-center fw-bold text-warning">SBJ Jewellery</h3>
<p class="text-center text-muted">Purchase Receipt</p>

<hr class="border-secondary">

<p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>
<p><strong>Address:</strong> <?= htmlspecialchars($order['address']); ?></p>

<p><strong>Payment Method:</strong>
    <?= htmlspecialchars($order['payment_method']); ?>
</p>

<p><strong>Order Status:</strong>
    <span class="text-success fw-bold">
        <?= htmlspecialchars($order['order_status']); ?>
    </span>
</p>

<p><strong>Order Date:</strong>
    <?= date("d M Y, h:i A", strtotime($order['order_date'])); ?>
</p>

<table class="table table-bordered table-dark mt-4">
<thead>
<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
</tr>
</thead>

<tbody>
<?php
$grand_total = 0;

while ($row = mysqli_fetch_assoc($items_result)) {

    $quantity = (int)$row['quantity'];
    $price    = (float)$row['price'];
    $line_total = $price * $quantity;
    $grand_total += $line_total;
?>
<tr>
<td><?= htmlspecialchars($row['name']); ?></td>
<td><?= $quantity; ?></td>
<td>₹ <?= number_format($price, 2); ?></td>
<td>₹ <?= number_format($line_total, 2); ?></td>
</tr>
<?php } ?>

</tbody>

<tfoot>
<tr>
<th colspan="3" class="text-end">Grand Total</th>
<th>₹ <?= number_format($grand_total, 2); ?></th>
</tr>
</tfoot>
</table>

<p class="text-center mt-3 fw-bold text-warning">
Thank you for shopping with us 💛
</p>

<div class="text-center mt-3 no-print">
<button onclick="window.print()" class="btn btn-outline-light">
🖨 Print Receipt
</button>

<a href="order_history.php" class="btn btn-secondary ms-2">
⬅ Back to History
</a>
</div>

</div>
</div>
</div>

<?php include('includes/footer.php'); ?>
