<?php
session_start();

/* =========================
   ALLOW ONLY POST
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit();
}

/* =========================
   VALIDATE INPUT
========================= */
if (!isset($_POST['product_id'], $_POST['action'])) {
    header("Location: cart.php");
    exit();
}

$product_id = intval($_POST['product_id']);
$action     = trim($_POST['action']);

/* =========================
   BASIC SANITY CHECK
========================= */
if ($product_id <= 0) {
    header("Location: cart.php");
    exit();
}

/* =========================
   CHECK CART EXISTS
========================= */
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

/* =========================
   CHECK PRODUCT EXISTS
========================= */
if (!array_key_exists($product_id, $_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

/* =========================
   UPDATE QUANTITY
========================= */
switch ($action) {

    case 'increase':
        $_SESSION['cart'][$product_id] += 1;
        break;

    case 'decrease':
        $_SESSION['cart'][$product_id] -= 1;

        if ($_SESSION['cart'][$product_id] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
        break;

    default:
        // Invalid action
        header("Location: cart.php");
        exit();
}

/* =========================
   REDIRECT BACK
========================= */
header("Location: cart.php");
exit();
