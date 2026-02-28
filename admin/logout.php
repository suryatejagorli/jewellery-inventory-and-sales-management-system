<?php
session_start();

/* Destroy admin session completely */
unset($_SESSION['admin']);
session_destroy();

/* Kill browser cache */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* Redirect to HOME page (not admin login) */
header("Location: ../index.php");
exit;
