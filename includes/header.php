<?php

// Start the session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create a CSRF token if it does not already exist
// This token is used to protect forms from CSRF attacks
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if the user is logged in
// user_id is set after successful login
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>

    <link rel="stylesheet" href="../../assets/styles.css">
</head>
<body>

<h2>Library Management System</h2>

<?php if ($isLoggedIn): ?>
    <!-- Navigation bar (shown only when user is logged in) -->
    <nav>
        <!-- Link of pages -->
        <a href="../book/books.php">Books</a> 
        <a href="../author/authors.php">Authors</a> 
        <a href="../categories/categories.php">Categories</a> 
        <a href="../search/search.php">Search</a> | |
        <a href="../auth/register.php">Register New Admin</a> 
        <a href="../auth/logout.php">Logout</a> 
    </nav>
    <hr>
<?php endif; ?>
