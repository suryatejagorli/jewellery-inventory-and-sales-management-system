<?php
include '../includes/contact_data.php';

/* =========================
   SAVE UPDATED CONTACT DATA
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $content = "<?php\n\$contact = " . var_export([

        'project_title' => $_POST['project_title'],

        'student1' => [
            'name'  => $_POST['s1_name'],
            'reg'   => $_POST['s1_reg'],
            'phone' => $_POST['s1_phone'],
            'email' => $_POST['s1_email']
        ],

        'student2' => [
            'name'  => $_POST['s2_name'],
            'reg'   => $_POST['s2_reg'],
            'phone' => $_POST['s2_phone'],
            'email' => $_POST['s2_email']
        ]

    ], true) . ";\n?>";

    file_put_contents('../includes/contact_data.php', $content);

    header("Location: help.php?saved=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Contact / Help</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">

<div class="container py-5">

<h3 class="mb-4">✏️ Edit Contact / Help</h3>

<form method="post">

<div class="mb-3">
<label class="form-label">Project Title</label>
<input class="form-control" name="project_title"
       value="<?= htmlspecialchars($contact['project_title']); ?>" required>
</div>

<hr>

<div class="row">
<div class="col-md-6">
<h5>Student 1</h5>
<input class="form-control mb-2" name="s1_name"
       value="<?= htmlspecialchars($contact['student1']['name']); ?>" required>
<input class="form-control mb-2" name="s1_reg"
       value="<?= htmlspecialchars($contact['student1']['reg']); ?>" required>
<input class="form-control mb-2" name="s1_phone"
       value="<?= htmlspecialchars($contact['student1']['phone']); ?>" required>
<input class="form-control mb-2" name="s1_email"
       value="<?= htmlspecialchars($contact['student1']['email']); ?>" required>
</div>

<div class="col-md-6">
<h5>Student 2</h5>
<input class="form-control mb-2" name="s2_name"
       value="<?= htmlspecialchars($contact['student2']['name']); ?>" required>
<input class="form-control mb-2" name="s2_reg"
       value="<?= htmlspecialchars($contact['student2']['reg']); ?>" required>
<input class="form-control mb-2" name="s2_phone"
       value="<?= htmlspecialchars($contact['student2']['phone']); ?>" required>
<input class="form-control mb-2" name="s2_email"
       value="<?= htmlspecialchars($contact['student2']['email']); ?>" required>
</div>
</div>

<button class="btn btn-success mt-4">💾 Save Changes</button>
<a href="../index.php" class="btn btn-secondary mt-4 ms-2">⬅ Back</a>

</form>

</div>

</body>
</html>
