<?php
require_once __DIR__ . '/../config/database.php';

class Operador {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findByEmailOrUsername($identifier) {
        $stmt = $this->pdo->prepare("SELECT * FROM operadores WHERE usuario = :user OR correo = :email LIMIT 1");
        $stmt->execute(['user' => $identifier, 'email' => $identifier]);
        return $stmt->fetch();
    }
}
