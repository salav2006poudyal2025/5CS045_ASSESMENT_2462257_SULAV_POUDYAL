<?php
// Check if user is logged in (redirect happens inside auth.php)
require "../../includes/auth.php";

require "../../database/db.php";
include "../../includes/header.php";

// Fetch all authors from the database
$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Link to add a new author -->
<a href="add_author.php">Add Author</a>

<!-- Authors table -->
<table border="1">
    <tr>
        <th>Name</th>
        <th>Action</th>
    </tr>

    <!-- Loop through all authors and display them -->
    <?php foreach ($authors as $a): ?>
    <tr>
        <!-- Display author name safely -->
        <td><?= htmlspecialchars($a['name']) ?></td>
        <td>
            <a href="edit_author.php?id=<?= (int)$a['id'] ?>">Edit</a> |

            <a href="delete_author.php?id=<?= (int)$a['id'] ?>&csrf=<?= $_SESSION['csrf_token'] ?>"
               onclick="return confirm('Delete this author?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include "../../includes/footer.php"; ?>
