<?php
include '../db.php';

$id = $_GET['id'];

// Get image name
$res = mysqli_query($conn, "SELECT image FROM products WHERE id=$id");
$row = mysqli_fetch_assoc($res);

if($row) {
    $image = $row['image'];

    // Delete image file
    if(file_exists("../uploads/$image")) {
        unlink("../uploads/$image");
    }

    // Delete from database
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
}

header("Location: products.php");
exit;
