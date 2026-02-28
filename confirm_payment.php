<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE REQUEST METHOD
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: shop.php");
    exit();
}

/* =========================
   VALIDATE ORDER ID
========================= */
if (!isset($_POST['order_id']) || empty($_POST['order_id'])) {
    header("Location: shop.php");
    exit();
}

$order_id = (int) $_POST['order_id'];

if ($order_id <= 0) {
    header("Location: shop.php");
    exit();
}

/* =========================
   FETCH ORDER SAFELY
========================= */
$stmt = mysqli_prepare($conn, "
    SELECT id, is_emi, payment_status 
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
   UPDATE PAYMENT STATUS
========================= */
if ($order['payment_status'] === 'Unpaid') {

    $new_status = ((int)$order['is_emi'] === 1)
        ? 'Partially Paid'
        : 'Paid';

    $update_stmt = mysqli_prepare($conn, "
        UPDATE orders 
        SET payment_status = ? 
        WHERE id = ?
    ");

    mysqli_stmt_bind_param($update_stmt, "si", $new_status, $order_id);
    $update_success = mysqli_stmt_execute($update_stmt);

    mysqli_stmt_close($update_stmt);

    if (!$update_success) {
        die("Payment update failed.");
    }
}

/* =========================
   REDIRECT TO SUCCESS PAGE
========================= */
header("Location: payment_success.php?id=" . $order_id);
exit();
