<?php
session_start();

/* 🔐 Admin session check */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

/* 🌍 Proper Timezone */
date_default_timezone_set('Asia/Kolkata');

/* 🚫 Prevent browser cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include '../db.php';

/* =========================
   DATE SELECTION
========================= */
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$selected_date_safe = mysqli_real_escape_string($conn, $selected_date);

/* =========================
   FETCH DASHBOARD STATS
========================= */

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$total_products = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories");
$total_categories = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$total_orders = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($conn, "
    SELECT COUNT(DISTINCT customer_id) AS total 
    FROM orders 
    WHERE customer_id IS NOT NULL
");
$total_customers = mysqli_fetch_assoc($res)['total'] ?? 0;

/* =========================
   🔥 CORRECT REVENUE LOGIC
========================= */

$res = mysqli_query($conn, "
    SELECT COALESCE(SUM(
        CASE

            WHEN payment_method = 'UPI'
                 AND payment_status = 'Paid'
                 THEN total_amount

            WHEN payment_method = 'Cash'
                 AND payment_status = 'Paid'
                 THEN total_amount

            WHEN payment_method = 'EMI'
                 AND payment_status = 'Partially Paid'
                 THEN emi_paid_amount

            WHEN payment_method = 'EMI'
                 AND payment_status = 'Paid'
                 THEN total_amount

            ELSE 0

        END
    ),0) AS revenue
    FROM orders
    WHERE DATE(order_date) = '$selected_date_safe'
");

$selected_revenue = mysqli_fetch_assoc($res)['revenue'] ?? 0;
$formatted_date = date("d M Y", strtotime($selected_date));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | SBJ Jewellery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">

<div class="container py-5">

    <h1 class="fw-bold mb-1">Admin Dashboard</h1>
    <p class="text-muted mb-4">
        Welcome, <?= htmlspecialchars($_SESSION['admin']); ?>
    </p>

    <div class="row">

        <!-- LEFT SIDE -->
        <div class="col-md-7">

            <div class="row g-4 mb-4">

                <div class="col-md-6">
                    <div class="card bg-black border-secondary text-center">
                        <div class="card-body">
                            <h6>Total Products</h6>
                            <h3><?= $total_products ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-black border-secondary text-center">
                        <div class="card-body">
                            <h6>Total Categories</h6>
                            <h3><?= $total_categories ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-black border-secondary text-center">
                        <div class="card-body">
                            <h6>Total Orders</h6>
                            <h3><?= $total_orders ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-black border-secondary text-center">
                        <div class="card-body">
                            <h6>Total Customers</h6>
                            <h3><?= $total_customers ?></h3>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Revenue Section -->
            <div class="card bg-black border-secondary p-4">

                <h4 class="mb-3">Revenue Overview</h4>

                <form method="GET" class="row g-3 align-items-center mb-4">
                    <div class="col-md-6">
                        <input type="date" 
                               name="date" 
                               value="<?= $selected_date ?>" 
                               class="form-control bg-dark text-light border-secondary"
                               required>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-info w-100">View</button>
                    </div>
                </form>

                <div class="text-center">
                    <h6>Revenue (Paid) - <?= $formatted_date ?></h6>
                    <h2>₹ <?= number_format((float)$selected_revenue, 2) ?></h2>
                </div>

            </div>

        </div>

        <!-- RIGHT SIDE CONTROL PANEL -->
        <div class="col-md-5">

            <div class="card bg-black border-secondary p-4">

                <h4 class="mb-4 text-center">Control Panel</h4>

                <a href="add_product.php" class="btn btn-success w-100 mb-3">➕ Add Product</a>

                <a href="products.php" class="btn btn-primary w-100 mb-3">📦 Manage Products</a>

                <a href="orders.php" class="btn btn-warning w-100 mb-3">🧾 Customer Orders</a>

                <a href="customers.php" class="btn btn-info w-100 mb-3">👥 Manage Customers</a>

                <a href="manage_settings.php" class="btn btn-secondary w-100 mb-3">
                    ⚙ GST & Delivery Settings
                </a>

                <a href="admin_settings.php" class="btn btn-dark w-100 mb-3">
                    🔑 Admin Settings
                </a>

                <a href="logout.php" class="btn btn-danger w-100">🚪 Logout</a>

            </div>

        </div>

    </div>

</div>

</body>
</html>
