<?php

class Book {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    public function create(
        string $title,
        string $author,
        int $category, 
        int $subcategory, 
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

    // Získání všech knih z databáze (Včetně názvů kategorií a podkategorií)
    public function getAll() {
        // PŘIDÁNO: Načtení subcategory_name a druhý LEFT JOIN
        $sql = "SELECT books.*, categories.name AS category_name, subcategories.name AS subcategory_name 
                FROM books 
                LEFT JOIN categories ON books.category = categories.id 
                LEFT JOIN subcategories ON books.subcategory = subcategories.id
                ORDER BY books.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získání jedné konkrétní knihy podle jejího ID
    public function getById($id) {
        // PŘIDÁNO: Načtení subcategory_name a oba JOINy (aby detail knihy uměl přeložit ID na text)
        $sql = "SELECT books.*, categories.name AS category_name, subcategories.name AS subcategory_name 
                FROM books 
                LEFT JOIN categories ON books.category = categories.id 
                LEFT JOIN subcategories ON books.subcategory = subcategories.id
                WHERE books.id = :id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(
        $id, $title, $author, $category, $subcategory, 
        $year, $price, $isbn, $description, $link, $images = [], 
        $userId = null 
    ) {
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