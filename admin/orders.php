<?php
session_start();

/* 🔐 Admin session check */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* 🚫 Prevent browser cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

include '../db.php';

/* =========================
   FETCH ORDERS (Latest First)
========================= */
$stmt = mysqli_prepare($conn, "
    SELECT id, customer_name, phone, payment_method, 
           order_status, total_amount, order_date
    FROM orders
    ORDER BY order_date DESC, id DESC
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

<h2 class="mb-4">📦 Customer Orders</h2>

<table class="table table-bordered table-hover bg-white shadow-sm">
<thead class="table-light">
<tr>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Payment</th>
    <th>Status</th>
    <th>Total</th>
    <th>Date</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php if ($result && mysqli_num_rows($result) > 0): ?>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>

    <?php
        $status = strtolower(trim($row['order_status']));

        switch ($status) {
            case 'packed':
                $badge = '<span class="badge bg-success">Packed</span>';
                break;

            case 'cancelled':
                $badge = '<span class="badge bg-danger">Cancelled</span>';
                break;

            default:
                $badge = '<span class="badge bg-warning text-dark">Pending</span>';
                break;
        }
    ?>

    <tr>
        <td>#<?= (int)$row['id']; ?></td>

        <td><?= htmlspecialchars($row['customer_name']); ?></td>

        <td><?= htmlspecialchars($row['phone']); ?></td>

        <td><?= htmlspecialchars($row['payment_method']); ?></td>

        <td><?= $badge; ?></td>

        <td>₹ <?= number_format((float)$row['total_amount'], 2); ?></td>

        <td>
            <?= !empty($row['order_date']) 
                ? date("d M Y, h:i A", strtotime($row['order_date'])) 
                : '-'; ?>
        </td>

        <td>
            <a href="order_view.php?id=<?= (int)$row['id']; ?>" 
               class="btn btn-sm btn-outline-dark">
               View
            </a>
        </td>
    </tr>

    <?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="8" class="text-center text-muted">
        No orders found.
    </td>
</tr>

<?php endif; ?>

</tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary mt-3">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>