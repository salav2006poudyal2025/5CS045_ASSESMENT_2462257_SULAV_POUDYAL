<?php
// Include authentication check: only logged-in users can access
require "../../includes/auth.php";

require "../../database/db.php";

include "../../includes/header.php";

// Fetch all categories from the database
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<a href="add_categories.php">Add Category</a>

<!-- Start of categories table -->
<table border="1">
<tr>
    <!-- Table headers -->
    <th>Category</th>
    <th>Action</th>
</tr>

<!-- Loop through each category and display in table -->
<?php foreach ($categories as $c): ?>
<tr>
    <!-- Display category name safely -->
    <td><?= htmlspecialchars($c['category_name']) ?></td>
    <td>
        <a href="edit_categories.php?id=<?= (int)$c['id'] ?>">Edit</a> |
        <!-- Link to delete category with CSRF token and confirmation -->
        <a href="delete_categories.php?id=<?= (int)$c['id'] ?>&csrf=<?= $_SESSION['csrf_token'] ?>"
           onclick="return confirm('Delete this category?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php include "../../includes/footer.php"; ?>
