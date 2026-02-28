<?php
include 'db.php';

/* =========================
   VALIDATE ORDER ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Order ID missing.");
}

$order_id = (int) $_GET['id'];

if ($order_id <= 0) {
    die("Invalid Order ID.");
}

/* =========================
   DETECT SOURCE
========================= */
$source = isset($_GET['source']) ? $_GET['source'] : 'mobile';

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
   BLOCK ONLY FULLY PAID ORDERS
========================= */
if ($payment_status === 'Paid') {
    header("Location: processing.php?id={$order_id}&source={$source}");
    exit();
}

/* =========================
   DETERMINE AMOUNT & STATUS
========================= */
if ((int)$is_emi === 1) {
    $amount = (float)$emi_paid_amount;
    $new_status = 'Partially Paid';
} else {
    $amount = (float)$total_amount;
    $new_status = 'Paid';
}

$success = false;

/* =========================
   HANDLE PAYMENT UPDATE
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $update_stmt = mysqli_prepare($conn, "
        UPDATE orders 
        SET payment_status = ?,
            payment_time = NOW()
        WHERE id = ?
    ");

    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, "si", $new_status, $order_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Payment | SBJ Jewellery</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    background: #000;
    font-family: 'Segoe UI', sans-serif;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.phone {
    width: 380px;
    background: #111;
    border-radius: 30px;
    padding: 30px;
    box-shadow: 0 0 50px rgba(212,175,55,0.3);
    border: 2px solid #d4af37;
    text-align: center;
}
.header {
    font-size: 20px;
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 20px;
}
.amount {
    font-size: 32px;
    font-weight: bold;
    color: #28a745;
    margin-bottom: 20px;
}
.merchant {
    background: #1a1a1a;
    padding: 15px;
    border-radius: 15px;
    margin-bottom: 25px;
    border: 1px solid #333;
}
.pay-btn {
    background: #28a745;
    border: none;
    width: 100%;
    padding: 14px;
    font-size: 18px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
}
.loading {
    margin-top: 20px;
    font-size: 18px;
}
.tick {
    font-size: 80px;
    color: #28a745;
    animation: pop 0.6s ease forwards;
}
@keyframes pop {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}
.success-text {
    margin-top: 15px;
    font-size: 22px;
    font-weight: 600;
}
</style>

<?php if ($success): ?>
<script>
setTimeout(function() {
    window.location.href = "processing.php?id=<?= $order_id ?>&source=<?= $source ?>";
}, 2000);
</script>
<?php endif; ?>

</head>

<body>

<div class="phone">

<?php if ($success): ?>

    <div class="tick">✔</div>
    <div class="success-text">Payment Confirmed</div>

<?php else: ?>

    <div class="header">
        💎 SBJ Jewellery
    </div>

    <div class="amount">
        ₹ <?= number_format($amount, 2); ?>
    </div>

    <div class="merchant">
        <strong>UPI ID:</strong><br>
        sbjproject@upi<br><br>
        <strong>Order ID:</strong> <?= $order_id; ?>
    </div>

    <form method="post" id="payForm">
        <button type="submit" class="pay-btn">
            💳 Pay Now
        </button>
    </form>

    <div class="loading" id="loading" style="display:none;">
        Processing Payment<span id="dots"></span>
    </div>

<?php endif; ?>

</div>

<script>
const form = document.getElementById("payForm");
const loading = document.getElementById("loading");
const dots = document.getElementById("dots");

if (form) {
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        form.style.display = "none";
        loading.style.display = "block";

        let count = 0;
        const dotInterval = setInterval(() => {
            dots.innerHTML = ".".repeat((count % 3) + 1);
            count++;
        }, 500);

        setTimeout(() => {
            clearInterval(dotInterval);
            form.submit();
        }, 4000);
    });
}
</script>

</body>
</html>