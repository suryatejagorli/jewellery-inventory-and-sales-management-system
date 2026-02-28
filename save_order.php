<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =========================
   VALIDATION
========================= */
if (
    !isset($_SESSION['temp_order']) ||
    !isset($_SESSION['cart']) ||
    empty($_SESSION['cart'])
) {
    header("Location: checkout.php");
    exit();
}

mysqli_begin_transaction($conn);

try {

    $orderData = $_SESSION['temp_order'];

    $name           = trim($orderData['name']);
    $phone          = trim($orderData['phone']);
    $address        = trim($orderData['address']);
    $payment_method = trim($orderData['payment_method']);

    /* =========================
       CALCULATE SUBTOTAL
    ========================= */
    $subtotal = 0;

    $product_stmt = mysqli_prepare($conn, "SELECT price FROM products WHERE id = ?");
    if (!$product_stmt) {
        throw new Exception("Product query failed.");
    }

    foreach ($_SESSION['cart'] as $pid => $qty) {

        $pid = (int)$pid;
        $qty = (int)$qty;

        mysqli_stmt_bind_param($product_stmt, "i", $pid);
        mysqli_stmt_execute($product_stmt);
        $result = mysqli_stmt_get_result($product_stmt);

        if (!$result || mysqli_num_rows($result) == 0) {
            throw new Exception("Product not found.");
        }

        $product = mysqli_fetch_assoc($result);
        $subtotal += (float)$product['price'] * $qty;
    }

    mysqli_stmt_close($product_stmt);

    /* =========================
       FETCH GST & DELIVERY
    ========================= */
    $settings_stmt = mysqli_prepare($conn,
        "SELECT gst_percent, delivery_charge FROM settings LIMIT 1"
    );

    if (!$settings_stmt) {
        throw new Exception("Settings query failed.");
    }

    mysqli_stmt_execute($settings_stmt);
    $settings_result = mysqli_stmt_get_result($settings_stmt);

    if (!$settings_result || mysqli_num_rows($settings_result) == 0) {
        throw new Exception("GST/Delivery settings not configured.");
    }

    $settings = mysqli_fetch_assoc($settings_result);
    mysqli_stmt_close($settings_stmt);

    $gst_percent     = (float)$settings['gst_percent'];
    $delivery_charge = (float)$settings['delivery_charge'];

    $gst_amount  = round(($subtotal * $gst_percent) / 100, 2);
    $grand_total = $subtotal + $gst_amount + $delivery_charge;

    /* =========================
       INSERT CUSTOMER
    ========================= */
    $check_customer = mysqli_prepare($conn,
        "SELECT id FROM customers WHERE phone = ?"
    );

    if (!$check_customer) {
        throw new Exception("Customer query failed.");
    }

    mysqli_stmt_bind_param($check_customer, "s", $phone);
    mysqli_stmt_execute($check_customer);
    $cust_result = mysqli_stmt_get_result($check_customer);

    if (mysqli_num_rows($cust_result) > 0) {
        $cust = mysqli_fetch_assoc($cust_result);
        $customer_id = $cust['id'];
    } else {

        $insert_customer = mysqli_prepare($conn,
            "INSERT INTO customers (name, phone, address)
             VALUES (?, ?, ?)"
        );

        if (!$insert_customer) {
            throw new Exception("Customer insert failed.");
        }

        mysqli_stmt_bind_param($insert_customer, "sss",
            $name, $phone, $address
        );

        mysqli_stmt_execute($insert_customer);
        $customer_id = mysqli_insert_id($conn);

        mysqli_stmt_close($insert_customer);
    }

    mysqli_stmt_close($check_customer);

    /* =========================
   ORDER DEFAULT STATUS
========================= */
$order_status = 'Pending';

/* ALL online payments start as Unpaid */
$payment_status = 'Unpaid';

    /* =========================
       EMI HANDLING
    ========================= */
    $is_emi = 0;
    $emi_total_amount = 0;
    $emi_paid_amount = 0;
    $emi_remaining_amount = 0;
    $emi_months = 0;

    if ($payment_method === 'EMI') {

        $is_emi = 1;
        $emi_total_amount     = $grand_total;
        $emi_paid_amount      = (float)($orderData['down_payment'] ?? 0);
        $emi_remaining_amount = (float)($orderData['remaining_amount'] ?? 0)
                              + (float)($orderData['interest_amount'] ?? 0);
        $emi_months           = (int)($orderData['emi_months'] ?? 0);
    }

    /* =========================
       INSERT ORDER
    ========================= */
    $order_stmt = mysqli_prepare($conn, "
        INSERT INTO orders 
        (customer_id, customer_name, phone, address, payment_method,
         total_amount, subtotal, gst_amount, delivery_charge,
         order_status, payment_status,
         is_emi, emi_total_amount, emi_paid_amount,
         emi_remaining_amount, emi_months)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$order_stmt) {
        throw new Exception("Order insert preparation failed.");
    }

    mysqli_stmt_bind_param(
        $order_stmt,
        "issssddddssidddi",
        $customer_id,
        $name,
        $phone,
        $address,
        $payment_method,
        $grand_total,
        $subtotal,
        $gst_amount,
        $delivery_charge,
        $order_status,
        $payment_status,
        $is_emi,
        $emi_total_amount,
        $emi_paid_amount,
        $emi_remaining_amount,
        $emi_months
    );

    mysqli_stmt_execute($order_stmt);
    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($order_stmt);

    /* =========================
       INSERT ORDER ITEMS + UPDATE STOCK
    ========================= */

    $item_stmt = mysqli_prepare($conn,
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (?, ?, ?, ?)"
    );

    $stock_stmt = mysqli_prepare($conn,
        "UPDATE products SET stock = stock - ? WHERE id = ?"
    );

    foreach ($_SESSION['cart'] as $pid => $qty) {

        $pid = (int)$pid;
        $qty = (int)$qty;

        $check_stock = mysqli_prepare($conn,
            "SELECT price, stock FROM products WHERE id = ?"
        );

        mysqli_stmt_bind_param($check_stock, "i", $pid);
        mysqli_stmt_execute($check_stock);
        $stock_result = mysqli_stmt_get_result($check_stock);

        if (!$stock_result || mysqli_num_rows($stock_result) == 0) {
            throw new Exception("Product not found: ID $pid");
        }

        $product = mysqli_fetch_assoc($stock_result);

        if ($product['stock'] < $qty) {
            throw new Exception("Insufficient stock.");
        }

        $price = (float)$product['price'];

        mysqli_stmt_bind_param($item_stmt, "iiid",
            $order_id, $pid, $qty, $price
        );
        mysqli_stmt_execute($item_stmt);

        mysqli_stmt_bind_param($stock_stmt, "ii",
            $qty, $pid
        );
        mysqli_stmt_execute($stock_stmt);

        mysqli_stmt_close($check_stock);
    }

    mysqli_stmt_close($item_stmt);
    mysqli_stmt_close($stock_stmt);

    mysqli_commit($conn);

    unset($_SESSION['cart']);
    unset($_SESSION['temp_order']);

    if ($payment_method === 'Cash') {
        header("Location: order_confirmed.php?id=" . $order_id);
    } else {
        header("Location: qr_payment.php?id=" . $order_id);
    }

    exit();

} catch (Exception $e) {

    mysqli_rollback($conn);
    echo "<h3 style='color:red;'>Transaction Failed:</h3>";
    echo htmlspecialchars($e->getMessage());
}
?>