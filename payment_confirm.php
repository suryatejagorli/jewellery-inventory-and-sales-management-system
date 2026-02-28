<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE REQUEST
========================= */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit();
}

if (
    empty($_POST['name']) ||
    empty($_POST['phone']) ||
    empty($_POST['address']) ||
    empty($_POST['payment_method']) ||
    !isset($_SESSION['cart']) ||
    empty($_SESSION['cart'])
) {
    header("Location: checkout.php");
    exit();
}

/* =========================
   SANITIZE INPUT
========================= */
$name  = trim($_POST['name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$payment_method = trim($_POST['payment_method']);

/* Phone validation */
if (!preg_match('/^[0-9]{10}$/', $phone)) {
    die("Invalid phone number.");
}

/* Allowed payment methods */
$allowed_methods = ['Cash', 'UPI', 'EMI'];
if (!in_array($payment_method, $allowed_methods)) {
    die("Invalid payment method.");
}

/* =========================
   SAVE / FIND CUSTOMER (SECURE)
========================= */
$stmt = mysqli_prepare($conn, "SELECT id FROM customers WHERE phone = ?");
mysqli_stmt_bind_param($stmt, "s", $phone);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    $customer = mysqli_fetch_assoc($result);
    $customer_id = $customer['id'];

} else {

    $insert_stmt = mysqli_prepare($conn, "
        INSERT INTO customers (name, phone, address)
        VALUES (?, ?, ?)
    ");
    mysqli_stmt_bind_param($insert_stmt, "sss", $name, $phone, $address);
    mysqli_stmt_execute($insert_stmt);

    $customer_id = mysqli_insert_id($conn);

    mysqli_stmt_close($insert_stmt);
}

mysqli_stmt_close($stmt);

/* =========================
   FETCH SETTINGS
========================= */
$settings_stmt = mysqli_prepare($conn, "
    SELECT gst_percent, delivery_charge
    FROM settings
    WHERE id = 1
");
mysqli_stmt_execute($settings_stmt);
$settings_result = mysqli_stmt_get_result($settings_stmt);

if (!$settings_result || mysqli_num_rows($settings_result) == 0) {
    die("Settings not configured.");
}

$settings = mysqli_fetch_assoc($settings_result);
mysqli_stmt_close($settings_stmt);

$gst_percent = (float)$settings['gst_percent'];
$delivery_charge = (float)$settings['delivery_charge'];

/* =========================
   CALCULATE BILL
========================= */
$subtotal = 0;

$product_stmt = mysqli_prepare($conn, "SELECT price FROM products WHERE id = ?");

foreach ($_SESSION['cart'] as $pid => $qty) {

    $pid = (int)$pid;
    $qty = (int)$qty;

    mysqli_stmt_bind_param($product_stmt, "i", $pid);
    mysqli_stmt_execute($product_stmt);
    $product_result = mysqli_stmt_get_result($product_stmt);

    if (!$product_result || mysqli_num_rows($product_result) == 0) {
        die("Product not found.");
    }

    $product = mysqli_fetch_assoc($product_result);
    $subtotal += $product['price'] * $qty;
}

mysqli_stmt_close($product_stmt);

$gst_amount  = round(($subtotal * $gst_percent) / 100, 2);
$grand_total = round($subtotal + $gst_amount + $delivery_charge, 2);

/* =========================
   STORE TEMP ORDER
========================= */
$_SESSION['temp_order'] = [
    'customer_id'     => $customer_id,
    'name'            => $name,
    'phone'           => $phone,
    'address'         => $address,
    'payment_method'  => $payment_method,
    'subtotal'        => $subtotal,
    'gst_amount'      => $gst_amount,
    'delivery_charge' => $delivery_charge,
    'grand_total'     => $grand_total
];

/* =========================
   REDIRECT TO SAVE ORDER
========================= */
header("Location: save_order.php");
exit();
