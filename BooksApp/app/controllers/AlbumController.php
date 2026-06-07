<?php

class AlbumController {

    public function index() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Album.php';

        $database = new Database();
        $db = $database->getConnection();

        $albumModel = new Album($db);
        $albums = $albumModel->getAll(); 
        
        require_once '../app/views/albums/album_list.php';
    }

    public function show($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID alba k zobrazení.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Album.php';

        $database = new Database();
        $db = $database->getConnection();

        $albumModel = new Album($db);
        $album = $albumModel->getById($id);

        if (!$album) {
            $this->addErrorMessage('Požadované album nebylo v databázi nalezeno.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Comment.php';
        $commentModel = new Comment($db);
        $comments = $commentModel->getByAlbumId($id);

        require_once '../app/views/albums/album_show.php';
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

        require_once '../app/views/albums/album_create.php';
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
            $rating = (int)($_POST['rating'] ?? 0);

            $uploadedImages = $this->processImageUploads(); 

            require_once '../app/models/Database.php';
            require_once '../app/models/Album.php';

            $database = new Database();
            $db = $database->getConnection();
            $albumModel = new Album($db);
            
            $isSaved = $albumModel->create(
                $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages,
                $userId, $rating
            );

            if ($isSaved) {
                $this->addSuccessMessage('Album bylo úspěšně uloženo do databáze.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nastala chyba. Nepodařilo se uložit album do databáze.');
            }
        }
    }

    public function delete($id = null) {
        if (!isset($_SESSION['user_id']) || !$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Album.php';

        $database = new Database();
        $db = $database->getConnection();
        $albumModel = new Album($db);
        $album = $albumModel->getById($id);

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if ($album && ($album['created_by'] == $_SESSION['user_id'] || $isAdmin)) {
            $albumModel->delete($id);
            $this->addSuccessMessage('Album bylo smazáno.');
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function edit($id = null) {
        if (!isset($_SESSION['user_id']) || !$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Album.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/Subcategory.php';

        $database = new Database();
        $db = $database->getConnection();
        $albumModel = new Album($db);
        $album = $albumModel->getById($id);

        $categoryModel = new Category($db);
        $categories = $categoryModel->getAllCategories();
        $subcategoryModel = new Subcategory($db);
        $subcategories = $subcategoryModel->getAllSubcategories();

        require_once '../app/views/albums/album_edit.php';
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            require_once '../app/models/Database.php';
            require_once '../app/models/Album.php';

            $database = new Database();
            $db = $database->getConnection();
            $albumModel = new Album($db);
            
            $uploadedImages = $this->processImageUploads();
            if (empty($uploadedImages)) {
                $existing = $albumModel->getById($id);
                $uploadedImages = json_decode($existing['images'] ?? '[]', true);
            }

            $albumModel->update(
                $id, $_POST['title'], $_POST['author'], (int)$_POST['category'], (int)$_POST['subcategory'], 
                (int)$_POST['year'], (float)$_POST['price'], htmlspecialchars($_POST['isbn']), 
                htmlspecialchars($_POST['description']), htmlspecialchars($_POST['link']), 
                $uploadedImages, $_SESSION['user_id'], (int)$_POST['rating']
            );

            $this->addSuccessMessage('Album bylo upraveno.');
        }
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    protected function addSuccessMessage($m) { $_SESSION['messages']['success'][] = $m; }
    protected function addErrorMessage($m) { $_SESSION['messages']['error'][] = $m; }
    
    protected function processImageUploads() {
        // ... (ponech svůj původní kód pro upload obrázků)
        return []; 
    }
}