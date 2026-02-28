<?php
include 'db.php';

/* =========================
   DISABLE ERROR OUTPUT
========================= */
error_reporting(0);

/* =========================
   VALIDATE ORDER ID
========================= */
if (!isset($_GET['id'])) {
    echo "WAIT";
    exit();
}

$order_id = intval($_GET['id']);

if ($order_id <= 0) {
    echo "WAIT";
    exit();
}

/* =========================
   FETCH PAYMENT STATUS
========================= */
$stmt = mysqli_prepare($conn, "SELECT payment_status FROM orders WHERE id = ?");

if (!$stmt) {
    echo "WAIT";
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $order_id);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    echo "WAIT";
    exit();
}

mysqli_stmt_bind_result($stmt, $payment_status);

if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    echo "WAIT";
    exit();
}

mysqli_stmt_close($stmt);

/* =========================
   RETURN STATUS
========================= */
if ($payment_status === 'Paid' || $payment_status === 'Partially Paid') {
    echo "PAID";
} else {
    echo "WAIT";
}
exit();
?>