<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php';

/* =========================
   VALIDATE ORDER ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int)$_GET['id'];

if ($order_id <= 0) {
    header("Location: index.php");
    exit();
}

/* =========================
   GET SOURCE (mobile / desktop)
========================= */
$source = $_GET['source'] ?? 'desktop';

/* =========================
   FETCH PAYMENT STATUS
========================= */
$stmt = mysqli_prepare($conn, "
    SELECT payment_status 
    FROM orders 
    WHERE id = ?
");

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $payment_status);

if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    die("Order not found.");
}

mysqli_stmt_close($stmt);

/* =========================
   IF NOT PAID → GO BACK
========================= */
if ($payment_status !== 'Paid' && $payment_status !== 'Partially Paid') {
    header("Location: payment.php?id=" . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Processing | SBJ Jewellery</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    background: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    font-family: 'Segoe UI', sans-serif;
    color: white;
}

.container {
    text-align: center;
}

.loader {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 6px solid rgba(212,175,55,0.2);
    border-top: 6px solid #d4af37;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    100% { transform: rotate(360deg); }
}

.tick-container {
    display: none;
    margin-top: 20px;
}

svg {
    width: 120px;
    height: 120px;
}

circle {
    fill: none;
    stroke: #28a745;
    stroke-width: 4;
}

path {
    fill: none;
    stroke: #28a745;
    stroke-width: 4;
    stroke-dasharray: 50;
    stroke-dashoffset: 50;
    animation: draw 0.6s ease forwards;
}

@keyframes draw {
    to { stroke-dashoffset: 0; }
}

h2 {
    margin-top: 25px;
    color: #d4af37;
}
</style>

<script>
setTimeout(function(){
    document.querySelector('.loader').style.display = 'none';
    document.querySelector('.tick-container').style.display = 'block';
}, 2500);

setTimeout(function(){
    <?php if ($source === 'desktop'): ?>
        window.location.href = "payment_success.php?id=<?= $order_id ?>";
    <?php else: ?>
        // Mobile stops here (no redirect)
    <?php endif; ?>
}, 4000);
</script>

</head>

<body>

<div class="container">

<div class="loader"></div>

<div class="tick-container">
<svg viewBox="0 0 52 52">
<circle cx="26" cy="26" r="25"/>
<path d="M14 27 l7 7 l16 -16"/>
</svg>
</div>

<h2>Confirming Payment...</h2>

</div>

</body>
</html>
