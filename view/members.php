<?php
$editMember = null;
if (isset($_GET['edit_member'])) {
    $editMember = $member->getMemberById($_GET['edit_member']);
}
?>

<h3><?= $editMember ? 'Edit Member' : 'Add Member' ?></h3>
<form method="POST">
    <input type="hidden" name="id" value="<?= $editMember['id'] ?? '' ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?= $editMember['name'] ?? '' ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?= $editMember['email'] ?? '' ?>" required>
    <label>Phone:</label>
    <input type="text" name="phone" value="<?= $editMember['phone'] ?? '' ?>" required>
    <button type="submit" name="<?= $editMember ? 'update_member' : 'add_member' ?>">
        <?= $editMember ? 'Update' : 'Add' ?>
    </button>
</form>

<hr>

<h3>Member List</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>
    <?php foreach ($member->getAllMembers() as $m): ?>
    <tr>
        <td><?= $m['id'] ?></td>
        <td><?= htmlspecialchars($m['name']) ?></td>
        <td><?= htmlspecialchars($m['email']) ?></td>
        <td><?= htmlspecialchars($m['phone']) ?></td>
        <td>
            <a href="?page=members&edit_member=<?= $m['id'] ?>">Edit</a> |
            <a href="?page=members&delete_member=<?= $m['id'] ?>" onclick="return confirm('Delete this member?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
