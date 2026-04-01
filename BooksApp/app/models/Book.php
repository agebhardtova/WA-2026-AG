<?php

class Book {
    // Definice, že proměnná $db musí být vždy instancí třídy PDO
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
        array $images
    ): bool {
        $sql = "INSERT INTO books (title, author, category, subcategory, year, price, isbn, description, link, images)
                VALUES (:title, :author, :category, :subcategory, :year, :price, :isbn, :description, :link, :images)";
        // stmt = statement
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
            ':images' => json_encode($images)
        ]);
    }

    // Získání všech knih z databáze
    public function getAll() {
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        // Vrací pole asociativních polí (každý řádek z DB je jedno pole)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}