<?php
session_start();
include '../db.php';

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {
        $error = "All fields are required.";
    } else {

        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password'])) {

                $_SESSION['admin'] = $admin['username'];
                header("Location: dashboard.php");
                exit;

            } else {
                $error = "Invalid Username or Password";
            }

        } else {
            $error = "Invalid Username or Password";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login | SBJ Jewellery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#0f0f0f; }
.login-box {
    max-width:400px;
    margin:120px auto;
    padding:30px;
    background:#111;
    border-radius:10px;
    box-shadow:0 0 15px rgba(255,255,255,0.08);
}
</style>
</head>
<body class="text-light">
<div class="login-box">
    <h4 class="text-center mb-4">🔐 Admin Login</h4>

    <?php if ($error != "") { ?>
        <div class="alert alert-danger text-center">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="post">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-success w-100">
            Login
        </button>
    </form>
</div>
</body>
</html>
