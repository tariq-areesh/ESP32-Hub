<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';
include '../includes/header.php';

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = isset($_POST["category_id"]) ? (int) $_POST["category_id"] : 0;
    $title = trim($_POST["title"] ?? "");
    $difficulty = trim($_POST["difficulty"] ?? "");
    $estimated_time = trim($_POST["estimated_time"] ?? "");
    $short_description = trim($_POST["short_description"] ?? "");

    if ($category_id <= 0) {
        $errors[] = "Please choose a category.";
    }
    if ($title === "") {
        $errors[] = "Project title is required.";
    }
    if ($difficulty === "") {
        $errors[] = "Please choose a difficulty level.";
    }
    if ($estimated_time === "") {
        $errors[] = "Estimated time is required.";
    }
    // Insert into database if no errors
    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO projects (category_id, title, difficulty, estimated_time, short_description)
                                  VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issss", $category_id, $title, $difficulty, $estimated_time, $short_description);
            if ($stmt->execute()) {
                $success = "Project added successfully.";
                // clear fields after success
                $title = $difficulty = $estimated_time = $short_description = "";
                $category_id = 0;
            } else {
                $errors[] = "Database error while inserting project.";
            }
            $stmt->close();
        } else {
            $errors[] = "Could not prepare database statement.";
        }
    }
}

// Fetch categories for dropdown
$categories = [];
if ($result = $mysqli->query("SELECT category_id, category_name FROM categories ORDER BY category_name")) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    $result->free();
}
?>
<!-- Add Project Form -->
<div class="auth-page-wrapper">
  <div class="surface auth-card">
    <h1>Add ESP32 Project</h1>

    <?php if (!empty($success)): ?>
      <div class="success">
        <?php echo htmlspecialchars($success); ?>
      </div>
    <?php endif; ?>
<!-- Display errors if any -->
    <?php if (!empty($errors)): ?>
      <div class="error">
        <?php foreach ($errors as $msg): ?>
          <p><?php echo htmlspecialchars($msg); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
<!-- Project submission form -->
    <form method="post" action="">
      <label for="category_id">Category *</label>
      <select id="category_id" name="category_id" required>
        <option value="">Select a category</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo (int)$cat["category_id"]; ?>"
            <?php if (!empty($category_id) && (int)$category_id === (int)$cat["category_id"]) echo "selected"; ?>>
            <?php echo htmlspecialchars($cat["category_name"]); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="title">Title *</label>
      <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title ?? ""); ?>" required>

      <label for="difficulty">Difficulty *</label>
      <select id="difficulty" name="difficulty" required>
        <?php
        $difficulties = ["Beginner", "Intermediate", "Advanced"];
        $currentDiff = $difficulty ?? "";
        foreach ($difficulties as $diff):
        ?>
          <option value="<?php echo $diff; ?>" <?php if ($currentDiff === $diff) echo "selected"; ?>>
            <?php echo $diff; ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="estimated_time">Estimated Time *</label>
      <input type="text" id="estimated_time" name="estimated_time"
             placeholder="e.g., 45 minutes"
             value="<?php echo htmlspecialchars($estimated_time ?? "45 minutes"); ?>" required>

      <label for="short_description">Short Description</label>
      <textarea id="short_description" name="short_description" rows="3"><?php
        echo htmlspecialchars($short_description ?? "");
      ?></textarea>

      <button type="submit" class="btn auth-btn">Add Project</button>
    </form>
  </div>
</div>

<?php
include '../includes/footer.php';
?>
