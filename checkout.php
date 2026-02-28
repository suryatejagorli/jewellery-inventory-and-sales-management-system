<?php
session_start();
include 'db.php';

/* =========================
   IF CART EMPTY → REDIRECT
========================= */
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

/* =========================
   HANDLE CHECKOUT SUBMIT
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';

    if (empty($name) || empty($phone) || empty($address) || empty($payment_method)) {
        die("All required fields must be filled.");
    }

    /* =========================
       CALCULATE GRAND TOTAL
    ========================= */
    $grand_total = 0;

    foreach ($_SESSION['cart'] as $product_id => $qty) {

        $product_id = (int)$product_id;
        $qty = (int)$qty;

        $result = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");

        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            $grand_total += $product['price'] * $qty;
        }
    }

    if ($grand_total <= 0) {
        die("Invalid cart amount.");
    }

    /* =========================
       INITIALIZE ORDER STATUS
       (THIS FIXES DISAPPEARING BUTTONS)
    ========================= */

    $order_status = 'Pending';

    if ($payment_method === 'Cash') {
    $payment_status = 'Unpaid';
}
elseif ($payment_method === 'UPI' || $payment_method === 'EMI') {
    $payment_status = 'Unpaid';  // Important fix
}
else {
    $payment_status = 'Unpaid';
}

    /* =========================
       STORE TEMP ORDER
    ========================= */
    $_SESSION['temp_order'] = [
        'name' => htmlspecialchars($name),
        'phone' => htmlspecialchars($phone),
        'email' => htmlspecialchars($email),
        'address' => htmlspecialchars($address),
        'payment_method' => $payment_method,
        'grand_total' => $grand_total,
        'order_status' => $order_status,
        'payment_status' => $payment_status
    ];

    /* =========================
       ROUTE BASED ON PAYMENT
    ========================= */
    if ($payment_method === "EMI") {
        header("Location: emi_details.php");
    } else {
        header("Location: save_order.php");
    }

    exit();
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-6">

<div class="card shadow-lg border-0">
<div class="card-body p-4">

<h3 class="text-center mb-4" style="color:#d4af37;">
    🧾 Secure Checkout
</h3>

<form method="post" action="">

<div class="mb-3">
<label class="form-label">
Customer Name <span class="text-danger">*</span>
</label>
<input type="text"
       name="name"
       class="form-control"
       required
       minlength="3"
       maxlength="100"
       placeholder="Enter full name">
</div>

<div class="mb-3">
<label class="form-label">
Mobile Number <span class="text-danger">*</span>
</label>
<input type="tel"
       name="phone"
       class="form-control"
       pattern="[0-9]{10}"
       maxlength="10"
       required
       placeholder="10 digit mobile number">
<small class="text-muted">
Must be exactly 10 digits.
</small>
</div>

<div class="mb-3">
<label class="form-label">
Email (Optional)
</label>
<input type="email"
       name="email"
       class="form-control"
       maxlength="100"
       placeholder="example@email.com">
</div>

<div class="mb-3">
<label class="form-label">
Delivery Address <span class="text-danger">*</span>
</label>
<textarea name="address"
          class="form-control"
          rows="3"
          required
          minlength="10"
          placeholder="Enter complete delivery address"></textarea>
</div>

<div class="mb-4">
<label class="form-label">
Payment Method <span class="text-danger">*</span>
</label>
<select name="payment_method"
        class="form-select"
        required>
    <option value="">-- Select Payment Method --</option>
    <option value="Cash">Cash on Delivery</option>
    <option value="UPI">UPI</option>
    <option value="EMI">EMI</option>
</select>
</div>

<div class="d-grid">
<button type="submit"
        class="btn btn-lg"
        style="background:#d4af37; font-weight:600;">
    Proceed to Payment
</button>
</div>

</form>

</div>
</div>

<div class="text-center mt-3">
<a href="cart.php" class="text-decoration-none text-secondary">
⬅ Back to Cart
</a>
</div>

</div>
</div>
</div>

<?php include('includes/footer.php'); ?>