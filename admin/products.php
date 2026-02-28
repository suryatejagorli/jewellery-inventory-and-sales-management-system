<?php
session_start();
include '../db.php';

/* Optional: protect page */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn, "
    SELECT products.*, categories.name AS category_name 
    FROM products 
    JOIN categories ON products.category_id = categories.id
    ORDER BY products.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage Products</h2>
        <a href="add_product.php" class="btn btn-success">
            ➕ Add New Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Weight</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php 
            $i = 1;
            while($row = mysqli_fetch_assoc($result)) { 

                $imagePath = "../uploads/" . $row['image'];
                if (!file_exists($imagePath) || empty($row['image'])) {
                    $imagePath = "../uploads/no-image.png";
                }
            ?>
                <tr>
                    <td><?= $i++; ?></td>

                    <td>
                        <img src="<?= $imagePath; ?>" 
                             width="60" 
                             height="60"
                             class="rounded shadow-sm"
                             style="object-fit:cover;">
                    </td>

                    <td><?= htmlspecialchars($row['name']); ?></td>

                    <td><?= htmlspecialchars($row['category_name']); ?></td>

                    <td><?= $row['weight']; ?> g</td>

                    <td class="fw-bold text-success">
                        ₹ <?= number_format($row['price'], 2); ?>
                    </td>

                    <!-- 🔥 CLICKABLE STOCK -->
                    <td>
                        <a href="update_stock.php?id=<?= $row['id']; ?>" 
                           class="text-decoration-none">

                        <?php if ($row['stock'] > 5) { ?>
                            <span class="badge bg-success p-2">
                                <?= $row['stock']; ?>
                            </span>

                        <?php } elseif ($row['stock'] > 0) { ?>
                            <span class="badge bg-warning text-dark p-2">
                                <?= $row['stock']; ?>
                            </span>

                        <?php } else { ?>
                            <span class="badge bg-danger p-2">
                                Out
                            </span>
                        <?php } ?>

                        </a>
                    </td>

                    <td>
                        <a href="edit_product.php?id=<?= $row['id']; ?>" 
                           class="btn btn-sm btn-primary">
                           Edit
                        </a>

                        <a href="delete_product.php?id=<?= $row['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this product?');">
                           Delete
                        </a>
                    </td>

                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="dashboard.php" class="btn btn-secondary">
            ⬅ Back to Dashboard
        </a>
    </div>

</div>

</body>
</html>
