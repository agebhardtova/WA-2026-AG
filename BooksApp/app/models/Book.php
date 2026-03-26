<?php

class Book {
    private $conn;
    private $table_name = "books"; // Název tvé tabulky v databázi

    // Konstruktor - při vytvoření převezme spojení s databází
    public function __construct($db) {
        $this->conn = $db;
    }

    // Funkce pro uložení knihy (přijímá parametry z BookControlleru)
    public function create($title, $author, $category, $subcategory, $year, $price, $isbn, $description, $link, $imagePaths) {
        
        // 1. SQL příkaz pro vložení dat
        // Používáme zástupné znaky s dvojtečkou (např. :title) kvůli bezpečnosti proti hackerům
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, author, category, subcategory, year, price, isbn, description, link, images) 
                  VALUES 
                  (:title, :author, :category, :subcategory, :year, :price, :isbn, :description, :link, :images)";

        // 2. Příprava dotazu
        $stmt = $this->conn->prepare($query);

        // Databáze očekává u obrázků datový typ JSON, takže pole s obrázky převedeme na text (JSON)
        $images_json = !empty($imagePaths) ? json_encode($imagePaths) : null;

        // 3. Propojení zástupných znaků s reálnými proměnnými z formuláře (Bindování)
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':subcategory', $subcategory);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':images', $images_json);

        // 4. Spuštění dotazu a ověření, zda se to povedlo
        if ($stmt->execute()) {
            return true; // Povedlo se, kniha je v databázi!
        }

        return false; // Něco se pokazilo
    }
}