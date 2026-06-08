<?php

class Album {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    public function create(
        string $title,
        string $interpret,
        int $category, 
        int $subcategory, 
        int $year,
        float $price,
        string $catalog_number,
        string $description,
        string $link,
        array $images,
        int $userId,
        int $rating 
    ): bool {
        
        $sql = "INSERT INTO albums (title, interpret, category, subcategory, year, price, catalog_number, description, link, images, created_by, rating)
                VALUES (:title, :interpret, :category, :subcategory, :year, :price, :catalog_number, :description, :link, :images, :created_by, :rating)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title' => $title,
            ':interpret' => $interpret,
            ':category' => $category,
            ':subcategory' => $subcategory ?: null,
            ':year' => $year,
            ':price' => $price,
            ':catalog_number' => $catalog_number,
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

    // NOVÁ FUNKCE: Načtení alb pouze pro konkrétního uživatele
    public function getAllByUserId($userId) {
        $sql = "SELECT albums.*, categories.name AS category_name, subcategories.name AS subcategory_name 
                FROM albums 
                LEFT JOIN categories ON albums.category = categories.id 
                LEFT JOIN subcategories ON albums.subcategory = subcategories.id
                WHERE albums.created_by = :user_id
                ORDER BY albums.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
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
        $id, $title, $interpret, $category, $subcategory, 
        $year, $price, $catalog_number, $description, $link, $images = [], 
        $userId = null,
        $rating = 0
    ) {
        $sql = "UPDATE albums 
                SET title = :title, 
                    interpret = :interpret, 
                    category = :category, 
                    subcategory = :subcategory, 
                    year = :year, 
                    price = :price, 
                    catalog_number = :catalog_number, 
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
            ':interpret' => $interpret,
            ':category' => $category,
            ':subcategory' => $subcategory ?: null,
            ':year' => $year,
            ':price' => $price,
            ':catalog_number' => $catalog_number,
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