<?php

class Comment {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    // Načtení všech komentářů k jednomu albu
    public function getByAlbumId($albumId) {
        $sql = "SELECT comments.*, users.username, users.first_name, users.last_name 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.album_id = :album_id 
                ORDER BY comments.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':album_id' => $albumId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Přidání nového komentáře
    public function create($albumId, $userId, $content) {
        $sql = "INSERT INTO comments (album_id, user_id, content) VALUES (:album_id, :user_id, :content)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':album_id' => $albumId,
            ':user_id' => $userId,
            ':content' => $content
        ]);
    }

    // Smazání komentáře
    public function delete($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}