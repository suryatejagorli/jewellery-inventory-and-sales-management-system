<?php
session_start();
include 'db.php';
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<style>
/* ===== CART LUXURY UI ===== */

.cart-wrapper {
    min-height: 75vh;
}

.cart-title {
    font-weight: 700;
    letter-spacing: 1px;
}

.cart-table {
    background: linear-gradient(145deg, #111, #1a1a1a);
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
}

.cart-table thead {
    background: #000;
}

.cart-table th {
    border-color: rgba(255,255,255,0.08);
}

.cart-table td {
    border-color: rgba(255,255,255,0.05);
}

.cart-table tbody tr:hover {
    background: rgba(212,175,55,0.05);
    transition: 0.3s ease;
}

.price-gold {
    color: #d4af37;
    font-weight: 600;
}

.qty-btn {
    border-radius: 6px;
    transition: 0.2s ease;
}

.qty-btn:hover {
    background-color: #d4af37;
    color: #000;
}

.cart-footer-total {
    font-size: 18px;
    font-weight: 600;
}

.btn-gold {
    background: #d4af37;
    color: #000;
    font-weight: 600;
    border-radius: 8px;
    transition: 0.3s ease;
}

.btn-gold:hover {
    background: #c19b2e;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(212,175,55,0.25);
}

.empty-cart-box {
    background: linear-gradient(145deg, #111, #1a1a1a);
    padding: 60px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.05);
    text-align: center;
}

.empty-cart-icon {
    font-size: 60px;
    margin-bottom: 15px;
    opacity: 0.8;
}
</style>

<div class="container py-5 cart-wrapper">

<?php
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
?>
    <div class="empty-cart-box mx-auto" style="max-width:600px;">
        <div class="empty-cart-icon">🛒</div>
        <h3 class="mb-3">Your Shopping Cart is Empty</h3>
        <p class="text-muted mb-4">
            Explore our premium jewellery collection and add your favourites.
        </p>
        <a href="shop.php" class="btn btn-gold px-4 py-2">
            Continue Shopping
        </a>
    </div>
<?php
    include('includes/footer.php');
    exit();
}

$total = 0;
?>

<h2 class="mb-4 cart-title">🛒 Shopping Cart</h2>

<div class="table-responsive cart-table">
<table class="table align-middle text-light mb-0">
  <thead>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th style="width:180px;">Qty</th>
      <th>Subtotal</th>
    </tr>
  </thead>

  <tbody>
<?php
foreach ($_SESSION['cart'] as $product_id => $qty) {

    $product_id = intval($product_id);
    $res = mysqli_query($conn, "SELECT name, price FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($res);

    if (!$product) continue;

    $subtotal = $product['price'] * $qty;
    $total += $subtotal;
?>
    <tr>
      <td><?php echo htmlspecialchars($product['name']); ?></td>

      <td class="price-gold">
        ₹ <?php echo number_format($product['price'], 2); ?>
      </td>

      <td>
        <form method="post" action="update_cart.php" class="d-flex align-items-center gap-2">
          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

          <button type="submit" name="action" value="decrease"
                  class="btn btn-sm btn-outline-light qty-btn">−</button>

          <strong><?php echo $qty; ?></strong>

          <button type="submit" name="action" value="increase"
                  class="btn btn-sm btn-outline-light qty-btn">+</button>
        </form>
      </td>

      <td class="price-gold">
        ₹ <?php echo number_format($subtotal, 2); ?>
      </td>
    </tr>
<?php } ?>
  </tbody>

  <tfoot>
    <tr style="background:#000;">
      <th colspan="3" class="text-end cart-footer-total">Total</th>
      <th class="price-gold cart-footer-total">
        ₹ <?php echo number_format($total, 2); ?>
      </th>
    </tr>
  </tfoot>
</table>
</div>

<div class="mt-4 d-flex justify-content-between flex-wrap gap-2">

  <div>
    <a href="shop.php" class="btn btn-outline-secondary px-4">
      Continue Shopping
    </a>

    <a href="clear_cart.php"
       class="btn btn-outline-danger px-4 ms-2"
       onclick="return confirm('Are you sure you want to clear your cart?');">
       🗑 Clear Cart
    </a>
  </div>

  <a href="checkout.php" class="btn btn-gold px-4">
    Proceed to Checkout
  </a>

</div>

</div>

<?php include('includes/footer.php'); ?>