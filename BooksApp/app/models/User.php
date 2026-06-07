<?php

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    // 1. Registrace nového uživatele (nyní přijímá i jméno, příjmení a přezdívku)
    public function register(
        string $username, 
        string $email, 
        string $password, 
        ?string $firstName = null, 
        ?string $lastName = null, 
        ?string $nickname = null
    ): bool {
        // Kontrola, zda uživatel s tímto emailem už neexistuje
        if ($this->findByEmail($email)) {
            return false; // Email už je zabraný
        }

        // ZABEZPEČENÍ: Vytvoření bezpečného hashe z hesla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL dotaz rozšířen o nová pole
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, nickname) 
                VALUES (:username, :email, :password, :first_name, :last_name, :nickname)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword, // Do DB ukládáme hash, nikoliv čisté heslo!
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname
        ]);
    }

    // 2. Nalezení uživatele podle emailu (použijeme při přihlašování)
    public function findByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        // Vrátí pole s daty uživatele, nebo false pokud neexistuje
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 3. Získání uživatele podle ID (hodí se pro zobrazení profilu atd.)
    public function findById(int $id) {
        // Zde jsme také přidali nová pole, aby se nám při načtení profilu vypsalo všechno
        $sql = "SELECT id, username, email, first_name, last_name, nickname, created_at FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        // TADY CHYBĚLO UZAVŘENÍ FUNKCE FINDBYID
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Úprava profilu (včetně nových polí)
    public function updateProfile($id, $username, $email, $firstName, $lastName, $nickname) {
        $sql = "UPDATE users SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, nickname = :nickname WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':username' => $username,
            ':email' => $email,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname
        ]);
    }

    // 5. Načtení všech uživatelů (pro seznam v administraci)
    public function getAll() {
        $sql = "SELECT id, username, email, first_name, last_name, is_admin, created_at FROM users ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Smazání uživatele (pro administrátora)
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}