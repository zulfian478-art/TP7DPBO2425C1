<?php
class Loan {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // Ambil semua loans dengan alias kolom yang konsisten
    public function getAllLoans() {
        $sql = "
            SELECT loans.*,
                   books.title AS book_title,
                   members.name AS member_name
            FROM loans
            JOIN books   ON loans.book_id = books.id
            JOIN members ON loans.member_id = members.id
            ORDER BY loans.id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function borrowBook($book_id, $member_id) {
        // ambil stok aman
        $stmt = $this->db->prepare("SELECT stock FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book && (int)$book['stock'] > 0) {
            // mulai transaction agar atomic (opsional tapi direkomendasikan)
            try {
                $this->db->beginTransaction();

                // update stock
                $stmtUp = $this->db->prepare("UPDATE books SET stock = stock - 1 WHERE id = ?");
                $stmtUp->execute([$book_id]);

                // insert loan
                $stmtLoan = $this->db->prepare("INSERT INTO loans (book_id, member_id, loan_date) VALUES (?, ?, CURDATE())");
                $stmtLoan->execute([$book_id, $member_id]);

                $this->db->commit();
                return true;
            } catch (Exception $e) {
                if ($this->db->inTransaction()) $this->db->rollBack();
                return false;
            }
        }
        return false;
    }

    public function returnBook($loan_id) {
        // ambil loan
        $stmtLoan = $this->db->prepare("SELECT book_id, return_date FROM loans WHERE id = ?");
        $stmtLoan->execute([$loan_id]);
        $loan = $stmtLoan->fetch(PDO::FETCH_ASSOC);

        if (!$loan) return false;
        if ($loan['return_date'] !== null) return false; // sudah dikembalikan

        try {
            $this->db->beginTransaction();

            // update return_date
            $stmtUpdateLoan = $this->db->prepare("UPDATE loans SET return_date = CURDATE() WHERE id = ?");
            $stmtUpdateLoan->execute([$loan_id]);

            // tambah stock buku
            $stmtUp = $this->db->prepare("UPDATE books SET stock = stock + 1 WHERE id = ?");
            $stmtUp->execute([$loan['book_id']]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }

    // optional delete
    public function deleteLoan($id) {
        $stmt = $this->db->prepare("DELETE FROM loans WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
