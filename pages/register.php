<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    // Validate inputs
    if ($username === "") $errors[] = "Username is required.";
    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($password === "" || $confirm_password === "") {
        $errors[] = "Password and confirmation are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    // Check for existing user
    if (empty($errors)) {
        $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "A user with this email or username already exists.";
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $mysqli->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hash);

            if ($insert->execute()) {
                $success = "Account created successfully. You can now log in.";
                $username = $email = "";
            } else {
                $errors[] = "Failed to create account. Please try again.";
            }

            $insert->close();
        }
    }
}

include '../includes/header.php';
?>
<!-- Register Form -->
<div class="auth-page-wrapper">
  <div class="surface auth-card">
    <h1>Create an Account</h1>
    <p>
        Register for an ESP32 Hub account so you can upload code problems and post solutions for other users.
    </p>
<!-- Display success or error messages -->
    <?php if (!empty($success)): ?>
        <div class="success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
<!-- Display errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $err): ?>
                <p><?php echo htmlspecialchars($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="register.php">
        <label for="username">Username *</label>
        <input type="text" id="username" name="username"
               value="<?php echo htmlspecialchars($username ?? ""); ?>">

        <label for="reg_email">Email *</label>
        <input type="email" id="reg_email" name="email"
               value="<?php echo htmlspecialchars($email ?? ""); ?>">

        <label for="password">Password *</label>
        <input type="password" id="password" name="password">

        <label for="confirm_password">Confirm Password *</label>
        <input type="password" id="confirm_password" name="confirm_password">

        <button type="submit" class="btn auth-btn">Sign up</button>
    </form>

    <p class="auth-subtext">
        Already have an account?
        <a href="login.php">Log in</a>
    </p>
  </div>
</div>

<?php
include '../includes/footer.php';
?>

