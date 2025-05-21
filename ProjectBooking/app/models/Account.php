<?php
require_once "config/database.php";

class Account {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function checkLogin($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM accounts WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        return password_verify($password, $user["password"]);
    }
}
?>
