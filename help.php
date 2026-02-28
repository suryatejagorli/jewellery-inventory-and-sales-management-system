<?php
session_start();
include 'includes/contact_data.php';
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<style>
body {
    background-color: #121212;
    color: #f1f1f1;
}

.card-custom {
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 20px;
    padding: 30px;
    transition: all 0.35s ease;
}

.card-custom:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
}

.section-title {
    color: #d4af37;
    font-weight: 700;
    letter-spacing: 1px;
}

.sub-text {
    color: #aaaaaa;
    font-size: 15px;
}

.label-text {
    color: #888;
    font-size: 13px;
    margin-bottom: 4px;
}

.value-text {
    font-weight: 500;
    margin-bottom: 18px;
}

.divider-line {
    height: 2px;
    width: 60px;
    background: #d4af37;
    margin: 15px auto 0;
}

.btn-outline-warning {
    border-color: #d4af37;
    color: #d4af37;
}

.btn-outline-warning:hover {
    background: #d4af37;
    color: #000;
}
</style>

<div class="container py-5">

    <!-- PAGE TITLE -->
    <div class="text-center mb-5">
        <h2 class="section-title">Project Information & Support</h2>
        <p class="sub-text">
            Jewellery Inventory & Sales Management System
        </p>
        <div class="divider-line"></div>
    </div>

    <!-- TEAM SECTION -->
    <div class="row justify-content-center g-4">

        <!-- Member 1 -->
        <div class="col-md-5">
            <div class="card card-custom h-100">
                <h5 class="text-warning mb-4">Team Member</h5>

                <div class="label-text">Name</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student1']['name']) ?>
                </div>

                <div class="label-text">Registration Number</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student1']['reg']) ?>
                </div>

                <div class="label-text">Phone</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student1']['phone']) ?>
                </div>

                <div class="label-text">Email</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student1']['email']) ?>
                </div>
            </div>
        </div>

        <!-- Member 2 -->
        <div class="col-md-5">
            <div class="card card-custom h-100">
                <h5 class="text-warning mb-4">Team Member</h5>

                <div class="label-text">Name</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student2']['name']) ?>
                </div>

                <div class="label-text">Registration Number</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student2']['reg']) ?>
                </div>

                <div class="label-text">Phone</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student2']['phone']) ?>
                </div>

                <div class="label-text">Email</div>
                <div class="value-text">
                    <?= htmlspecialchars($contact['student2']['email']) ?>
                </div>
            </div>
        </div>

    </div>

    <hr class="my-5 border-secondary">

    <div class="text-center text-muted">
        For project-related queries, feel free to reach out using the above contact details.
    </div>

    <div class="text-center mt-4">
        <a href="shop.php" class="btn btn-outline-warning px-4 py-2">
            Back to Home
        </a>
    </div>

</div>

<?php include('includes/footer.php'); ?>