<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/class/Book.php';
require_once __DIR__ . '/class/Member.php';
require_once __DIR__ . '/class/Loan.php';

$db = new Database();
$book = new Book($db->conn);
$member = new Member($db->conn);
$loan = new Loan($db->conn);

// ==== CRUD HANDLING ====
// Books
if (isset($_POST['add_book'])) {
    $book->addBook($_POST['title'], $_POST['author'], $_POST['isbn'], $_POST['stock']);
    header("Location: ?page=books"); exit;
}
if (isset($_POST['update_book'])) {
    $book->updateBook($_POST['id'], $_POST['title'], $_POST['author'], $_POST['isbn'], $_POST['stock']);
    header("Location: ?page=books"); exit;
}
if (isset($_GET['delete_book'])) {
    $book->deleteBook($_GET['delete_book']);
    header("Location: ?page=books"); exit;
}

// Members
if (isset($_POST['add_member'])) {
    $member->addMember($_POST['name'], $_POST['email'], $_POST['phone']);
    header("Location: ?page=members"); exit;
}
if (isset($_POST['update_member'])) {
    $member->updateMember($_POST['id'], $_POST['name'], $_POST['email'], $_POST['phone']);
    header("Location: ?page=members"); exit;
}
if (isset($_GET['delete_member'])) {
    $member->deleteMember($_GET['delete_member']);
    header("Location: ?page=members"); exit;
}

// Loans
if (isset($_POST['borrow'])) {
    $loan->borrowBook($_POST['book_id'], $_POST['member_id']);
    header("Location: ?page=loans"); exit;
}
if (isset($_GET['return'])) {
    $loan->returnBook($_GET['return']);
    header("Location: ?page=loans"); exit;
}
if (isset($_GET['delete'])) {
    $loan->deleteLoan($_GET['delete']);
    header("Location: ?page=loans"); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'view/header.php'; ?>

<hr>

<main>
<?php
$page = $_GET['page'] ?? 'books';
if ($page === 'books') {
    include 'view/books.php';
} elseif ($page === 'members') {
    include 'view/members.php';
} elseif ($page === 'loans') {
    include 'view/loans.php';
}
?>
</main>

<?php include 'view/footer.php'; ?>

<!-- Pindahkan script ke bawah agar elemen #theme-toggle sudah ada -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById('theme-toggle');
    const body = document.body;

    // Cek tema tersimpan
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark');
        if (toggleBtn) toggleBtn.textContent = '☀️';
    }

    // Tombol toggle tema
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('dark');
            const isDark = body.classList.contains('dark');
            toggleBtn.textContent = isDark ? '☀️' : '🌙';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    }
});
</script>

</body>
</html>
