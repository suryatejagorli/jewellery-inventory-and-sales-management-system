<?php
session_start();
include 'db.php';

$orders = [];
$phone = '';
$error = '';

/* =========================
   HANDLE FORM SUBMISSION
========================= */
if (isset($_POST['phone'])) {

    $phone = trim($_POST['phone']);

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Please enter a valid 10-digit phone number.";
    } else {

        /* Secure Prepared Statement */
        $stmt = mysqli_prepare($conn, "
            SELECT 
                id, 
                order_date, 
                total_amount, 
                order_status,
                payment_status,
                payment_method
            FROM orders
            WHERE phone = ?
            ORDER BY id DESC
        ");

        mysqli_stmt_bind_param($stmt, "s", $phone);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container py-5" style="max-width:900px;">

<h3 class="mb-4 text-center fw-bold" style="color:#d4af37;">
📜 Order History
</h3>

<form method="post" class="mb-4">

    <label class="form-label fw-bold">Enter Phone Number</label>

    <input type="text"
           name="phone"
           class="form-control"
           maxlength="10"
           required
           value="<?= htmlspecialchars($phone); ?>">

    <button class="btn btn-warning mt-3 w-100 fw-bold">
        View Orders
    </button>

</form>

<?php if ($error) { ?>
    <div class="alert alert-danger text-center">
        <?= htmlspecialchars($error); ?>
    </div>
<?php } ?>

<?php if ($phone && empty($orders) && !$error) { ?>
    <div class="alert alert-warning text-center">
        No orders found for this phone number.
    </div>
<?php } ?>

<?php if (!empty($orders)) { ?>

<div class="table-responsive">
<table class="table table-bordered align-middle text-light"
       style="background:#1a1a1a;">

<thead style="background:#000;">
<tr>
    <th>Order ID</th>
    <th>Date</th>
    <th>Amount</th>
    <th>Order Status</th>
    <th>Payment</th>
    <th class="text-center">Action</th>
</tr>
</thead>

<tbody>
<?php foreach ($orders as $o) { 

    $status = strtolower(trim($o['order_status'] ?? ''));
    $paymentStatus = strtolower(trim($o['payment_status'] ?? ''));
    $paymentMethod = htmlspecialchars($o['payment_method'] ?? '');

    /* Amount color logic */
    $amountClass = "text-danger";
    if ($paymentStatus === "paid") {
        $amountClass = "text-success";
    } elseif ($paymentStatus === "partially paid") {
        $amountClass = "text-warning";
    }
?>
<tr>

    <td class="fw-bold">#<?= (int)$o['id']; ?></td>

    <td><?= date("d M Y", strtotime($o['order_date'])); ?></td>

    <td class="fw-bold <?= $amountClass; ?>">
        ₹ <?= number_format((float)$o['total_amount'], 2); ?>
    </td>

    <!-- Order Status -->
    <td>
        <?php if ($status === 'packed') { ?>
            <span class="badge bg-success">Packed</span>
        <?php } elseif ($status === 'cancelled') { ?>
            <span class="badge bg-danger">Cancelled</span>
        <?php } else { ?>
            <span class="badge bg-warning text-dark">Pending</span>
        <?php } ?>
    </td>

    <!-- Payment Status -->
    <td>
        <?php if ($paymentStatus === 'paid') { ?>
            <span class="badge bg-success">Paid</span>
        <?php } elseif ($paymentStatus === 'partially paid') { ?>
            <span class="badge bg-warning text-dark">Partially Paid</span>
        <?php } else { ?>
            <span class="badge bg-danger">Unpaid</span>
        <?php } ?>

        <div class="small text-muted mt-1">
            <?= $paymentMethod; ?>
        </div>
    </td>

    <td class="text-center">
        <a href="receipt.php?id=<?= (int)$o['id']; ?>"
           class="btn btn-sm btn-outline-light">
           🧾 View Receipt
        </a>
    </td>

</tr>
<?php } ?>
</tbody>

</table>
</div>

<?php } ?>

<div class="text-center mt-4">
    <a href="shop.php" class="btn btn-outline-secondary">
        ⬅ Back to Shop
    </a>
</div>

</div>

<?php include('includes/footer.php'); ?>
