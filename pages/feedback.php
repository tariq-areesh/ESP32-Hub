<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';

$success_message = "";
$error_messages = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $project_title = trim($_POST["project_title"] ?? "");
    $experience_level = trim($_POST["experience_level"] ?? "");
    $feedback_type = $_POST["feedback_type"] ?? "";
    $improvements = $_POST["improvements"] ?? [];
    $source = $_POST["source"] ?? "";
    $message = trim($_POST["message"] ?? "");
    // Validate inputs
    if ($name === "") $error_messages[] = "Name is required.";
    if ($email === "") {
        $error_messages[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Invalid email format.";
    }
    if ($project_title === "") $error_messages[] = "Project title is required.";
    if ($experience_level === "") $error_messages[] = "Experience level is required.";
    if ($feedback_type === "") $error_messages[] = "Please select a feedback type.";
    if (empty($improvements)) $error_messages[] = "Please select at least one improvement option.";
    if ($source === "") $error_messages[] = "Please select how you heard about ESP32 Hub.";
    if ($message === "") $error_messages[] = "Feedback message cannot be empty.";

    if (empty($error_messages)) {
        $check = $mysqli->prepare("SELECT feedback_id FROM feedback WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error_messages[] = "This email has already been used to submit feedback.";
        } else {
            $check->close();
            $improvements_str = implode(", ", $improvements);

            $stmt = $mysqli->prepare("INSERT INTO feedback (name, email, project_title, experience_level, feedback_type, improvements, source, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $name, $email, $project_title, $experience_level, $feedback_type, $improvements_str, $source, $message);

            if ($stmt->execute()) {
                $success_message = "Thank you! Your feedback has been saved.";
                $name = $email = $project_title = $experience_level = $feedback_type = $source = $message = "";
                $improvements = [];
            } else {
                $error_messages[] = "Failed to save feedback. Please try again later.";
            }

            $stmt->close();
        }
    }
}

include '../includes/header.php';
?>

<!-- Feedback Form -->
<div class="surface">
    <h1>Feedback</h1>
    <p>
        Use this form to send feedback about ESP32 Hub or to suggest new ESP32 project ideas. All fields marked
        with * are mandatory. Client-side JavaScript validation is used, and the data is also validated and
        stored on the server side in the database.
    </p>

    <?php if (!empty($success_message)): ?>
        <div class="success">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_messages)): ?>
        <div class="error">
            <?php foreach ($error_messages as $err): ?>
                <p><?php echo htmlspecialchars($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div id="feedback-errors" class="error" style="display:none;"></div>

    <form id="feedback-form" method="post" action="feedback.php" novalidate>
        <fieldset>
            <legend>Basic Information</legend>

            <label for="name">Name *</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ""); ?>">

            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ""); ?>">

            <label for="project_title">Project Title (or topic) *</label>
            <input type="text" id="project_title" name="project_title" value="<?php echo htmlspecialchars($project_title ?? ""); ?>">

            <label for="experience_level">ESP32 Experience Level *</label>
            <input type="text" id="experience_level" name="experience_level" placeholder="e.g., Beginner, Intermediate, Advanced" value="<?php echo htmlspecialchars($experience_level ?? ""); ?>">
        </fieldset>

        <fieldset>
            <legend>Feedback Details</legend>

            <label>Feedback Type *</label>
            <label><input type="radio" name="feedback_type" value="Bug" <?php if (($feedback_type ?? "") === "Bug") echo "checked"; ?>> Bug report</label>
            <label><input type="radio" name="feedback_type" value="Suggestion" <?php if (($feedback_type ?? "") === "Suggestion") echo "checked"; ?>> Suggestion</label>

            <label>What should we improve? *</label>
            <?php $improv = $improvements ?? []; ?>
            <label><input type="checkbox" name="improvements[]" value="More beginner projects" <?php if (in_array("More beginner projects", $improv)) echo "checked"; ?>> More beginner projects</label>
            <label><input type="checkbox" name="improvements[]" value="More diagrams" <?php if (in_array("More diagrams", $improv)) echo "checked"; ?>> More diagrams</label>
            <label><input type="checkbox" name="improvements[]" value="More troubleshooting tips" <?php if (in_array("More troubleshooting tips", $improv)) echo "checked"; ?>> More troubleshooting tips</label>

            <label for="source">How did you hear about ESP32 Hub? *</label>
            <select id="source" name="source">
                <option value="">-- Select an option --</option>
                <option value="Course announcement" <?php if (($source ?? "") === "Course announcement") echo "selected"; ?>>Course announcement</option>
                <option value="Friend" <?php if (($source ?? "") === "Friend") echo "selected"; ?>>Friend</option>
                <option value="Search engine" <?php if (($source ?? "") === "Search engine") echo "selected"; ?>>Search engine</option>
                <option value="Other" <?php if (($source ?? "") === "Other") echo "selected"; ?>>Other</option>
            </select>

            <label for="message">Feedback *</label>
            <textarea id="message" name="message"><?php echo htmlspecialchars($message ?? ""); ?></textarea>
        </fieldset>

        <button type="submit" class="btn">Submit Feedback</button>
    </form>
</div>

<script src="../script/validation.js"></script>

<?php
include '../includes/footer.php';
?>
