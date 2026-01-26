<?php
// Ensure the user is logged in
require "../../includes/auth.php";

require "../../database/db.php";

include "../../includes/header.php";

// Variable to store error messages
$error = "";

// Fetch all authors and categories for the dropdowns
$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CSRF token validation
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    // Get and sanitize input values
    $title = trim($_POST['title']);
    $author = (int)$_POST['author'];
    $category = (int)$_POST['category'];
    $year = (int)$_POST['year'];

    // Validate inputs
    if ($title === "" || $author <= 0 || $category <= 0) {
        $error = "All fields are required";
    } elseif ($year < 1000 || $year > date("Y")) {
        $error = "Invalid year";
    } else {
        // Insert new book into database using prepared statement
        $stmt = $pdo->prepare(
            "INSERT INTO books (title, author_id, category_id, publication_year)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$title, $author, $category, $year]);

        // Redirect to the books list page
        header("Location: books.php");
        exit;
    }
}
?>

<!-- Add Book Form -->
<form method="post">
    <!-- CSRF token -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <!-- Book Title -->
    Title:
    <input name="title"><br>

    <!-- Author Dropdown -->
    Author:
    <select name="author">
        <option value="">Select</option>
        <?php foreach ($authors as $a): ?>
            <option value="<?= $a['id'] ?>">
                <?= htmlspecialchars($a['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <!-- Category Dropdown -->
    Category:
    <select name="category">
        <option value="">Select</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>">
                <?= htmlspecialchars($c['category_name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    Year:
    <input type="number" name="year"><br>

    <button>Add Book</button>
</form>

<p><?= htmlspecialchars($error) ?></p>

<?php include "../../includes/footer.php"; ?>
