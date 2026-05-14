<?php
require_once __DIR__ . '/../config/database.php';

class ConfigController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function index() {
        // Obtener Tarifas
        $stmtTarifas = $this->pdo->query("SELECT id_tarifa as id, nombre_tarifa as nombre, precio_m3 as precio_base FROM tarifas");
        $tarifas = $stmtTarifas->fetchAll(PDO::FETCH_ASSOC);

        // Obtener Operadores
        $stmtOp = $this->pdo->query("SELECT nombre_completo as nombre, correo as email, rol FROM operadores");
        $usuarios = $stmtOp->fetchAll(PDO::FETCH_ASSOC);

        $content = __DIR__ . '/../views/config.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
