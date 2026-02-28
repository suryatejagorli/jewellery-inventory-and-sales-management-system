<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE TEMP ORDER
========================= */
if (!isset($_SESSION['temp_order']) || empty($_SESSION['temp_order'])) {
    die("Session expired. Please restart checkout.");
}

$order = $_SESSION['temp_order'];

if (!isset($order['grand_total']) || $order['grand_total'] <= 0) {
    die("Invalid order amount.");
}

$grand_total = (float)$order['grand_total'];

/* =========================
   HANDLE EMI SELECTION
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['emi_months']) || empty($_POST['emi_months'])) {
        die("Please select EMI months.");
    }

    $emi_months = (int)$_POST['emi_months'];

    if (!in_array($emi_months, [3,6,9,12])) {
        die("Invalid EMI duration selected.");
    }

    /* =========================
       EMI CALCULATION
       20% Down Payment
       8% Annual Interest
    ========================= */

    $down_payment = round($grand_total * 0.20, 2);
    $remaining = round($grand_total - $down_payment, 2);

    $interest = round(
        $remaining * 0.08 * ($emi_months / 12),
        2
    );

    $emi_installment = round(
        ($remaining + $interest) / $emi_months,
        2
    );

    /* =========================
       STORE EMI DATA
    ========================= */

    $_SESSION['temp_order']['payment_method'] = 'EMI';
    $_SESSION['temp_order']['emi_months'] = $emi_months;
    $_SESSION['temp_order']['down_payment'] = $down_payment;
    $_SESSION['temp_order']['remaining_amount'] = $remaining;
    $_SESSION['temp_order']['interest_amount'] = $interest;
    $_SESSION['temp_order']['emi_installment'] = $emi_installment;

    header("Location: emi_preview.php");
    exit();
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-6">

<div class="card shadow-lg border-secondary">
<div class="card-body text-center">

<h3 class="mb-4" style="color:#d4af37;">💳 EMI Plan Selection</h3>

<p><strong>Order Value:</strong> 
<span style="color:#d4af37;">₹ <?= number_format($grand_total, 2); ?></span>
</p>

<p class="text-muted">
Select EMI duration. You will pay 
<strong>20% now</strong> and the remaining with 
<strong>8% annual interest</strong>.
</p>

<form method="post">

<select name="emi_months" class="form-select mb-3" required>
    <option value="">-- Select EMI Months --</option>
    <option value="3">3 Months</option>
    <option value="6">6 Months</option>
    <option value="9">9 Months</option>
    <option value="12">12 Months</option>
</select>

<button type="submit" class="btn w-100"
        style="background:#d4af37; font-weight:600;">
Continue
</button>

</form>

<a href="checkout.php" 
   class="btn btn-outline-secondary mt-3 w-100">
⬅ Back to Checkout
</a>

</div>
</div>

</div>
</div>
</div>

<?php include('includes/footer.php'); ?>
