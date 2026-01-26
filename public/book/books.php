<?php
// Require authentication (user must be logged in)
require "../../includes/auth.php";

require "../../database/db.php";

include "../../includes/header.php";

// Fetch all books with their author and category names
$sql = "
SELECT books.id, books.title, books.publication_year,
       authors.name AS author,
       categories.category_name AS category
FROM books
LEFT JOIN authors ON books.author_id = authors.id
LEFT JOIN categories ON books.category_id = categories.id
";

// Execute query and fetch all results as associative array
$stmt = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link to add new book -->
<a href="add_book.php">Add Book</a>

<!-- Books Table -->
<table border="1">
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Category</th>
    <th>Year</th>
    <th>Action</th>
</tr>

<?php foreach ($stmt as $row): ?>
<tr>
    <!-- Escape output to prevent XSS -->
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= htmlspecialchars($row['author']) ?></td>
    <td><?= htmlspecialchars($row['category']) ?></td>
    <td><?= htmlspecialchars($row['publication_year']) ?></td>
    <td>
        <a href="edit_book.php?id=<?= (int)$row['id'] ?>">Edit</a> |
        <a href="delete_book.php?id=<?= (int)$row['id'] ?>&csrf=<?= $_SESSION['csrf_token'] ?>"
           onclick="return confirm('Delete this book?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php include "../../includes/footer.php"; ?>
