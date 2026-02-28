<?php
include '../db.php';

// Add category
if (isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
    header("Location: categories.php");
    exit;
}

// Delete category
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id=$id");
    header("Location: categories.php");
    exit;
}

// Fetch categories
$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">

    <h2 class="mb-4">Manage Categories</h2>

    <!-- Add Category -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control" placeholder="Enter Category Name" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="add_category" class="btn btn-success w-100">
                        Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category List -->
    <table class="table table-bordered bg-white">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=1; while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <a href="categories.php?delete=<?php echo $row['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this category?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>

</div>

</body>
</html>
