<?php
// Check if user is logged in
require "../../includes/auth.php";

require "../../database/db.php";

// Validate the request
// 1. Check if 'id' exists and is a number
// 2. Check if 'csrf' token exists and matches the session token
if (
    empty($_GET['id']) ||
    !is_numeric($_GET['id']) ||
    empty($_GET['csrf']) ||
    !hash_equals($_SESSION['csrf_token'], $_GET['csrf'])
) {
    die("Invalid request"); // Stop if request is invalid
}

// Get author ID safely
$id = (int)$_GET['id'];

// Delete the author from the database using a prepared statement
$stmt = $pdo->prepare("DELETE FROM authors WHERE id=?");
$stmt->execute([$id]);

// Redirect back to the authors list after deletion
header("Location: authors.php");
exit;
