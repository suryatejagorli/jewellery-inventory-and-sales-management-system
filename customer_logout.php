<?php
session_start();

/* =========================
   CUSTOMER LOGOUT
========================= */

/* Remove only customer session flag */
if (isset($_SESSION['customer'])) {
    unset($_SESSION['customer']);
}

/* Optional: Clear cart completely (uncomment if needed) */
/*
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}
*/

/* Regenerate session ID for security */
session_regenerate_id(true);

/* Prevent caching after logout */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

/* Redirect to entry / shop page */
header("Location: index.php");
exit();
