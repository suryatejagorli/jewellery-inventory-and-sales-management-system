<?php
session_start();
include 'db.php';

/* =========================
   VALIDATE PRODUCT ID
========================= */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product not found.");
}

$id = (int)$_GET['id'];

if ($id <= 0) {
    die("Invalid product.");
}

/* =========================
   FETCH PRODUCT SECURELY
========================= */
$stmt = mysqli_prepare($conn, "
    SELECT products.*, categories.name AS category_name 
    FROM products 
    JOIN categories ON products.category_id = categories.id
    WHERE products.id = ?
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container py-5">
  <div class="row align-items-center">

    <div class="col-md-6 text-center mb-4 mb-md-0">
      <img 
        src="uploads/<?= htmlspecialchars($product['image']); ?>" 
        class="img-fluid rounded shadow"
        style="max-height:450px;"
        alt="<?= htmlspecialchars($product['name']); ?>"
      >
    </div>

    <div class="col-md-6">

      <h2 class="fw-bold mb-2">
        <?= htmlspecialchars($product['name']); ?>
      </h2>

      <p class="text-uppercase text-muted">
        <?= htmlspecialchars($product['category_name']); ?>
      </p>

      <h3 class="fw-bold my-3" style="color:#d4af37;">
        ₹ <?= number_format((float)$product['price'], 2); ?>
      </h3>

      <p><strong>Weight:</strong> <?= (float)$product['weight']; ?> g</p>

      <?php if ($product['category_name'] === 'Gold') { ?>
          <div class="p-3 mb-3 rounded"
               style="background:#1a1a1a; border:1px solid #d4af37;">

              <div class="fw-bold" style="color:#d4af37; font-size:16px;">
                  <?php
                      if ($product['purity'] === '916') {
                          echo "SBJ 916 HM";
                      } elseif ($product['purity'] === '999') {
                          echo "SBJ 999";
                      }
                  ?>
              </div>

              <div class="small text-light">
                  HUID: <?= htmlspecialchars($product['huid_code']); ?>
              </div>
          </div>
      <?php } ?>

      <p class="text-muted">
        <?= htmlspecialchars($product['description']); ?>
      </p>

      <?php if ($product['stock'] > 5) { ?>
          <p class="text-success fw-bold">In Stock</p>
      <?php } elseif ($product['stock'] > 0) { ?>
          <p class="text-warning fw-bold">
              Only <?= (int)$product['stock']; ?> left!
          </p>
      <?php } else { ?>
          <p class="text-danger fw-bold">
              Out of Stock
          </p>
      <?php } ?>

      <hr class="border-secondary">

      <?php if ($product['stock'] > 0) { ?>

      <!-- ABSOLUTE SAFE ACTION -->
      <form method="post" action="/cart_add.php" class="mt-3">

        <input type="hidden" name="product_id" value="<?= (int)$product['id']; ?>">

        <div class="mb-3" style="max-width:150px;">
          <label class="form-label">Quantity</label>
          <input type="number"
                 name="qty"
                 value="1"
                 min="1"
                 max="<?= (int)$product['stock']; ?>"
                 class="form-control"
                 required>
        </div>

        <button type="submit" class="btn btn-warning btn-lg">
          🛒 Add to Cart
        </button>

        <a href="shop.php" class="btn btn-outline-secondary btn-lg ms-2">
          Back to Shop
        </a>

      </form>

      <?php } else { ?>

      <button class="btn btn-secondary btn-lg" disabled>
          Out of Stock
      </button>

      <a href="shop.php" class="btn btn-outline-secondary btn-lg ms-2">
          Back to Shop
      </a>

      <?php } ?>

    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
