<?php
// Require authentication: only logged-in users can delete
require "../../includes/auth.php";
require "../../database/db.php";

// Validate input
// Check if 'id' is present and numeric
// Check if CSRF token is present and valid
if (
    empty($_GET['id']) ||
    !is_numeric($_GET['id']) ||
    empty($_GET['csrf']) ||
    !hash_equals($_SESSION['csrf_token'], $_GET['csrf'])
) {
    die("Invalid request"); // Stop execution if validation fails
}

// Convert ID to integer
$id = (int)$_GET['id'];

// Delete the book
$stmt = $pdo->prepare("DELETE FROM books WHERE id=?");
$stmt->execute([$id]);

// Redirect back to books list after deletion
header("Location: books.php");
exit;
