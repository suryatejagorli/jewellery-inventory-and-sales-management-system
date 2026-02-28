<?php
session_start();

/* 🔐 Admin session check */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* 🚫 Prevent browser back-button cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

include '../db.php';

/* =========================
   GET PRODUCT ID
========================= */
$id = intval($_GET['id']);

/* =========================
   FETCH EXISTING PRODUCT
========================= */
$res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($res);

if (!$product) {
    header("Location: products.php");
    exit;
}

/* =========================
   FETCH CATEGORIES
========================= */
$cat_res = mysqli_query($conn, "SELECT * FROM categories");

/* =========================
   UPDATE LOGIC
========================= */
if (isset($_POST['update'])) {

    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $weight      = mysqli_real_escape_string($conn, $_POST['weight']);
    $price       = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    /* Featured checkbox */
    $featured = isset($_POST['featured']) ? 1 : 0;

    /* Image update (optional) */
    if (!empty($_FILES['image']['name'])) {

        $image = $_FILES['image']['name'];
        $tmp   = $_FILES['image']['tmp_name'];

        if (!is_dir("../uploads")) {
            mkdir("../uploads", 0777, true);
        }

        move_uploaded_file($tmp, "../uploads/$image");

        mysqli_query($conn, "
            UPDATE products 
            SET name='$name', 
                category_id='$category_id', 
                weight='$weight', 
                price='$price', 
                description='$description', 
                image='$image',
                featured='$featured'
            WHERE id=$id
        ");

    } else {

        mysqli_query($conn, "
            UPDATE products 
            SET name='$name', 
                category_id='$category_id', 
                weight='$weight', 
                price='$price', 
                description='$description',
                featured='$featured'
            WHERE id=$id
        ");
    }

    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4">Edit Product</h2>

    <form method="post" enctype="multipart/form-data" class="bg-white p-4 border rounded">

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($product['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                <?php while ($cat = mysqli_fetch_assoc($cat_res)) { ?>
                    <option value="<?= $cat['id']; ?>"
                        <?php if ($cat['id'] == $product['category_id']) echo 'selected'; ?>>
                        <?= htmlspecialchars($cat['name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Weight (grams)</label>
            <input type="number" step="0.01" name="weight" class="form-control"
                   value="<?= htmlspecialchars($product['weight']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control"
                   value="<?= htmlspecialchars($product['price']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description']); ?></textarea>
        </div>

        <!-- ⭐ FEATURED PRODUCT -->
        <div class="mb-3">
            <label class="form-label">Show on Homepage (Featured)</label><br>
            <input type="checkbox" name="featured" value="1"
                <?php if ($product['featured'] == 1) echo 'checked'; ?>>
            <span>Mark as Featured Product</span>
        </div>

        <div class="mb-3">
            <label>Current Image</label><br>
            <img src="../uploads/<?= htmlspecialchars($product['image']); ?>" width="120">
        </div>

        <div class="mb-3">
            <label>Change Image (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" name="update" class="btn btn-success">Update Product</button>
        <a href="products.php" class="btn btn-secondary">Cancel</a>

    </form>
</div>

</body>
</html>
