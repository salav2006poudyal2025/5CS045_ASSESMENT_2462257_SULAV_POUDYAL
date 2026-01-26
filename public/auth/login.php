<?php
// Start the session (needed for login and CSRF protection)
session_start();

require "../../database/db.php";

require "../../includes/header.php";

// Generate a CSRF token if it does not already exist
// This helps protect the login form from CSRF attacks
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Variable to store error messages
$error = "";

// Check if the form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check if CSRF token exists and matches the session token
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        // Stop execution if CSRF token is invalid
        die("Invalid CSRF token");
    }

    // Get and clean form input values
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if any field is empty
    if ($username === "" || $password === "") {
        $error = "All fields are required";
    } else {

        // Prepare SQL query to prevent SQL injection
        $stmt = $pdo->prepare(
            "SELECT id, username, password FROM users WHERE username = ?"
        );

        // Execute query with user input
        $stmt->execute([$username]);

        // Fetch user data from database
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password using password_verify (hashed password check)
        if ($user && password_verify($password, $user['password'])) {

            // Store user data in session after successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect user to books page
            header("Location: ../book/books.php");
            exit;
        }

        // Show error if login fails
        $error = "Invalid login details";
    }
}
?>

<!-- Login form container -->
<div class="auth-container">
    <h3>Login</h3>

    <!-- Display error message safely to prevent XSS -->
    <p class="error"><?= htmlspecialchars($error) ?></p>

    <!-- Login form -->
    <form method="post">

        <!-- Hidden CSRF token field -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <!-- Username input -->
        Username
        <input type="text" name="username" required><br>

        <!-- Password input -->
        Password
        <input type="password" name="password" required><br>

        <!-- Submit button -->
        <button type="submit">Login</button>
    </form>
</div>
