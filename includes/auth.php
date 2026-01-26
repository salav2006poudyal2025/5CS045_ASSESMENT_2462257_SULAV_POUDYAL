<?php

// Check if a session is not already started
if (session_status() === PHP_SESSION_NONE) {

    // Start the session
    session_start();
}

// Check if the user is NOT logged in
// (user_id is set only after successful login)
if (!isset($_SESSION['user_id'])) {

    // Redirect the user to the login page
    header("Location: ../auth/login.php");

    // Stop further execution of the page
    exit;
}
