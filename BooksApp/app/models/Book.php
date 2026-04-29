<?php

class Book {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    public function create(
        string $title,
        string $author,
        string $category,
        string $subcategory,
        int $year,
        float $price,
        string $isbn,
        string $description,
        string $link,
        array $images,
        int $userId 
    ): bool {
        
        $sql = "INSERT INTO books (title, author, category, subcategory, year, price, isbn, description, link, images, created_by)
                VALUES (:title, :author, :category, :subcategory, :year, :price, :isbn, :description, :link, :images, :created_by)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':category' => $category,
            ':subcategory' => $subcategory ?: null,
            ':year' => $year,
            ':price' => $price,
            ':isbn' => $isbn,
            ':description' => $description,
            ':link' => $link,
            ':images' => json_encode($images),
            ':created_by' => $userId 
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // !!! ZMĚNA K ÚKOLU 22: Přidán parametr $userId s výchozí hodnotou null
    public function update(
        $id, $title, $author, $category, $subcategory, 
        $year, $price, $isbn, $description, $link, $images = [], 
        $userId = null 
    ) {
        // !!! ZMĚNA K ÚKOLU 22: Přidáno aktualizování sloupce updated_by
        $sql = "UPDATE books 
                SET title = :title, 
                    author = :author, 
                    category = :category, 
                    subcategory = :subcategory, 
                    year = :year, 
                    price = :price, 
                    isbn = :isbn, 
                    description = :description, 
                    link = :link, 
                    images = :images,
                    updated_by = :updated_by
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);

        // !!! ZMĚNA K ÚKOLU 22: Spárování hodnoty s databází
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':author' => $author,
            ':category' => $category,
            ':subcategory' => $subcategory ?: null,
            ':year' => $year,
            ':price' => $price,
            ':isbn' => $isbn,
            ':description' => $description,
            ':link' => $link,
            ':images' => json_encode($images),
            ':updated_by' => $userId 
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
}