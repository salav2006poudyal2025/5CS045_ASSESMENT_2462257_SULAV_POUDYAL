<?php
// Start the session so it can be destroyed
session_start();

// Destroy all session data (logs the user out)
session_destroy();

// Redirect the user back to the login page
header("Location: login.php");

// Stop script execution after redirect
exit;
