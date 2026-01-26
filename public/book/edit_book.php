<?php
// Include authentication: only logged-in users can access
require "../../includes/auth.php";

require "../../database/db.php";

// Include page header HTML (navigation, CSS, etc.)
include "../../includes/header.php";

// Validate book ID
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    // Stop execution if ID is missing or not a number
    die("Invalid ID");
}

// Convert book ID to integer
$id = (int)$_GET['id'];

// Initialize error message
$error = "";

// Get current book data
$bookStmt = $pdo->prepare("SELECT * FROM books WHERE id=?"); // Prepare query to get book
$bookStmt->execute([$id]); // Execute query with book ID
$book = $bookStmt->fetch(); // Fetch single book row

// If book not found, stop execution
if (!$book) {
    die("Book not found");
}

// Get all authors and categories for dropdowns
$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check CSRF token to prevent cross-site attacks
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    // Get and sanitize form inputs
    $title = trim($_POST['title']); // Book title
    $author = (int)$_POST['author']; // Author ID
    $category = (int)$_POST['category']; // Category ID
    $year = (int)$_POST['year']; // Publication year

    // Validate inputs
    if ($title === "" || $author <= 0 || $category <= 0) {
        // Check that all fields are filled
        $error = "All fields are required";
    } elseif ($year < 1000 || $year > date("Y")) {
        // Validate year
        $error = "Invalid year";
    } else {
        // Update book in database
        $stmt = $pdo->prepare(
            "UPDATE books
             SET title=?, author_id=?, category_id=?, publication_year=?
             WHERE id=?"
        );
        // Execute the update query with form data
        $stmt->execute([$title, $author, $category, $year, $id]);

        // Redirect back to books list after update
        header("Location: books.php");
        exit;
    }
}
?>

<!----Edit Book Form------>
<form method="post">
    <!-- CSRF token for security -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <!-- Book Title -->
    Title:
    <input name="title" value="<?= htmlspecialchars($book['title']) ?>"><br>

    <!-- Author Dropdown -->
    Author:
    <select name="author">
        <?php foreach ($authors as $a): ?>
            <option value="<?= $a['id'] ?>" <?= $a['id'] == $book['author_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <!-- Category Dropdown -->
    Category:
    <select name="category">
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $book['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['category_name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    Year:
    <input type="number" name="year" value="<?= htmlspecialchars($book['publication_year']) ?>"><br>

    <button>Update Book</button>
</form>

<p><?= htmlspecialchars($error) ?></p>

<?php include "../../includes/footer.php"; ?>
