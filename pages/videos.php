<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
include '../includes/header.php';

// Directory for local tutorial videos (relative to this page)
$videoDir = '../videos/tutorial-videos';
$videoUrl = '../videos/tutorial-videos';
$videoFiles = [];
$allowedVideoExt = ['mp4','webm','ogg'];
$mimeTypes = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];

// Scan the video directory for video files
if (is_dir($videoDir)) {
    foreach (scandir($videoDir) as $file) {
        if ($file[0] === '.') continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $allowedVideoExt)) {
            $videoFiles[] = $file;
        }
    }
}
?>
<!-- Videos Page -->
<div class="surface">
    <h1>ESP32 Tutorial Videos</h1>

    <!-- Video Section -->
    <div class="video-wrapper">
        <!-- YouTube Video Embed -->
        <div class="video-card">
            <h2>Setting up an ESP32 with Arduino IDE</h2>
            <div class="video-embed">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/CD8VJl27n94?si=9hYEKHJH68NEKXRH" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </div>
                    
        <!-- Local Tutorial Videos -->
        <?php if (!empty($videoFiles)): ?>
            <?php foreach ($videoFiles as $video): ?>
                <?php
                    $ext = strtolower(pathinfo($video, PATHINFO_EXTENSION));
                    $mime = isset($mimeTypes[$ext]) ? $mimeTypes[$ext] : 'video/mp4';
                    $src = $videoUrl . '/' . rawurlencode($video);
                    $title = pathinfo($video, PATHINFO_FILENAME);
                ?>
                <div class="video-card">
                    <h2><?php echo htmlspecialchars($title); ?></h2>
                    <video controls>
                        <source src="<?php echo $src; ?>" type="<?php echo $mime; ?>">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="video-card">
                <h2>No Local Tutorial Videos Yet</h2>
                <p class="text-subtle">
                    Add <code>.mp4</code>, <code>.webm</code>, or <code>.ogg</code> files to the
                    <code>videos/tutorial-videos</code> folder to show them here.
                </p>
            </div>
        <?php endif; ?>
            <!-- YouTube Video Embed -->
          <div class="video-card">
            <h2>How to use ESP32 WiFi and Bluetooth with Arduino IDE full details with examples - RJT211</h2>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/--Fj8QDlGuQ?si=t8qWZC8pZ2xMJ3yg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
          </div>

    </div>
</div>

<?php
include '../includes/footer.php';
?>
