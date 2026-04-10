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
        require_once '../app/views/books/book_create.php';
    }

    // 2. Zpracování dat odeslaných z formuláře
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
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
            $isSaved = $bookModel->create(
                $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages
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
        $isDeleted = $bookModel->delete($id);

        if ($isDeleted) {
            $this->addSuccessMessage('Kniha byla trvale smazána z databáze.');
        } else {
            $this->addErrorMessage('Nastala chyba. Knihu se nepodařilo smazat.');
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    // 4. Zobrazení formuláře pro úpravu existující knihy
    public function edit($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k úpravě.');
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

        require_once '../app/views/books/book_edit.php';
    }

    // 5. Zpracování dat odeslaných z editačního formuláře
    public function update($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k aktualizaci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            // 1. Zkusíme nahrát NOVÉ obrázky (pokud uživatel nějaké vybral)
            $uploadedImages = $this->processImageUploads(); 

            // 2. Příprava modelů a databáze
            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();
            $bookModel = new Book($db);

            // ZDE JE ZMĚNA (KROK 5): Záchrana starých obrázků
            // Pokud je pole s novými obrázky prázdné, uživatel nevybral novou fotku.
            if (empty($uploadedImages)) {
                // Vytáhneme si knihu z DB, jak vypadala doteď
                $existingBook = $bookModel->getById($id);
                
                // Obrázky jsou v DB uložené jako text (JSON). Převedeme je zpět na pole.
                $oldImagesString = $existingBook['images'] ?? '[]';
                $uploadedImages = json_decode($oldImagesString, true);

                // Pojistka, kdyby to nebylo pole
                if (!is_array($uploadedImages)) {
                    $uploadedImages = [];
                }
            }

            // 3. Volání updatu nad modelem (teď už se staré obrázky nepřepíšou prázdnotou)
            $isUpdated = $bookModel->update(
                $id, $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages
            );

            if ($isUpdated) {
                $this->addSuccessMessage('Kniha byla úspěšně upravena.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nastala chyba. Změny se nepodařilo uložit.');
            }
            
        } else {
            $this->addNoticeMessage('Pro úpravu knihy je nutné odeslat formulář.');
        }
    }


    // --- Pomocné metody pro systém notifikací ---
    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message) {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }
       
    // --- Pomocná metoda pro zpracování nahrávání obrázků ---
    protected function processImageUploads() {
        $uploadedFiles = [];
        
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue; 
                    }

                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

}