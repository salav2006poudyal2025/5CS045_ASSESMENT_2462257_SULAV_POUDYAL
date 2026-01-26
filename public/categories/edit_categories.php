<?php
// Include authentication check to ensure only logged-in users can access
require "../../includes/auth.php";

require "../../database/db.php";

include "../../includes/header.php";

// Check if 'id' parameter exists and is a number
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID"); // Stop execution if invalid
}

// Cast 'id' to integer for safety
$id = (int)$_GET['id'];

// Initialize error message variable
$error = "";

// Prepare SQL statement to fetch the category by ID
$categoryStmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$categoryStmt->execute([$id]);
$category = $categoryStmt->fetch(); // Fetch the category record

// If category not found, stop execution
if (!$category) {
    die("Category not found");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check CSRF token validity
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token"); // Stop if token invalid
    }

    // Get the submitted category name and trim whitespace
    $name = trim($_POST['name']);

    // Validate input
    if ($name === "") {
        $error = "Category name is required"; // Show error if empty
    } else {
        // Prepare SQL to update the category name
        $stmt = $pdo->prepare("UPDATE categories SET category_name=? WHERE id=?");
        $stmt->execute([$name, $id]); // Execute update

        // Redirect back to categories list after successful update
        header("Location: categories.php");
        exit; // Stop further execution
    }
}
?>

<form method="post">
    <!-- CSRF token hidden field for security -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    
    <input name="name" value="<?= htmlspecialchars($category['category_name']) ?>">
    
    <button>Update Category</button>
</form>

<p><?= htmlspecialchars($error) ?></p>

<?php include "../../includes/footer.php"; ?>
