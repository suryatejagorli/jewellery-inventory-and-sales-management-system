<?php
session_start();
include 'db.php';

/* =========================
   CUSTOMER SESSION
========================= */
$_SESSION['customer'] = true;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* =========================
   CART COUNT
========================= */
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}

/* =========================
   GET FILTER VALUES
========================= */
$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

/* =========================
   BUILD SECURE QUERY
========================= */
$sql = "
    SELECT products.*, categories.name AS category_name
    FROM products
    JOIN categories ON products.category_id = categories.id
    WHERE 1
";

$params = [];
$types  = "";

if ($search !== '') {
    $sql .= " AND (
        products.name LIKE ? OR
        products.description LIKE ? OR
        categories.name LIKE ?
    )";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

if ($category !== '') {
    $sql .= " AND categories.name = ?";
    $params[] = $category;
    $types .= "s";
}

if ($search === '' && $category === '') {
    $sql .= " AND products.featured = 1";
}

$sql .= " ORDER BY products.id ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
<title>SBJ Jewellery | Luxury Collection</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ===============================
   GLOBAL DARK LUXURY
================================ */
body {
    background-color: #121212;
    color: #f1f1f1;
    min-height: 100vh;

    opacity: 0;
    animation: fadeInPage 0.6s ease forwards;
}

@keyframes fadeInPage {
    to { opacity: 1; }
}

/* ===============================
   NAVBAR
================================ */
.navbar-brand {
    letter-spacing: 2px;
    font-weight: 700;
}

.nav-link.active,
.nav-link:hover {
    color: #d4af37 !important;
    transition: 0.3s;
}

/* ===============================
   CARD ANIMATION
================================ */
.card {
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
}

/* IMAGE ZOOM */
.card-img-top {
    transition: transform 0.5s ease;
}

.card:hover .card-img-top {
    transform: scale(1.08);
}

/* ===============================
   BUTTON ANIMATION
================================ */
.btn {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.6);
}

.btn:active {
    transform: scale(0.95);
}

/* MOBILE FIX */
@media (max-width: 991px) {
    .card-img-top {
        height: 200px !important;
    }
    .display-6 {
        font-size: 1.4rem;
    }
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm sticky-top">
  <div class="container">

    <a class="navbar-brand" href="shop.php">
      SBJ <span style="color:#d4af37;">Jewellery</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navBar">

      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?= ($category==''?'active':'') ?>" href="shop.php">All</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($category=='Gold'?'active':'') ?>" href="shop.php?category=Gold">Gold</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($category=='Silver'?'active':'') ?>" href="shop.php?category=Silver">Silver</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($category=='Diamond'?'active':'') ?>" href="shop.php?category=Diamond">Diamond</a>
        </li>
      </ul>

      <!-- SEARCH -->
      <form method="GET" class="d-flex my-2 my-lg-0 me-lg-3">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category); ?>">
        <input class="form-control form-control-sm me-2"
               type="search"
               name="search"
               placeholder="Search jewellery..."
               value="<?= htmlspecialchars($search); ?>">
        <button class="btn btn-outline-light btn-sm">🔍</button>
      </form>

      <!-- RIGHT BUTTONS -->
      <div class="d-flex flex-column flex-lg-row gap-2">

        <a href="order_history.php" class="btn btn-outline-light btn-sm">
          📜 Order History
        </a>

        <a href="help.php" class="btn btn-outline-secondary btn-sm">
          ☎ Help
        </a>

        <a href="cart.php" class="btn btn-outline-light btn-sm position-relative">
          🛒 Cart
          <?php if ($cart_count > 0) { ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?= $cart_count; ?>
            </span>
          <?php } ?>
        </a>

        <a href="customer_logout.php" class="btn btn-outline-danger btn-sm">
          ⬅ Exit
        </a>

      </div>

    </div>
  </div>
</nav>

<!-- HERO -->
<section class="py-5 text-center bg-black">
  <div class="container">
    <h1 class="fw-bold display-6">Elite Jewellery Collection</h1>
    <p class="text-muted fs-5">Discover timeless elegance</p>
    <div class="mx-auto mt-3" style="width:80px;height:3px;background:#d4af37;"></div>
  </div>
</section>

<!-- PRODUCTS -->
<section class="py-5">
  <div class="container">
    <div class="row g-4 justify-content-center">

<?php if(mysqli_num_rows($result) == 0) { ?>
    <div class="col-12 text-center">
        <p class="text-muted fs-5">No products found.</p>
    </div>
<?php } ?>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div class="col-md-4 col-lg-3">
      <div class="card h-100 border-0 shadow-sm bg-black text-light">
        <a href="product.php?id=<?= (int)$row['id']; ?>" class="text-decoration-none text-light">

          <img src="uploads/<?= htmlspecialchars($row['image']); ?>"
               class="card-img-top"
               style="height:240px; object-fit:cover;">

          <div class="card-body text-center">

            <h5 class="fw-bold"><?= htmlspecialchars($row['name']); ?></h5>
            <p class="small text-muted"><?= htmlspecialchars($row['category_name']); ?></p>
            <p class="text-secondary">Weight: <?= (float)$row['weight']; ?> g</p>

            <div class="fw-bold mb-2" style="color:#d4af37;">
              ₹ <?= number_format((float)$row['price'], 2); ?>
            </div>

            <?php if ($row['stock'] > 5) { ?>
                <p class="small text-success mt-2">In Stock</p>
            <?php } elseif ($row['stock'] > 0) { ?>
                <p class="small text-warning mt-2 fw-bold">
                    Only <?= (int)$row['stock']; ?> left!
                </p>
            <?php } else { ?>
                <p class="small text-danger mt-2 fw-bold">
                    Out of Stock
                </p>
            <?php } ?>

          </div>

        </a>
      </div>
    </div>
<?php } ?>

    </div>
  </div>
</section>

<footer class="py-4 text-center bg-black border-top border-secondary">
  <p class="mb-1 text-muted small">
    © <?= date('Y'); ?> SBJ Jewellery. All Rights Reserved.
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>