<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE EMI SESSION DATA
========================= */
if (
    !isset($_SESSION['temp_order']) ||
    !isset($_SESSION['temp_order']['emi_months'])
) {
    die("EMI details missing. Please restart checkout.");
}

$order = $_SESSION['temp_order'];

$grand_total     = (float)$order['grand_total'];
$emi_months      = (int)$order['emi_months'];
$down_payment    = (float)$order['down_payment'];
$remaining       = (float)$order['remaining_amount'];
$interest        = (float)$order['interest_amount'];
$emi_installment = (float)$order['emi_installment'];

$total_with_interest = $remaining + $interest;
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-6">

<div class="card shadow-lg border-secondary text-center p-4">

<h3 class="mb-4" style="color:#d4af37;">📊 EMI Plan Details</h3>

<p>
<strong>Order Total:</strong><br>
<span style="color:#d4af37;">
₹ <?= number_format($grand_total, 2); ?>
</span>
</p>

<hr class="border-secondary">

<h5 class="text-success">
₹ <?= number_format($emi_installment, 2); ?>
<span class="fs-6 text-light">per month</span>
</h5>

<p>
For <strong><?= $emi_months ?> months</strong>
</p>

<hr class="border-secondary">

<p>
<strong>Down Payment (20% Now):</strong><br>
₹ <?= number_format($down_payment, 2); ?>
</p>

<p>
<strong>Total Remaining (with Interest):</strong><br>
₹ <?= number_format($total_with_interest, 2); ?>
</p>

<hr class="border-secondary">

<form action="save_order.php" method="post">
    <button type="submit"
            class="btn w-100"
            style="background:#d4af37; font-weight:600;">
        💳 Pay Down Payment
    </button>
</form>

<a href="emi_details.php"
   class="btn btn-outline-secondary mt-3 w-100">
   ⬅ Back
</a>

</div>
</div>
</div>
</div>

<?php include('includes/footer.php'); ?>
