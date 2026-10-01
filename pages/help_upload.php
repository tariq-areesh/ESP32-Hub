<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/auth_check.php';
require '../includes/db_connect.php';

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $user_id = $_SESSION["user_id"];

    if ($title === "") $errors[] = "Title is required.";
    if ($description === "") $errors[] = "Description is required.";

    // Handle file upload if provided
    $file_path = null;
    if (!empty($_FILES["code_file"]["name"])) {
        $upload_dir = "../uploads/";
        $allowed_ext = ["ino", "c", "cpp", "txt", "py", "zip"];
        $original_name = $_FILES["code_file"]["name"];
        $size = $_FILES["code_file"]["size"];
        $tmp_name = $_FILES["code_file"]["tmp_name"];

        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            $errors[] =("Invalid file type. Allowed types: " . implode(", ", $allowed_ext) . ".");
        } elseif ($size > 2 * 1024 * 1024) {
            $errors[] = "File is too large. Maximum size is 2 MB.";
        } else {
            $safe_name = uniqid("code_", true) . "." . $ext;
            $destination = $upload_dir . $safe_name;
            if (move_uploaded_file($tmp_name, $destination)) {
                $file_path = $safe_name;
            } else {
                $errors[] = "Failed to upload file.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO help_requests (user_id, title, description, file_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $description, $file_path);
        if ($stmt->execute()) {
            $success = "Help request created successfully.";
            $title = $description = "";
        } else {
            $errors[] = "Failed to create help request.";
        }
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<!-- Help Request Upload Form -->
<div class="surface">
    <h1>New Code Help Request</h1>
    <p>
        Upload an ESP32 code file (optional) and describe your problem. Other registered users can view your
        request and submit solutions.
    </p>

    <!-- Display Success or Error Messages -->
    <?php if (!empty($success)): ?>
        <div class="success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $err): ?>
                <p><?php echo htmlspecialchars($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="help_upload.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Problem Details</legend>

            <label for="title">Title *</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title ?? ""); ?>">

            <label for="description">Description *</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($description ?? ""); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Code File (optional)</legend>

            <label for="code_file">Upload ESP32 code file (ino, c, cpp, txt, py, zip) - max 2 MB</label>
            <input type="file" id="code_file" name="code_file" accept=".ino,.c,.cpp,.txt,.py,.zip">
        </fieldset>

        <button type="submit" class="btn">Create Help Request</button>
    </form>
</div>

<?php
include '../includes/footer.php';
?>
