<?php

class UserController {

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro zobrazení profilu se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db = (new Database())->getConnection();
        $userModel = new User($db);
        
        // Používáme tvoji metodu findById
        $user = $userModel->findById($_SESSION['user_id']);

        require_once '../app/views/users/profile.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            require_once '../app/models/Database.php';
            require_once '../app/models/User.php';

            $db = (new Database())->getConnection();
            $userModel = new User($db);

            $username = htmlspecialchars($_POST['username'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $firstName = htmlspecialchars($_POST['first_name'] ?? '');
            $lastName = htmlspecialchars($_POST['last_name'] ?? '');
            $nickname = htmlspecialchars($_POST['nickname'] ?? '');

            if ($userModel->updateProfile($_SESSION['user_id'], $username, $email, $firstName, $lastName, $nickname)) {
                $_SESSION['user_name'] = $username; 
                $this->addSuccessMessage('Profil byl úspěšně aktualizován.');
            } else {
                $this->addErrorMessage('Nepodařilo se aktualizovat profil.');
            }
            header('Location: ' . BASE_URL . '/index.php?url=user/profile');
            exit;
        }
    }

    public function index() {
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění k zobrazení této stránky.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db = (new Database())->getConnection();
        $userModel = new User($db);
        $users = $userModel->getAll();

        require_once '../app/views/users/users_list.php';
    }

    public function delete($id = null) {
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění mazat uživatele.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($id == $_SESSION['user_id']) {
            $this->addErrorMessage('Nemůžete smazat sami sebe!');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';

        $db = (new Database())->getConnection();
        $userModel = new User($db);
        
        if ($userModel->delete($id)) {
            $this->addSuccessMessage('Uživatel byl úspěšně smazán.');
        } else {
            $this->addErrorMessage('Chyba při mazání uživatele.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=user/index');
        exit;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
}