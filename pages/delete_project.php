
<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';

// Handle project deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    $project_id = (int) $_POST["project_id"];

    if ($project_id > 0) {
        $stmt = $mysqli->prepare("DELETE FROM projects WHERE project_id = ?");
        $stmt->bind_param("i", $project_id);
        if ($stmt->execute()) {
            header("Location: projects.php");
            exit();
        }
    }
}
?>
