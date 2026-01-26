<?php
// Start the session
session_start();

// Only logged-in admins are allowed to register new admins
// If not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require "../../database/db.php";
include "../../includes/header.php";

// Generate CSRF token if it does not exist
// This helps protect against CSRF attacks
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Variable to store error messages
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF token validation
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("Invalid CSRF token");
    }

    // Get and clean user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input fields
    if ($username === "" || $password === "") {
        $error = "All fields are required";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {

        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            // Username already taken
            $error = "Username already exists";
        } else {

            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin user into database
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, password) VALUES (?, ?)"
            );
            $stmt->execute([$username, $hashedPassword]);

            // Log out current admin after creating a new admin
            // Forces the new admin to log in manually
            header("Location: logout.php");
            exit;
        }
    }
}
?>

<div class="auth-container">
    <h3>Register New Admin</h3>

    <p class="error"><?= htmlspecialchars($error) ?></p>

    <!-- Registration form -->
    <form method="post">
        <!-- CSRF token for security -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        Username <input type="text" name="username" required><br>
        Password <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</div>
