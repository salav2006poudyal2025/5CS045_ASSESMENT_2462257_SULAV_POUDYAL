<?php
// Include authentication: only logged-in users can access
require "../../includes/auth.php";

require "../../database/db.php";

include "../../includes/header.php";

// Initialize error message
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check CSRF token to prevent cross-site request forgery
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    // Get and trim category name from form input
    $name = trim($_POST['name']);

    // Validate input
    if ($name === "") {
        // If name is empty, show error
        $error = "Category name is required";
    } else {
        // Insert new category into database
        $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->execute([$name]);

        // Redirect to categories list after successful insertion
        header("Location: categories.php");
        exit;
    }
}
?>

<!-- --Add Category Form- -->
<form method="post">
    <!-- CSRF token for security -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <input name="name" placeholder="Category name">

    <button>Add Category</button>
</form>

<p><?= htmlspecialchars($error) ?></p>

<?php include "../../includes/footer.php"; ?>
