<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* CART COUNT */
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = array_sum($_SESSION['cart']);
}

$category = $_GET['category'] ?? '';
?>

<style>
/* ========== MOBILE BOTTOM NAVBAR ========== */
@media (max-width: 991px) {

    /* Hide top navbar buttons on mobile */
    .desktop-buttons {
        display: none !important;
    }

    body {
        padding-bottom: 70px; /* space for bottom nav */
    }

    .mobile-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #000;
        border-top: 1px solid #333;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 8px 0;
        z-index: 1050;
    }

    .mobile-bottom-nav a {
        color: #fff;
        text-decoration: none;
        font-size: 18px;
        text-align: center;
        position: relative;
    }

    .mobile-bottom-nav a:hover {
        color: #d4af37;
    }

    .mobile-bottom-nav .badge {
        font-size: 10px;
    }
}

/* Hide bottom nav on desktop */
@media (min-width: 992px) {
    .mobile-bottom-nav {
        display: none;
    }
}
</style>

<!-- TOP NAVBAR (UNCHANGED FOR LAPTOP) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm sticky-top">
  <div class="container">

    <a class="navbar-brand fw-bold" href="shop.php">
      SBJ Jewellery
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navBar">

      <!-- LEFT SIDE -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?php if($category=='') echo 'active'; ?>" href="shop.php">All</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($category=='Gold') echo 'active'; ?>" href="shop.php?category=Gold">Gold</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($category=='Silver') echo 'active'; ?>" href="shop.php?category=Silver">Silver</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($category=='Diamond') echo 'active'; ?>" href="shop.php?category=Diamond">Diamond</a>
        </li>
      </ul>

      <!-- SEARCH -->
      <form method="GET" class="d-flex me-3">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category); ?>">
        <input class="form-control form-control-sm me-2"
               type="search"
               name="search"
               placeholder="Search jewellery..."
               value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
        <button class="btn btn-outline-light btn-sm">🔍</button>
      </form>

      <!-- RIGHT BUTTONS (DESKTOP ONLY) -->
      <div class="desktop-buttons d-flex align-items-center">

        <a href="order_history.php" class="btn btn-outline-light me-2">
          📜 Order History
        </a>

        <a href="help.php" class="btn btn-outline-secondary me-2">
          ☎ Help
        </a>

        <a href="cart.php" class="btn btn-outline-light position-relative me-2">
          🛒 Cart
          <?php if ($cart_count > 0) { ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?= $cart_count; ?>
            </span>
          <?php } ?>
        </a>

        <a href="customer_logout.php" class="btn btn-outline-danger">
          ⬅ Exit
        </a>

      </div>

    </div>
  </div>
</nav>

<!-- MOBILE BOTTOM NAVIGATION -->
<div class="mobile-bottom-nav">

    <a href="shop.php">🏠</a>

    <a href="order_history.php">📜</a>

    <a href="cart.php" class="position-relative">
        🛒
        <?php if ($cart_count > 0) { ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?= $cart_count; ?>
            </span>
        <?php } ?>
    </a>

    <a href="help.php">☎</a>

    <a href="customer_logout.php">⬅</a>

</div>
