<?php

class BookController {

    public function index() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $books = $bookModel->getAll(); 
        
        require_once '../app/views/books/books_list.php';
    }

    public function show($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID alba k zobrazení.');
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
            $this->addErrorMessage('Požadované album nebylo v databázi nalezeno.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // --- PŘIDANÝ KÓD PRO KOMENTÁŘE ---
        require_once '../app/models/Comment.php';
        $commentModel = new Comment($db);
        $comments = $commentModel->getByBookId($id);
        // ---------------------------------

        require_once '../app/views/books/book_show.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro přidání alba se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/Subcategory.php';

        $database = new Database();
        $db = $database->getConnection();

        $categoryModel = new Category($db);
        $categories = $categoryModel->getAllCategories();

        $subcategoryModel = new Subcategory($db);
        $subcategories = $subcategoryModel->getAllSubcategories();

        require_once '../app/views/books/book_create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!isset($_SESSION['user_id'])) {
                $this->addErrorMessage('Pro uložení alba musíte být přihlášeni.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }
            
            $userId = $_SESSION['user_id'];
            
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            
            $category = (int)($_POST['category'] ?? 0);
            $subcategory = (int)($_POST['subcategory'] ?? 0);
            
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $rating = (int)($_POST['rating'] ?? 0); //Přidaný rating

            $uploadedImages = $this->processImageUploads(); 

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();

            $bookModel = new Book($db);
            
            // Poznámka: Ujisti se, že tvůj model Book má v metodě create 
            // přidaný parametr pro $subcategory
            $isSaved = $bookModel->create(
                $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages,
                $userId, $rating //přidání rating
            );

            if ($isSaved) {
                $this->addSuccessMessage('Album bylo úspěšně uloženo do databáze.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nastala chyba. Nepodařilo se uložit album do databáze.');
            }
            
        } else {
            $this->addNoticeMessage('Pro přidání alba je nutné odeslat formulář.');
        }
    }

    public function delete($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro smazání alba se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID alba ke smazání.');
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
            $this->addErrorMessage('Album nebylo nalezeno, pravděpodobně již bylo smazáno.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if ($book['created_by'] !== $_SESSION['user_id'] && !$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění smazat toto album.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isDeleted = $bookModel->delete($id);

        if ($isDeleted) {
            $this->addSuccessMessage('Album bylo trvale smazáno z databáze.');
        } else {
            $this->addErrorMessage('Nastala chyba. Album se nepodařilo smazat.');
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function edit($id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro úpravu alba se musíte nejprve přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID alba k úpravě.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/Subcategory.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $book = $bookModel->getById($id); 

        if (!$book) {
            $this->addErrorMessage('Požadované album nebylo nalezeno.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if ($book['created_by'] !== $_SESSION['user_id'] && !$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění upravovat toto album.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $categoryModel = new Category($db);
        $categories = $categoryModel->getAllCategories();

        $subcategoryModel = new Subcategory($db);
        $subcategories = $subcategoryModel->getAllSubcategories();

        require_once '../app/views/books/book_edit.php';
    }

    public function update($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID alba k aktualizaci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!isset($_SESSION['user_id'])) {
                $this->addErrorMessage('Pro uložení změn se musíte nejprve přihlásit.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }

            $userId = $_SESSION['user_id'];

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();
            $bookModel = new Book($db);

            $book = $bookModel->getById($id);

            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

            if (!$book || ($book['created_by'] !== $_SESSION['user_id'] && !$isAdmin)) {
                $this->addErrorMessage('Nemáte oprávnění ukládat změny u tohoto alba.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            
            $category = (int)($_POST['category'] ?? 0);
            $subcategory = (int)($_POST['subcategory'] ?? 0);
            
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $rating = (int)($_POST['rating'] ?? 0);

            $uploadedImages = $this->processImageUploads(); 

            if (empty($uploadedImages)) {
                $existingBook = $bookModel->getById($id);
                $oldImagesString = $existingBook['images'] ?? '[]';
                $uploadedImages = json_decode($oldImagesString, true);
                if (!is_array($uploadedImages)) $uploadedImages = [];
            }

            $isUpdated = $bookModel->update(
                $id, $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages, $userId, $rating //Přidán rating
            );

            if ($isUpdated) {
                $this->addSuccessMessage('Album bylo úspěšně upraveno.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nastala chyba. Změny se nepodařilo uložit.');
            }
            
        } else {
            $this->addNoticeMessage('Pro úpravu alba je nutné odeslat formulář.');
        }
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addNoticeMessage($message) { $_SESSION['messages']['notice'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
       
    protected function processImageUploads() {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) continue; 

                    $newName = 'album_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }
}