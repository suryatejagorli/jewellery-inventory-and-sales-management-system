<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

/* Fetch Customers */
$result = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">

<div class="container py-5">

    <h1 class="mb-4">Manage Customers</h1>

    <table class="table table-dark table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>

                    <!-- Email optional -->
                    <td>
                        <?= !empty($row['email']) 
                            ? htmlspecialchars($row['email']) 
                            : '-' ?>
                    </td>

                    <td><?= htmlspecialchars($row['address']); ?></td>

                    <td>
                        <a href="delete_customer.php?id=<?= $row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this customer?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>

        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-warning">
                    No customers found.
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back</a>

</div>

</body>
</html>
