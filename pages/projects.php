<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';
include '../includes/header.php';

// Fetch projects from the database
$projects = [];
$sql = "SELECT p.project_id, p.title, p.difficulty, p.estimated_time, p.short_description,
               c.category_name
        FROM projects p
        JOIN categories c ON p.category_id = c.category_id
        ORDER BY c.category_name, p.difficulty, p.title";
if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    $result->free();
}

$grouped = [];
foreach ($projects as $p) {
    $cat = $p["category_name"];
    if (!isset($grouped[$cat])) {
        $grouped[$cat] = [];
    }
    $grouped[$cat][] = $p;
}
?>

<!-- Projects Page -->
<div class="surface" style="padding-left: 20px;">
    <h1>ESP32 Projects Table</h1>
    <p>
        This table is generated dynamically from the database and shows a small collection of ESP32 projects
        grouped by category and difficulty. You can print only the table using the dedicated print button.
    </p>

    <button id="projects-table-print-button" class="btn" onclick="window.print();">
        Print Table
    </button>

    <!-- Projects Table -->
    <div id="print-area">
        <table class="data-table">
            <caption>ESP32 Projects Overview</caption>
            <thead>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="4" class="project-details-row-title">Project Details</th>
                </tr>
                <tr>
                    <th>Title</th>
                    <th>Difficulty</th>
                    <th>Estimated Time</th>
                    <th class="action-buttons"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grouped as $category => $rows): ?>
                    <?php $rowspan = count($rows); ?>
                    <?php foreach ($rows as $index => $project): ?>
                        <tr>
                            <?php if ($index === 0): ?>
                                <td rowspan="<?php echo $rowspan; ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </td>
                            <?php endif; ?>
                            <td>
                                <?php echo htmlspecialchars($project["title"]); ?>
                            </td>
                            <td>
                                <span class="badge badge-muted">
                                    <?php echo htmlspecialchars($project["difficulty"]); ?>
                                </span>
                            </td>
                            
    <td>
        <?php echo htmlspecialchars($project["estimated_time"]); ?>
    </td>
    <!-- Delete Buttons -->
    <td class="action-buttons">
        <form method="post" action="delete_project.php" style="display:inline;">
            <input type="hidden" name="project_id" value="<?php echo $project["project_id"]; ?>">
            <button type="submit" name="delete" class="btn btn-delete">Delete</button>
        </form>
    </td>
    
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <?php if (empty($grouped)): ?>
                    <tr>
                        <td colspan="4">No projects found. Please insert sample data into the database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <div style="text-align:right; margin-top:12px;">
        <a href="add_project.php" class="btn">Add New Project</a>
    </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
