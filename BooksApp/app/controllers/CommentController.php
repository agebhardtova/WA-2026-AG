<?php

class CommentController {

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

            $albumId = (int)$_POST['album_id'];
            $content = htmlspecialchars($_POST['content'] ?? '');
            $userId = $_SESSION['user_id'];

            if (!empty($content)) {
                $commentModel->create($albumId, $userId, $content);
                $_SESSION['messages']['success'][] = 'Komentář byl úspěšně přidán.';
            } else {
                $_SESSION['messages']['error'][] = 'Komentář nemůže být prázdný.';
            }

            header('Location: ' . BASE_URL . '/index.php?url=album/show/' . $albumId);
            exit;
        }
    }
    
    // NOVÁ METODA: Zobrazení formuláře pro úpravu komentáře
    public function edit($id = null) {
        if (!$id || !isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';

        $db = (new Database())->getConnection();
        $commentModel = new Comment($db);
        $comment = $commentModel->getById($id);

        if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
            $_SESSION['messages']['error'][] = 'Tento komentář nemůžete upravovat.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/comments/comment_edit.php';
    }

    // NOVÁ METODA: Uložení upraveného komentáře do DB
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && isset($_SESSION['user_id'])) {
            require_once '../app/models/Database.php';
            require_once '../app/models/Comment.php';

            $db = (new Database())->getConnection();
            $commentModel = new Comment($db);
            $comment = $commentModel->getById($id);

            if ($comment && $comment['user_id'] == $_SESSION['user_id']) {
                $content = htmlspecialchars($_POST['content'] ?? '');
                if (!empty($content)) {
                    $commentModel->update($id, $content);
                    $_SESSION['messages']['success'][] = 'Komentář byl úspěšně upraven.';
                } else {
                    $_SESSION['messages']['error'][] = 'Komentář nemůže být prázdný.';
                }
                header('Location: ' . BASE_URL . '/index.php?url=album/show/' . $comment['album_id']);
                exit;
            }
        }
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

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

        $albumId = $comment['album_id'];
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

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