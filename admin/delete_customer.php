<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../db.php';

/* Validate ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: customers.php");
    exit();
}

$customer_id = (int) $_GET['id'];

/* Check if customer exists */
$check = mysqli_query($conn, "SELECT id FROM customers WHERE id = $customer_id");

if (!$check || mysqli_num_rows($check) == 0) {
    header("Location: customers.php?error=notfound");
    exit();
}

/* Delete Customer (Cascade will handle related data) */
$delete = mysqli_query($conn, "DELETE FROM customers WHERE id = $customer_id");

if ($delete) {
    header("Location: customers.php?success=deleted");
    exit();
} else {
    header("Location: customers.php?error=failed");
    exit();
}
?>
