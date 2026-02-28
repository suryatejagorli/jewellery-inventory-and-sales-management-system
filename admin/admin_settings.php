<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$msg = "";

if (isset($_POST['update'])) {

    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);

    if ($new_username === "" || $new_password === "") {

        $msg = "<div class='alert alert-danger text-center'>
                All fields are required.
                </div>";

    } else {

        $new_username = mysqli_real_escape_string($conn, $new_username);

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update = mysqli_query($conn,
            "UPDATE admins 
             SET username='$new_username', password='$hashed_password'
             WHERE id=3"
        );

        if ($update) {

            $_SESSION['admin'] = $new_username;

            $msg = "<div class='alert alert-success text-center'>
                    Credentials updated successfully.
                    </div>";

        } else {

            $msg = "<div class='alert alert-danger text-center'>
                    Something went wrong. Try again.
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Settings | SBJ Jewellery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">

<div class="container py-5" style="max-width:500px;">

    <h3 class="text-center mb-4">🔐 Admin Settings</h3>

    <?php if ($msg !== "") echo $msg; ?>

    <form method="post" class="card bg-black p-4 shadow">

        <div class="mb-3">
            <label class="form-label">New Username</label>
            <input type="text"
                   name="username"
                   class="form-control"
                   value="<?php echo htmlspecialchars($_SESSION['admin']); ?>"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   required>
        </div>

        <button type="submit"
                name="update"
                class="btn btn-success w-100">
            Update Credentials
        </button>

        <a href="dashboard.php"
           class="btn btn-secondary w-100 mt-3">
            ⬅ Back to Dashboard
        </a>

    </form>

</div>

</body>
</html>
