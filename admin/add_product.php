<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

include '../db.php';

/* FETCH CATEGORIES */
$cat_result = mysqli_query($conn, "SELECT * FROM categories");

if (isset($_POST['submit'])) {

    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $weight      = mysqli_real_escape_string($conn, $_POST['weight']);
    $price       = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $purity      = isset($_POST['purity']) ? mysqli_real_escape_string($conn, $_POST['purity']) : '';
    $featured    = isset($_POST['featured']) ? 1 : 0;

    /* =========================
       PURITY VALIDATION
    ========================== */

    // If NOT Gold → purity must be empty
    if ($category_id != 1 && !empty($purity)) {
        $error = "❌ Purity is applicable only for Gold category.";
    }

    // If Gold → purity required
    if ($category_id == 1 && empty($purity)) {
        $error = "❌ Please select purity for Gold product.";
    }

    /* =========================
       AUTO GENERATE HUID
       Starts from SBJ00101
    ========================== */

    if (!isset($error)) {

        $get_last = mysqli_query($conn,
            "SELECT huid_code FROM products
             WHERE huid_code IS NOT NULL
             ORDER BY id DESC LIMIT 1");

        if (mysqli_num_rows($get_last) > 0) {
            $row = mysqli_fetch_assoc($get_last);
            $last_huid = $row['huid_code'];
            $number = intval(substr($last_huid, 3));
            $new_number = $number + 1;
        } else {
            $new_number = 101;
        }

        $huid_code = "SBJ" . str_pad($new_number, 5, "0", STR_PAD_LEFT);

        /* IMAGE UPLOAD */
        $image    = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $upload_folder = "../uploads/";
        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        move_uploaded_file($tmp_name, $upload_folder . $image);

        /* INSERT PRODUCT */
        $sql = "INSERT INTO products
                (category_id, name, weight, price, image, description, featured, purity, huid_code)
                VALUES
                ('$category_id', '$name', '$weight', '$price', '$image', '$description', '$featured', 
                 " . ($category_id == 1 ? "'$purity'" : "NULL") . ",
                 '$huid_code')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Product added successfully! Generated HUID: <b>$huid_code</b>";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product | Admin</title>
    <style>
        body {
            background-color: #111;
            font-family: Arial;
            color: white;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 0px 12px #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: none;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        button {
            background: #1e3a8a;
            border: none;
            padding: 12px;
            width: 100%;
            font-weight: bold;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #16213e;
        }

        .msg {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        a {
            color: #1e3a8a;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">

<h2>Add New Jewellery Product</h2>

<?php
if (isset($success)) echo "<div class='msg' style='color:lightgreen;'>$success</div>";
if (isset($error)) echo "<div class='msg' style='color:red;'>$error</div>";
?>

<form method="POST" enctype="multipart/form-data">

    <label>Category:</label>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php while ($cat = mysqli_fetch_assoc($cat_result)) { ?>
            <option value="<?= $cat['id']; ?>">
                <?= $cat['name']; ?>
            </option>
        <?php } ?>
    </select>

    <label>Purity (Gold Only):</label>
    <select name="purity">
        <option value="">Select Purity</option>
        <option value="916">916 Jewellery</option>
        <option value="999">999 Gold Biscuit</option>
    </select>

    <label>Product Name:</label>
    <input type="text" name="name" required>

    <label>Weight (grams):</label>
    <input type="number" step="0.01" name="weight" required>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required>

    <label>Product Image:</label>
    <input type="file" name="image" required>

    <label>Description:</label>
    <textarea name="description"></textarea>

    <div class="checkbox-label">
        <input type="checkbox" name="featured" value="1">
        <label>Show on Homepage (Featured)</label>
    </div>

    <button type="submit" name="submit">Add Product</button>

</form>

<p style="text-align:center; margin-top:15px;">
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</p>

</div>

</body>
</html>
