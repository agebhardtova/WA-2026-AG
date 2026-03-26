<?php

class BookController {

    // 0. Výchozí metoda pro zobrazení úvodní stránky
    public function index() {
        // V dalších krocích se zde přidá komunikace s Modelem pro získání
        // (např. načtení všech uložených knih)

        // Nyní se pouze načte (vloží) připravený soubor s HTML strukturou
        require_once '../app/views/books/books_list.php';
    }

}