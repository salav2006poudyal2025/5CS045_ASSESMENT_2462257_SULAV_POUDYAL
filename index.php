<?php
// Start the session to access session variables
session_start();

// Check if the user is already logged in by looking for 'user_id' in session
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to the books page
    header("Location: public/book/books.php");
} else {
    // If not logged in, redirect to the login page
    header("Location: public/auth/login.php");
}

// Stop executing the rest of the script after redirection
exit;
