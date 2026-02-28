<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

include '../db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = (int) $_GET['id'];

/* =========================
   HANDLE POST ACTIONS
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ===== MARK AS PACKED ===== */
    if (isset($_POST['mark_packed'])) {

        $stmt = mysqli_prepare($conn,
            "UPDATE orders SET order_status = 'Packed' WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: order_view.php?id=$order_id");
        exit;
    }

    /* ===== CANCEL ORDER ===== */
    if (isset($_POST['cancel_order'])) {

        mysqli_begin_transaction($conn);

        try {

            $stmt = mysqli_prepare($conn,
                "SELECT order_status FROM orders WHERE id = ?"
            );
            mysqli_stmt_bind_param($stmt, "i", $order_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $current_status);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            $current_status = strtolower(trim($current_status ?? 'pending'));

            if ($current_status === 'pending' || $current_status === 'packed') {

                /* Restore stock */
                $items_stmt = mysqli_prepare($conn,
                    "SELECT product_id, quantity 
                     FROM order_items 
                     WHERE order_id = ?"
                );
                mysqli_stmt_bind_param($items_stmt, "i", $order_id);
                mysqli_stmt_execute($items_stmt);
                $result = mysqli_stmt_get_result($items_stmt);

                while ($item = mysqli_fetch_assoc($result)) {

                    $pid = (int)$item['product_id'];
                    $qty = (int)$item['quantity'];

                    $update_stock = mysqli_prepare($conn,
                        "UPDATE products SET stock = stock + ? WHERE id = ?"
                    );
                    mysqli_stmt_bind_param($update_stock, "ii", $qty, $pid);
                    mysqli_stmt_execute($update_stock);
                    mysqli_stmt_close($update_stock);
                }

                mysqli_stmt_close($items_stmt);

                /* Update order */
                $cancel_stmt = mysqli_prepare($conn,
                    "UPDATE orders 
                     SET order_status = 'Cancelled',
                         payment_status = 'Failed'
                     WHERE id = ?"
                );
                mysqli_stmt_bind_param($cancel_stmt, "i", $order_id);
                mysqli_stmt_execute($cancel_stmt);
                mysqli_stmt_close($cancel_stmt);
            }

            mysqli_commit($conn);

        } catch (Exception $e) {
            mysqli_rollback($conn);
            die("Cancel failed.");
        }

        header("Location: order_view.php?id=$order_id");
        exit;
    }
}

/* =========================
   FETCH ORDER
========================= */
$stmt = mysqli_prepare($conn,
    "SELECT * FROM orders WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$order) {
    echo "Order not found.";
    exit;
}

/* ===== SAFE STATUS NORMALIZATION ===== */
$status = strtolower(trim($order['order_status'] ?? 'pending'));

if ($status === '') {
    $status = 'pending';
}

/* =========================
   FETCH ORDER ITEMS
========================= */
$items_stmt = mysqli_prepare($conn,
    "SELECT oi.quantity, oi.price, p.name 
     FROM order_items oi
     JOIN products p ON oi.product_id = p.id
     WHERE oi.order_id = ?"
);
mysqli_stmt_bind_param($items_stmt, "i", $order_id);
mysqli_stmt_execute($items_stmt);
$items = mysqli_stmt_get_result($items_stmt);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Details | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

<h3 class="mb-3">🧾 Order #<?= $order_id ?></h3>

<p><b>Customer:</b> <?= htmlspecialchars($order['customer_name']); ?></p>
<p><b>Phone:</b> <?= htmlspecialchars($order['phone']); ?></p>
<p><b>Payment:</b> <?= htmlspecialchars($order['payment_method']); ?></p>

<p>
<b>Status:</b>
<?php
if ($status === 'packed') {
    echo '<span class="badge bg-success">Packed</span>';
}
elseif ($status === 'cancelled') {
    echo '<span class="badge bg-danger">Cancelled</span>';
}
else {
    echo '<span class="badge bg-warning text-dark">Pending</span>';
}
?>
</p>

<table class="table table-bordered mt-4 bg-white">
<thead>
<tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
</tr>
</thead>
<tbody>

<?php
$total = 0;
while ($row = mysqli_fetch_assoc($items)) {
    $line = $row['price'] * $row['quantity'];
    $total += $line;
?>
<tr>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= $row['quantity']; ?></td>
    <td>₹ <?= number_format($row['price'], 2); ?></td>
    <td>₹ <?= number_format($line, 2); ?></td>
</tr>
<?php } ?>

</tbody>
<tfoot>
<tr>
    <th colspan="3" class="text-end">Grand Total</th>
    <th>₹ <?= number_format($total, 2); ?></th>
</tr>
</tfoot>
</table>

<?php if ($status === 'pending') { ?>
<form method="post" class="mt-3 d-inline">
    <button type="submit" name="mark_packed" class="btn btn-primary">
        📦 Mark as Packed
    </button>
</form>

<form method="post" class="mt-3 d-inline">
    <button type="submit" name="cancel_order" class="btn btn-danger">
        ❌ Cancel Order
    </button>
</form>
<?php } ?>

<?php if ($status === 'packed') { ?>
<div class="alert alert-success mt-3">
    📦 Order has been packed successfully
</div>
<?php } ?>

<?php if ($status === 'cancelled') { ?>
<div class="alert alert-danger mt-3">
    ❌ Order has been cancelled and stock restored
</div>
<?php } ?>

<br>
<a href="orders.php" class="btn btn-secondary mt-3">⬅ Back to Orders</a>

</div>
</body>
</html>