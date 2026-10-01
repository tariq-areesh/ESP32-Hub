<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';
include '../includes/header.php';

// Fetch help requests with solution counts
$sql = "SELECT hr.request_id, hr.title, hr.created_at, u.username,
               COUNT(hs.solution_id) AS solution_count
        FROM help_requests hr
        JOIN users u ON hr.user_id = u.user_id
        LEFT JOIN help_solutions hs ON hr.request_id = hs.request_id
        GROUP BY hr.request_id, hr.title, hr.created_at, u.username
        ORDER BY hr.created_at DESC";
$result = $mysqli->query($sql);
$requests = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    $result->free();
}
?>

<!-- Help Requests List Page -->
<div class="surface">
    <h1>Code Help Requests</h1>
    <p>
        Browse code help requests submitted by registered users. Click a title to view the full description,
        code file, and existing solutions, or add your own solution.
    </p>

    <?php if (isset($_SESSION["user_id"])): ?>
        <p>
            <a class="btn" href="help_upload.php">Create New Help Request</a>
        </p>
    <?php endif; ?>

    <?php if (empty($requests)): ?>
        <p>No help requests have been created yet.</p>
    <?php else: ?>
        <ul class="help-list">
            <?php foreach ($requests as $req): ?>
                <li class="help-list-item">
                    <h3>
                        <a href="help_view.php?id=<?php echo (int)$req["request_id"]; ?>">
                            <?php echo htmlspecialchars($req["title"]); ?>
                        </a>
                    </h3>
                    <p class="help-meta">
                        Created by <?php echo htmlspecialchars($req["username"]); ?>
                        on <?php echo htmlspecialchars($req["created_at"]); ?>
                        • <?php echo (int)$req["solution_count"]; ?> solution(s)
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php
include '../includes/footer.php';
?>
