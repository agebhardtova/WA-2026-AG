<?php

class Album {
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
        int $userId,
        int $rating 
    ): bool {
        
        $sql = "INSERT INTO albums (title, author, category, subcategory, year, price, isbn, description, link, images, created_by, rating)
                VALUES (:title, :author, :category, :subcategory, :year, :price, :isbn, :description, :link, :images, :created_by, :rating)";
        
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
            ':created_by' => $userId,
            ':rating' => $rating
        ]);
    }

    public function getAll() {
        $sql = "SELECT albums.*, categories.name AS category_name, subcategories.name AS subcategory_name 
                FROM albums 
                LEFT JOIN categories ON albums.category = categories.id 
                LEFT JOIN subcategories ON albums.subcategory = subcategories.id
                ORDER BY albums.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT albums.*, categories.name AS category_name, subcategories.name AS subcategory_name 
                FROM albums 
                LEFT JOIN categories ON albums.category = categories.id 
                LEFT JOIN subcategories ON albums.subcategory = subcategories.id
                WHERE albums.id = :id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(
        $id, $title, $author, $category, $subcategory, 
        $year, $price, $isbn, $description, $link, $images = [], 
        $userId = null,
        $rating = 0
    ) {
        $sql = "UPDATE albums 
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
                    updated_by = :updated_by,
                    rating = :rating
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
            ':updated_by' => $userId,
            ':rating' => $rating
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM albums WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
}