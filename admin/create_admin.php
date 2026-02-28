<?php
include '../db.php';

$username = "admin";
$password = "admin123";   // ← you will login with this

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

mysqli_query($conn, "
    INSERT INTO admins (username, password)
    VALUES ('$username', '$hashed_password')
");

echo "Admin Created Successfully!";
?>
