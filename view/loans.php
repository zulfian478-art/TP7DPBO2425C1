<?php
// Tangani aksi form di index.php (sebaiknya operasi POST/GET sudah ditangani di index.php)
// Di sini kita hanya menampilkan data.

// Ambil data 1x saja
$loans = $loan->getAllLoans();
$books = $book->getAllBooks();
$members = $member->getAllMembers();
?>

<div class="section">
    <h3>Loan List</h3>
    <table>
        <thead>
            <tr>
                <th style="width:50px">ID</th>
                <th>Book</th>
                <th>Member</th>
                <th style="width:120px">Loan Date</th>
                <th style="width:120px">Return Date</th>
                <th style="width:120px">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($loans)): ?>
                <tr><td colspan="6">No loans found.</td></tr>
            <?php else: ?>
                <?php foreach ($loans as $l): ?>
                    <tr>
                        <td><?= htmlspecialchars($l['id']) ?></td>
                        <td><?= htmlspecialchars($l['book_title'] ?? $l['title'] ?? '') ?></td>
                        <td><?= htmlspecialchars($l['member_name'] ?? $l['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($l['loan_date']) ?></td>
                        <td><?= $l['return_date'] ? htmlspecialchars($l['return_date']) : 'Not Returned' ?></td>
                        <td>
                            <?php if (!$l['return_date']): ?>
                                <a class="action" href="?page=loans&return=<?= $l['id'] ?>">Return</a> |
                            <?php endif; ?>
                            <a class="action" href="?page=loans&delete=<?= $l['id'] ?>" onclick="return confirm('Delete this loan?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="section">
    <h3>Borrow a Book</h3>
    <form method="POST">
        <label>Book:</label>
        <select name="book_id" required>
            <option value="">-- Select Book --</option>
            <?php foreach ($books as $b): ?>
                <?php if ((int)$b['stock'] > 0): // only show available books ?>
                    <option value="<?= htmlspecialchars($b['id']) ?>"><?= htmlspecialchars($b['title']) ?> (Stock: <?= $b['stock'] ?>)</option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <label>Member:</label>
        <select name="member_id" required>
            <option value="">-- Select Member --</option>
            <?php foreach ($members as $m): ?>
                <option value="<?= htmlspecialchars($m['id']) ?>"><?= htmlspecialchars($m['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="borrow">Borrow</button>
    </form>
</div>
