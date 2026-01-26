<?php
// Include authentication check to ensure user is logged in
require "../../includes/auth.php";

require "../../database/db.php";

// Start session if not already started (needed for CSRF and session management)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------- AJAX SEARCH HANDLER ----------
// Check if the request is a GET request and has 'ajax' parameter
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    
    // Clear any previous output to ensure valid JSON response
    if (ob_get_level()) ob_clean();

    // Base SQL query to fetch books with category names
    $sql = "
        SELECT books.title,
               categories.category_name AS category,
               books.publication_year AS year
        FROM books
        JOIN categories ON books.category_id = categories.id
        WHERE 1=1
    ";

    // Initialize array to store query parameters
    $params = [];

    // Filter by search term if provided
    if (!empty($_GET['search'])) {
        $sql .= " AND books.title LIKE ?";
        $params[] = "%" . $_GET['search'] . "%";
    }

    // Filter by category if provided and valid
    if (!empty($_GET['category']) && is_numeric($_GET['category']) && $_GET['category'] > 0) {
        $sql .= " AND categories.id = ?";
        $params[] = (int)$_GET['category'];
    }

    // Filter by publication year if provided and valid
    if (!empty($_GET['year']) && is_numeric($_GET['year']) && $_GET['year'] > 0) {
        $sql .= " AND books.publication_year = ?";
        $params[] = (int)$_GET['year'];
    }

    try {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Set response type to JSON
        header("Content-Type: application/json");

        // Output the results as JSON
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        // Return an empty array on error to prevent breaking frontend JS
        echo json_encode([]);
    }

    exit; // Stop further execution for AJAX requests
}

// Include header template
include "../../includes/header.php";

// Fetch all categories for the dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Page HTML -->
<h3>Search Books</h3>

<!-- Category filter dropdown -->
<select id="category">
    <option value="">All Categories</option>
    <?php foreach ($categories as $c): ?>
        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
    <?php endforeach; ?>
</select>

<input type="number" id="year" placeholder="Year">

<input type="text" id="search" placeholder="Search books">

<!-- Container for AJAX results -->
<div id="results"></div>

<script src="../../assets/script.js"></script>

<?php include "../../includes/footer.php"; ?>
