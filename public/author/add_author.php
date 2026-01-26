<?php
// Check if the user is logged in
// If not logged in, redirect to login page
require "../../includes/auth.php";

require "../../database/db.php";
include "../../includes/header.php";

// Variable to store error messages
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF token validation for security
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    // Get and clean author name input
    $name = trim($_POST['name']);

    // Validate author name
    if ($name === "") {
        $error = "Author name is required";
    } else {

        // Insert new author into the database
        $stmt = $pdo->prepare("INSERT INTO authors (name) VALUES (?)");
        $stmt->execute([$name]);

        // Redirect back to authors list after successful insert
        header("Location: authors.php");
        exit;
    }
}
?>

<!-- Add Author Form -->
<form method="post">

    <!-- CSRF token for security -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <input name="name" placeholder="Author name">

    <button>Add Author</button>
</form>

<p><?= htmlspecialchars($error) ?></p>

<?php include "../../includes/footer.php"; ?>
