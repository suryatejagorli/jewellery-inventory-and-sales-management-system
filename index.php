<?php
session_start();

/* =========================
   PREVENT CUSTOMER RE-ENTRY
========================= */
if (isset($_SESSION['customer'])) {
    header("Location: shop.php");
    exit();
}

/* Prevent browser caching */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SBJ Jewellery | Home</title>

    <!-- Mobile Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #121212;
            color: #f1f1f1;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .brand-title {
            font-weight: 700;
            letter-spacing: 2px;
        }

        .subtitle {
            color: #aaa;
        }

        .btn-success {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .btn-outline-warning {
            border-color: #d4af37;
            color: #d4af37;
        }

        .btn-outline-warning:hover {
            background-color: #d4af37;
            color: #000;
        }

        /* Mobile Adjustments Only */
        @media (max-width: 768px) {

            body {
                padding: 20px;
            }

            .brand-title {
                font-size: 1.8rem;
            }

            .btn-lg {
                font-size: 1rem;
                padding: 14px;
            }

            .btn-outline-warning {
                width: 100% !important;
            }

            .content-box {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container content-box">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">

            <h1 class="brand-title mb-2">
                SBJ <span style="color:#d4af37;">Jewellery</span>
            </h1>

            <p class="subtitle mb-5">
                Jewellery Inventory & Sales Management System
            </p>

            <!-- CUSTOMER -->
            <div class="mb-5">
                <a href="shop.php" class="btn btn-success btn-lg w-100 py-3">
                    🛍 Continue as Customer
                </a>
                <small class="text-muted d-block mt-2">
                    Browse and purchase jewellery
                </small>
            </div>

            <!-- ADMIN -->
            <div class="mb-4">
                <a href="admin/login.php" class="btn btn-outline-warning w-75">
                    🔐 Admin Login
                </a>
                <small class="text-muted d-block mt-2">
                    Inventory & order management
                </small>
            </div>

            <!-- CONTACT -->
            <div class="mt-3">
                <a href="contact.php" class="text-decoration-none text-secondary">
                    📞 Contact / Help
                </a>
                <div class="small text-muted mt-1">
                    Authorized staff only
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
