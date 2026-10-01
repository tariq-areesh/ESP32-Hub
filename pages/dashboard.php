<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/auth_check.php';
require '../includes/db_connect.php';
include '../includes/header.php';

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"] ?? "User";

// Fetch user's help requests
$stmt = $mysqli->prepare("SELECT request_id, title, created_at FROM help_requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$my_requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!-- Dashboard Page -->
<div class="surface">
    <h1>Dashboard</h1>
    <p>Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>

    <div class="card">
        <h2>My Help Requests</h2>
        <p class="text-subtle">
            These are code help requests you have created. Other users can view them and post solutions.
        </p>

        <?php if (empty($my_requests)): ?>
            <p>You have not created any help requests yet.</p>
        <?php else: ?>
            <ul class="help-list">
                <?php foreach ($my_requests as $req): ?>
                    <li class="help-list-item">
                        <h3>
                            <a href="help_view.php?id=<?php echo (int)$req["request_id"]; ?>">
                                <?php echo htmlspecialchars($req["title"]); ?>
                            </a>
                        </h3>
                        <p class="help-meta">
                            Created on <?php echo htmlspecialchars($req["created_at"]); ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p>
            <a class="btn" href="help_upload.php">Create New Help Request</a>
        </p>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
