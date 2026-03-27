<?php
// Načtení databáze a modelu
require_once '../app/models/Database.php';
require_once '../app/models/Book.php';

class BookController {
    private $db;
    private $bookModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->bookModel = new Book($this->db);
    }

    // 0. Výchozí metoda pro zobrazení úvodní stránky
    public function index() {
        require_once '../app/views/books/books_list.php';
    }

    // 1. Zobrazení formuláře pro přidání knihy
    public function create() {
        require_once '../app/views/books/book_create.php';
    }

    // 2. Samotné uložení knihy do databáze (volá se po odeslání formuláře)
    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = htmlspecialchars($_POST['title']);
            $author = htmlspecialchars($_POST['author']);
            $category = htmlspecialchars($_POST['category']);
            $subcategory = !empty($_POST['subcategory']) ? htmlspecialchars($_POST['subcategory']) : null;
            $year = intval($_POST['year']);
            $price = floatval($_POST['price']);
            $isbn = htmlspecialchars($_POST['isbn']);
            $description = htmlspecialchars($_POST['description']);
            $link = htmlspecialchars($_POST['link']);

            $imagePaths = []; // Zatím necháme prázdné

            // Zavoláme Model, ať knihu uloží
            if ($this->bookModel->create($title, $author, $category, $subcategory, $year, $price, $isbn, $description, $link, $imagePaths)) {
                // Když se to povede, přesměrujeme zpět na úvod
                header("Location: /WA-2026-AG/BooksApp/public/index.php");
                exit();
            } else {
                echo "Chyba při ukládání knihy do databáze.";
            }
        }
    }
}