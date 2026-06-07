<?php

class Comment {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    // Načtení všech komentářů k jednomu albu (včetně jména autora)
    public function getByBookId($bookId) {
        $sql = "SELECT comments.*, users.username, users.first_name, users.last_name 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.book_id = :book_id 
                ORDER BY comments.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':book_id' => $bookId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Přidání nového komentáře
    public function create($bookId, $userId, $content) {
        $sql = "INSERT INTO comments (book_id, user_id, content) VALUES (:book_id, :user_id, :content)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':book_id' => $bookId,
            ':user_id' => $userId,
            ':content' => $content
        ]);
    }

    // Získání jednoho komentáře podle ID (pro ověření práv při mazání)
    public function getById($id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Smazání komentáře
    public function delete($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}