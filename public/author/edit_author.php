<?php
// Ensure the user is logged in
require "../../includes/auth.php";

require "../../database/db.php";
include "../../includes/header.php";

// Validate author ID from URL
if (empty($_GET['id']) || !is_numeric($_GET['id'])) die("Invalid ID");

// Convert ID to integer for safety
$id = (int)$_GET['id'];

// Variable to store error messages
$error = "";

// Fetch the author data from the database
$authorStmt = $pdo->prepare("SELECT * FROM authors WHERE id=?");
$authorStmt->execute([$id]);
$author = $authorStmt->fetch();

// Stop if author not found
if (!$author) die("Author not found");

// Handle form submission to update author
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF token validation for security
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) die("Invalid CSRF token");

    // Get and clean the submitted name
    $name = trim($_POST['name']);

    // Validate input
    if ($name === "") {
        $error = "Author name is required";
    } else {

        // Update author in database using prepared statement
        $stmt = $pdo->prepare("UPDATE authors SET name=? WHERE id=?");
        $stmt->execute([$name, $id]);

        // Redirect to authors list after update
        header("Location: authors.php");
        exit;
    }
}
?>

<!-- Edit Author Form -->
<form method="post">
    <!-- CSRF token -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <input name="name" value="<?= htmlspecialchars($author['name']) ?>">

    <button>Update Author</button>
</form>

<p><?= htmlspecialchars($error) ?></p>

<?php include "../../includes/footer.php"; ?>
