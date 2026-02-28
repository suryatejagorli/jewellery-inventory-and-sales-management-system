<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE SESSION DATA
========================= */
if (
    !isset($_SESSION['cart']) ||
    empty($_SESSION['cart']) ||
    !isset($_SESSION['temp_order'])
) {
    header("Location: index.php");
    exit();
}

$data = $_SESSION['temp_order'];
$cart = $_SESSION['cart'];

/* Required fields */
if (
    empty($data['customer_id']) ||
    empty($data['name']) ||
    empty($data['phone']) ||
    empty($data['address']) ||
    empty($data['payment_method']) ||
    empty($data['grand_total'])
) {
    header("Location: checkout.php");
    exit();
}

$customer_id    = (int)$data['customer_id'];
$name           = $data['name'];
$phone          = $data['phone'];
$address        = $data['address'];
$payment_method = $data['payment_method'];
$grand_total    = (float)$data['grand_total'];

/* =========================
   DETECT DEVICE TYPE
========================= */
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('/mobile/i', $user_agent)) {
    $device_type = 'mobile';
} else {
    $device_type = 'desktop';
}

/* =========================
   START TRANSACTION
========================= */
mysqli_begin_transaction($conn);

try {

    /* =========================
       INSERT ORDER
    ========================= */
    $order_stmt = mysqli_prepare($conn, "
        INSERT INTO orders 
        (customer_id, customer_name, phone, address, payment_method, total_amount, payment_status, order_status, device_type)
        VALUES (?, ?, ?, ?, ?, ?, 'Unpaid', 'Pending', ?)
    ");

    mysqli_stmt_bind_param(
        $order_stmt,
        "issssds",
        $customer_id,
        $name,
        $phone,
        $address,
        $payment_method,
        $grand_total,
        $device_type
    );

    mysqli_stmt_execute($order_stmt);

    $order_id = mysqli_insert_id($conn);

    mysqli_stmt_close($order_stmt);

    if ($order_id <= 0) {
        throw new Exception("Order creation failed.");
    }

    /* =========================
       INSERT ORDER ITEMS
    ========================= */
    $product_stmt = mysqli_prepare($conn, "
        SELECT price FROM products WHERE id = ?
    ");

    $item_stmt = mysqli_prepare($conn, "
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($cart as $pid => $qty) {

        $pid = (int)$pid;
        $qty = (int)$qty;

        mysqli_stmt_bind_param($product_stmt, "i", $pid);
        mysqli_stmt_execute($product_stmt);
        $product_result = mysqli_stmt_get_result($product_stmt);

        if (!$product_result || mysqli_num_rows($product_result) == 0) {
            throw new Exception("Product not found.");
        }

        $product = mysqli_fetch_assoc($product_result);
        $price = (float)$product['price'];

        mysqli_stmt_bind_param($item_stmt, "iiid", $order_id, $pid, $qty, $price);
        mysqli_stmt_execute($item_stmt);
    }

    mysqli_stmt_close($product_stmt);
    mysqli_stmt_close($item_stmt);

    /* =========================
       COMMIT TRANSACTION
    ========================= */
    mysqli_commit($conn);

} catch (Exception $e) {

    mysqli_rollback($conn);
    die("Order failed. Please try again.");
}

/* =========================
   CLEAN SESSION
========================= */
$_SESSION['last_order_id'] = $order_id;

unset($_SESSION['cart']);
unset($_SESSION['temp_order']);

/* =========================
   REDIRECT BASED ON PAYMENT
========================= */
if ($payment_method === 'Cash') {
    header("Location: cod_success.php?id=" . $order_id);
} else {
    header("Location: payment.php?id=" . $order_id);
}

exit();
