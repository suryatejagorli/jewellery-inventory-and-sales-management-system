<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <!-- ✅ Correct Mobile Responsive Setting -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>SBJ Jewellery</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* ===== GLOBAL DARK THEME ===== */
    html, body {
        background-color: #121212;
        color: #f1f1f1;
        min-height: 100vh;
        overflow-x: hidden; /* prevents desktop squeeze on mobile */
    }

    /* Prevent horizontal scroll issues */
    * {
        max-width: 100%;
    }

    /* Cards */
    .card {
        background-color: #1a1a1a !important;
        color: #f1f1f1;
        border: none;
    }

    /* Forms */
    .form-control {
        background-color: #1e1e1e;
        color: #fff;
        border: 1px solid #333;
    }

    .form-control:focus {
        background-color: #1e1e1e;
        color: #fff;
        border-color: #d4af37;
        box-shadow: none;
    }

    /* Buttons Hover */
    .btn-outline-light:hover {
        background-color: #d4af37;
        color: #000;
        border-color: #d4af37;
    }

    /* Footer */
    footer {
        background-color: #000;
    }

    /* ===== MOBILE FIX ONLY (NO LAPTOP CHANGE) ===== */
    @media (max-width: 991px) {

        /* Force proper width on mobile */
        .container {
            max-width: 100% !important;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Improve spacing */
        h1, h2, h3 {
            font-size: 1.4rem;
        }

        .card-img-top {
            height: auto !important;
        }
    }
  </style>
</head>
<body>
