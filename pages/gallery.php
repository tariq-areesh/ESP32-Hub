<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
include '../includes/header.php';

// Directory for gallery images (relative to this page)
$galleryDir = '../images/gallery-images';
$galleryUrl = '../images/gallery-images';
$imageFiles = [];
$allowedImageExt = ['jpg','jpeg','png','gif','webp'];

if (is_dir($galleryDir)) {
    foreach (scandir($galleryDir) as $file) {
        if ($file[0] === '.') continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $allowedImageExt)) {
            $imageFiles[] = $file;
        }
    }
}

// Determine main image to display
$mainImageSrc = !empty($imageFiles) ? $galleryUrl . '/' . rawurlencode($imageFiles[0]) : '../images/esp32-getting-started.jpg';
$mainImageAlt = !empty($imageFiles) ? 'ESP32 project image: ' . htmlspecialchars(pathinfo($imageFiles[0], PATHINFO_FILENAME)) : 'ESP32 project image';
?>

<!-- Gallery Page -->
<div class="surface">
    <h1>ESP32 Image Gallery</h1>
    <p>
        A gallery of ESP32 boards, breadboard setups, and wiring examples.
    </p>

    <!-- Gallery Section -->
    <div class="gallery-main">
        <img id="gallery-main-image" src="<?php echo $mainImageSrc; ?>" alt="<?php echo $mainImageAlt; ?>">
    </div>
    <hr class="gallery-separator">
    <div class="gallery-grid">
        <?php if (!empty($imageFiles)): ?>
            <?php foreach ($imageFiles as $img): ?>
                <?php $src = $galleryUrl . '/' . rawurlencode($img); ?>
                <img src="<?php echo $src; ?>"
                     data-large="<?php echo $src; ?>"
                     alt="<?php echo htmlspecialchars(pathinfo($img, PATHINFO_FILENAME)); ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <p>No images found. Add JPG/PNG/WebP files to the <code>images/gallery-images</code> folder.</p>
        <?php endif; ?>
    </div>
</div>

<script src="../script/gallery.js"></script>

<?php
include '../includes/footer.php';
?>
