<?php

class Subcategory {
    // Proměnná pro uchování připojení k databázi
    private $db;

    // Konstruktor se zavolá automaticky při vytvoření objektu a přijme připojení
    public function __construct($db) {
        $this->db = $db;
    }

    // Metoda pro získání všech podkategorií seřazených podle názvu
    public function getAllSubcategories() {
        // Příprava SQL dotazu
        $stmt = $this->db->prepare("SELECT * FROM subcategories ORDER BY name ASC");
        
        // Spuštění dotazu
        $stmt->execute();
        
        // Vrácení výsledků jako asociativního pole
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}