<?php
session_start();
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height:80vh;">
    <div class="text-center">

        <h2 class="fw-bold mb-3" style="color:#d4af37;">
            Contact / Help
        </h2>

        <p class="text-muted mb-4">
            For assistance regarding orders, payments, or inventory.
        </p>

        <div class="mb-3">
            📍 <strong>Address:</strong> SBJ Jewellery, Main Road
        </div>

        <div class="mb-3">
            📞 <strong>Phone:</strong> +91 9XXXXXXXXX
        </div>

        <div class="mb-4">
            ✉️ <strong>Email:</strong> support@sbjjewellery.com
        </div>

        <p class="text-warning small">
            *Only authorized staff can modify details.
        </p>

        <a href="admin/login.php" class="btn btn-outline-warning mt-3">
            🔐 Staff Login
        </a>

        <div class="mt-4">
            <a href="shop.php" class="text-secondary text-decoration-none">
                ← Back to Home
            </a>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>
