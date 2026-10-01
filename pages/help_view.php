<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$solution_success = "";
$request_id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

// Validate request ID
if ($request_id <= 0) {
    header("Location: help_list.php");
    exit;
}

// Handle solution submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"])) {
        $errors[] = "You must be logged in to post a solution.";
    } else {
        $solution_text = trim($_POST["solution_text"] ?? "");
        if ($solution_text === "") {
            $errors[] = "Solution text cannot be empty.";
        } else {
            $user_id = $_SESSION["user_id"];
            $stmt = $mysqli->prepare("INSERT INTO help_solutions (request_id, user_id, solution_text) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $request_id, $user_id, $solution_text);
            if ($stmt->execute()) {
                $solution_success = "Your solution has been posted.";
            } else {
                $errors[] = "Failed to post solution.";
            }
            $stmt->close();
        }
    }
}

// Fetch help request details
$stmt = $mysqli->prepare("SELECT hr.title, hr.description, hr.file_path, hr.created_at, u.username
                          FROM help_requests hr
                          JOIN users u ON hr.user_id = u.user_id
                          WHERE hr.request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$stmt->bind_result($title, $description, $file_path, $created_at, $creator);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: help_list.php");
    exit;
}
$stmt->close();

// Fetch solutions for this help request
$sol_stmt = $mysqli->prepare("SELECT s.solution_text, s.created_at, u.username
                              FROM help_solutions s
                              JOIN users u ON s.user_id = u.user_id
                              WHERE s.request_id = ?
                              ORDER BY s.created_at ASC");
$sol_stmt->bind_param("i", $request_id);
$sol_stmt->execute();
$solutions_result = $sol_stmt->get_result();
$solutions = $solutions_result->fetch_all(MYSQLI_ASSOC);
$sol_stmt->close();

include '../includes/header.php';
?>

<!-- Help Request View Page -->
<div class="surface">
    <h1>Help Request: <?php echo htmlspecialchars($title); ?></h1>
    <p class="help-meta">
        Created by <?php echo htmlspecialchars($creator); ?>
        on <?php echo htmlspecialchars($created_at); ?>
    </p>

    <div class="card">
        <h2>Description</h2>
        <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
    </div>

    <?php if ($file_path): ?>
        <div class="card">
            <h2>Code File</h2>
            <p>
                <a href="../uploads/<?php echo htmlspecialchars($file_path); ?>" class="btn" download>
                    Download Code File
                </a>
            </p>

            <?php
            $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
            if (in_array($ext, ["ino", "c", "cpp", "txt", "py"])) {
                $full_path = realpath(__DIR__ . "/../uploads/" . $file_path);
                if ($full_path && strpos($full_path, realpath(__DIR__ . "/../uploads")) === 0) {
                    $code_content = @file_get_contents($full_path);
                    if ($code_content !== false): ?>
                        <pre class="code-block"><code><?php echo htmlspecialchars($code_content); ?></code></pre>
                    <?php endif;
                }
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Solutions Section -->
    <div class="card">
        <h2>Solutions</h2>
            <!-- Display Success or Error Messages -->
        <?php if (!empty($solution_success)): ?>
            <div class="success">
                <?php echo htmlspecialchars($solution_success); ?>
            </div>
        <?php endif; ?>
<!-- Display Success or Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
<!-- Solutions List -->
        <?php if (empty($solutions)): ?>
            <p>No solutions have been posted yet.</p>
        <?php else: ?>
            <ul class="help-list">
                <?php foreach ($solutions as $sol): ?>
                    <li class="help-list-item">
                        <p><?php echo nl2br(htmlspecialchars($sol["solution_text"])); ?></p>
                        <p class="help-meta">
                            Posted by <?php echo htmlspecialchars($sol["username"]); ?>
                            on <?php echo htmlspecialchars($sol["created_at"]); ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <!-- Solution Submission Form -->
        <?php if (isset($_SESSION["user_id"])): ?>
            <form class="solution-form" method="post" action="help_view.php?id=<?php echo (int)$request_id; ?>">
                <fieldset>
                    <legend>Post a Solution</legend>

                    <label for="solution_text">Your Solution *</label>
                    <textarea id="solution_text" name="solution_text"></textarea>
                </fieldset>

                <button type="submit" class="btn">Submit Solution</button>
            </form>
        <?php else: ?>
            <p>You must be logged in to post a solution.</p>
        <?php endif; ?>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
