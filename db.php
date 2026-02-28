<?php
$host = "sql202.byetcluster.com";
$user = "if0_41195486";
$pass = "sbj2026surya";
$dbname = "if0_41195486_sbjdb";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

/* 🔥 FIX TIMEZONE */
mysqli_query($conn, "SET time_zone = '+05:30'");

?>
