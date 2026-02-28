<?php
session_start();
include '../db.php';

/* 🔐 Admin session check */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   UPDATE ORDER STATUS
========================= */
if (isset($_POST['order_id'])) {

    $order_id = (int)$_POST['order_id'];

    $update = mysqli_query($conn, "
        UPDATE orders 
        SET order_status = 'Packed'
        WHERE id = $order_id
    ");

    if (!$update) {
        die("Status update failed: " . mysqli_error($conn));
    }
}

header("Location: orders.php");
exit;
?>
