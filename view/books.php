<?php
$editBook = null;
if (isset($_GET['edit_book'])) {
    $editBook = $book->getBookById($_GET['edit_book']);
}
?>

<h3><?= $editBook ? 'Edit Book' : 'Add Book' ?></h3>
<form method="POST">
    <input type="hidden" name="id" value="<?= $editBook['id'] ?? '' ?>">
    <label>Title:</label>
    <input type="text" name="title" value="<?= $editBook['title'] ?? '' ?>" required>
    <label>Author:</label>
    <input type="text" name="author" value="<?= $editBook['author'] ?? '' ?>" required>
    <label>ISBN:</label>
    <input type="text" name="isbn" value="<?= $editBook['isbn'] ?? '' ?>" required>
    <label>Stock:</label>
    <input type="number" name="stock" value="<?= $editBook['stock'] ?? '' ?>" required>
    <button type="submit" name="<?= $editBook ? 'update_book' : 'add_book' ?>">
        <?= $editBook ? 'Update' : 'Add' ?>
    </button>
</form>

<hr>

<h3>Book List</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>ISBN</th>
        <th>Stock</th>
        <th>Action</th>
    </tr>
    <?php foreach ($book->getAllBooks() as $b): ?>
    <tr>
        <td><?= $b['id'] ?></td>
        <td><?= htmlspecialchars($b['title']) ?></td>
        <td><?= htmlspecialchars($b['author']) ?></td>
        <td><?= htmlspecialchars($b['isbn']) ?></td>
        <td><?= $b['stock'] ?></td>
        <td>
            <a href="?page=books&edit_book=<?= $b['id'] ?>">Edit</a> |
            <a href="?page=books&delete_book=<?= $b['id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
