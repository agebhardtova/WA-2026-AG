<?php

class CommentController {

    // Uložení nového komentáře do DB
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['messages']['error'][] = 'Pro přidání komentáře musíte být přihlášeni.';
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }

            require_once '../app/models/Database.php';
            require_once '../app/models/Comment.php';

            $db = (new Database())->getConnection();
            $commentModel = new Comment($db);

            $albumId = (int)$_POST['album_id']; // Opraveno z book_id na album_id
            $content = htmlspecialchars($_POST['content'] ?? '');
            $userId = $_SESSION['user_id'];

            if (!empty($content)) {
                $commentModel->create($albumId, $userId, $content);
                $_SESSION['messages']['success'][] = 'Komentář byl úspěšně přidán.';
            } else {
                $_SESSION['messages']['error'][] = 'Komentář nemůže být prázdný.';
            }

            // Přesměrování zpět na detail alba
            header('Location: ' . BASE_URL . '/index.php?url=album/show/' . $albumId);
            exit;
        }
    }

    // Smazání komentáře (s kontrolou práv)
    public function delete($id = null) {
        if (!$id || !isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';

        $db = (new Database())->getConnection();
        $commentModel = new Comment($db);
        
        $comment = $commentModel->getById($id);
        
        if (!$comment) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $albumId = $comment['album_id']; // Opraveno z book_id na album_id
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        // Kontrola: Je to můj komentář NEBO jsem admin?
        if ($comment['user_id'] == $_SESSION['user_id'] || $isAdmin) {
            $commentModel->delete($id);
            $_SESSION['messages']['success'][] = 'Komentář byl úspěšně smazán.';
        } else {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění smazat tento komentář.';
        }

        header('Location: ' . BASE_URL . '/index.php?url=album/show/' . $albumId);
        exit;
    }
}