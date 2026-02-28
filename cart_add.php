<?php
session_start();

/* =========================
   VALIDATION
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (!isset($_POST['product_id'], $_POST['qty'])) {
    header("Location: index.php");
    exit;
}

$product_id = (int) $_POST['product_id'];
$qty        = (int) $_POST['qty'];

/* =========================
   SANITY CHECK
========================= */
if ($product_id <= 0 || $qty <= 0) {
    header("Location: index.php");
    exit;
}

/* =========================
   INIT CART IF NOT EXISTS
========================= */
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   ADD / UPDATE CART
========================= */
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $qty;
} else {
    $_SESSION['cart'][$product_id] = $qty;
}

/* =========================
   ABSOLUTE SAFE REDIRECT
========================= */
$redirect_url = "https://" . $_SERVER['HTTP_HOST'] . "/cart.php";
header("Location: " . $redirect_url);
exit;
?>
