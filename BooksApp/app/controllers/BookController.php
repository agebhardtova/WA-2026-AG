<?php

class BookController {

    // 0. Výchozí metoda pro zobrazení úvodní stránky
    public function index() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $books = $bookModel->getAll(); 
        
        require_once '../app/views/books/books_list.php';
    }

    // Zobrazení detailu konkrétní knihy
    public function show($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k zobrazení.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        if (!$book) {
            $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/books/book_show.php';
    }

    // 1. Zobrazení formuláře pro přidání nové knihy
    public function create() {
        // 🔒 ZMĚNA: Kontrola, zda je uživatel přihlášen
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/views/books/book_create.php';
    }

    // 2. Zpracování dat odeslaných z formuláře
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 🔒 ZMĚNA: Kontrola přihlášení a získání ID
            if (!isset($_SESSION['user_id'])) {
                $this->addErrorMessage('Pro uložení knihy musíte být přihlášeni.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }
            $userId = $_SESSION['user_id'];
            
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $uploadedImages = $this->processImageUploads(); 

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();

            $bookModel = new Book($db);
            
            // !!! ZMĚNA: Přidán parametr $userId nakonec
            $isSaved = $bookModel->create(
                $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages,
                $userId 
            );

            if ($isSaved) {
                $this->addSuccessMessage('Kniha byla úspěšně uložena do databáze.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nastala chyba. Nepodařilo se uložit knihu do databáze.');
            }
            
        } else {
            $this->addNoticeMessage('Pro přidání knihy je nutné odeslat formulář.');
        }
    }

    // 3. Smazání existující knihy
    public function delete($id = null) {
        // 🔒 ZMĚNA: Kontrola přihlášení
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání knihy se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy ke smazání.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();
        $bookModel = new Book($db);

        // 🛡️ ZMĚNA: Kontrola vlastnictví před smazáním
        $book = $bookModel->getById($id);

        if (!$book) {
            $this->addErrorMessage('Kniha nebyla nalezena, pravděpodobně již byla smazána.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($book['created_by'] !== $_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění smazat tuto knihu, protože nejste jejím autorem.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isDeleted = $bookModel->delete