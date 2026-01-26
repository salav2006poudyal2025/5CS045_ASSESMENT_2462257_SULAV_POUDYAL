<?php
// Include authentication check: only logged-in users can access
require "../../includes/auth.php";
require "../../database/db.php";

// Validate request:
// 1. Check if 'id' parameter exists and is numeric
// 2. Check if 'csrf' token exists
// 3. Verify CSRF token matches the one in the session
if (
    empty($_GET['id']) ||
    !is_numeric($_GET['id']) ||
    empty($_GET['csrf']) ||
    !hash_equals($_SESSION['csrf_token'], $_GET['csrf'])
) {
    // If any check fails, stop execution
    die("Invalid request");
}

// Cast 'id' to integer for safety
$id = (int)$_GET['id'];

// Prepare DELETE statement to remove category by ID
$stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");

// Execute the DELETE statement
$stmt->execute([$id]);

// Redirect back to the categories list page
header("Location: categories.php");
exit; // Stop further execution
