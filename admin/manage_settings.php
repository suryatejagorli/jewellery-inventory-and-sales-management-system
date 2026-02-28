<?php
session_start();
include '../db.php';

/* 🔐 Admin Session Check */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   FETCH CURRENT SETTINGS
========================= */
$settings = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM settings WHERE id = 1")
);

/* =========================
   UPDATE SETTINGS
========================= */
if (isset($_POST['update_settings'])) {

    $gst = floatval($_POST['gst_percent']);
    $delivery = floatval($_POST['delivery_charge']);

    mysqli_query($conn, "
        UPDATE settings 
        SET gst_percent = $gst,
            delivery_charge = $delivery
        WHERE id = 1
    ");

    header("Location: manage_settings.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage GST & Delivery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
<h3 class="mb-4">⚙ Manage GST & Delivery Charges</h3>

<?php if (isset($_GET['updated'])) { ?>
<div class="alert alert-success">
    ✅ Settings updated successfully
</div>
<?php } ?>

<form method="post" class="bg-white p-4 rounded shadow-sm">

    <div class="mb-3">
        <label class="form-label">GST Percentage (%)</label>
        <input type="number"
               step="0.01"
               name="gst_percent"
               class="form-control"
               value="<?= $settings['gst_percent']; ?>"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Delivery Charge (₹)</label>
        <input type="number"
               step="0.01"
               name="delivery_charge"
               class="form-control"
               value="<?= $settings['delivery_charge']; ?>"
               required>
    </div>

    <button type="submit" name="update_settings" class="btn btn-primary">
        💾 Save Changes
    </button>

    <a href="dashboard.php" class="btn btn-secondary ms-2">
        ⬅ Back to Dashboard
    </a>

</form>
</div>

</body>
</html>
