<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';

$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $errors[] = "Email and password are required.";
    } else {
        $stmt = $mysqli->prepare("SELECT user_id, username, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $username, $password_hash);
            $stmt->fetch();

            if (password_verify($password, $password_hash)) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Invalid email or password.";
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<!-- Login Form -->
<div class="auth-page-wrapper">
  <div class="surface auth-card">
    <h1>Login</h1>
    <p>
        Log in to ESP32 Hub to upload code problems and help other users by posting solutions.
    </p>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $err): ?>
                <p><?php echo htmlspecialchars($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label for="login_email">Email *</label>
        <input type="email" id="login_email" name="email"
               value="<?php echo htmlspecialchars($email ?? ""); ?>">

        <label for="login_password">Password *</label>
        <input type="password" id="login_password" name="password">

        <button type="submit" class="btn auth-btn">Log in</button>
    </form>

    <p class="auth-subtext">
        Don't have an account yet?
        <a href="register.php">Sign up</a>
    </p>
  </div>
</div>

<?php
include '../includes/footer.php';
?>

