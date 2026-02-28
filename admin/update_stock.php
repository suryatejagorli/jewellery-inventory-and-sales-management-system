<?php
session_start();
include '../db.php';

/* 🔐 Admin Protection */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int) $_GET['id'];

/* Fetch Product */
$product_q = mysqli_query($conn, "SELECT name, stock FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($product_q);

if (!$product) {
    die("Product not found.");
}

$current_stock = $product['stock'];

/* Handle Update */
if (isset($_POST['update_stock'])) {

    $new_stock = (int) $_POST['new_stock'];

    if ($new_stock < 0) {
        $error = "Stock cannot be negative.";
    } else {

        mysqli_query($conn, "UPDATE products SET stock = $new_stock WHERE id = $id");
        header("Location: products.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Stock</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
<div class="card shadow mx-auto" style="max-width:500px;">
<div class="card-body">

<h4 class="fw-bold mb-3 text-center">Update Stock</h4>

<p class="text-center text-muted">
Product: <strong><?= htmlspecialchars($product['name']); ?></strong>
</p>

<hr>

<?php if(isset($error)) { ?>
<div class="alert alert-danger"><?= $error; ?></div>
<?php } ?>

<form method="post">

<div class="mb-3">
<label class="form-label">Current Stock</label>
<input type="text" class="form-control" value="<?= $current_stock; ?>" disabled>
</div>

<div class="mb-3">
<label class="form-label">New Stock Quantity</label>
<input type="number" name="new_stock" 
       class="form-control" 
       min="0" 
       value="<?= $current_stock; ?>" 
       required>
</div>

<button type="submit" name="update_stock" class="btn btn-dark w-100">
Update Stock
</button>

</form>

<div class="text-center mt-3">
<a href="products.php" class="btn btn-outline-secondary">
⬅ Back to Products
</a>
</div>

</div>
</div>
</div>

</body>
</html>
