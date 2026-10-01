<?php
// Tariq Mohammed Areesh: 2237498 | Majd Ahmed Al-farasani: 2237426
require '../includes/db_connect.php';

$contact_success = "";
$contact_errors = [];
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");
    // Validate inputs
    if ($name === "") $contact_errors[] = "Name is required.";
    if ($email === "") {
        $contact_errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contact_errors[] = "Invalid email format.";
    }
    if ($subject === "") $contact_errors[] = "Subject is required.";
    if ($message === "") $contact_errors[] = "Message cannot be empty.";

    if (empty($contact_errors)) {
        $stmt = $mysqli->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $contact_success = "Thank you for contacting us. We will get back to you soon.";
            $name = $email = $subject = $message = "";
        } else {
            $contact_errors[] = "Failed to send your message. Please try again later.";
        }

        $stmt->close();
    }
}

include '../includes/header.php';
?>

<!-- Contact Form -->
<div class="surface">
    <h1>Contact Us</h1>
    <p>
        Use the form below to contact the ESP32 Hub maintainer with questions about the site, or about hosting and
        database issues.
    </p>

    <?php if (!empty($contact_success)): ?>
        <div class="success">
            <?php echo htmlspecialchars($contact_success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($contact_errors)): ?>
        <div class="error">
            <?php foreach ($contact_errors as $err): ?>
                <p><?php echo htmlspecialchars($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="contact.php">
        <fieldset>
            <legend>Contact Details</legend>

            <label for="contact_name">Name *</label>
            <input type="text" id="contact_name" name="name" value="<?php echo htmlspecialchars($name ?? ""); ?>">

            <label for="contact_email">Email *</label>
            <input type="email" id="contact_email" name="email" value="<?php echo htmlspecialchars($email ?? ""); ?>">

            <label for="subject">Subject *</label>
            <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($subject ?? ""); ?>">
        </fieldset>

        <fieldset>
            <legend>Message</legend>

            <label for="contact_message">Message *</label>
            <textarea id="contact_message" name="message"><?php echo htmlspecialchars($message ?? ""); ?></textarea>
        </fieldset>

        <button type="submit" class="btn">Send Message</button>
    </form>
</div>

<?php
include '../includes/footer.php';
?>
