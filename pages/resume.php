<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
include '../includes/header.php';
?>

<!-- Resume Page -->
<div class="surface">
    <h1>Our Resume</h1>

    <!-- Resume Embed -->
    <div class="card">
        <p>
            You can view our resume below. If your browser does not support embedded PDFs,
            you can download it using the link provided.
        </p>
        <object data="../files/resume.pdf" type="application/pdf" width="100%" height="600">
            <p>
                Your browser does not support embedded PDFs. You can
                <a href="../files/resume.pdf" target="_blank" rel="noopener">download the resume instead</a>.
            </p>
        </object>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
