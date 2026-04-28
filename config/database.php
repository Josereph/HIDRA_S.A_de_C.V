<?php
// config/database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Path a la base de datos SQLite en la raíz del proyecto
        $dbPath = __DIR__ . '/../database.sqlite';
        $needsInit = !file_exists($dbPath);

        try {
            $this->pdo = new PDO("sqlite:" . $dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if ($needsInit) {
                $this->initDatabase();
            }
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }

    private function initDatabase() {
        $queries = [
            // Módulo 1: Clientes
            "CREATE TABLE IF NOT EXISTS clientes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tipo_persona TEXT NOT NULL, -- 'Natural' o 'Juridica'
                nombre TEXT NOT NULL,
                identificador TEXT NOT NULL UNIQUE, -- DUI o NIT
                historial TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",

            // Módulo 2: Territorio
            "CREATE TABLE IF NOT EXISTS sectores (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL UNIQUE
            )",

            "CREATE TABLE IF NOT EXISTS casas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                sector_id INTEGER NOT NULL,
                direccion TEXT NOT NULL,
                estado TEXT NOT NULL DEFAULT 'Activa', -- 'Activa', 'Suspendida', 'En revisión'
                cliente_id INTEGER,
                lat REAL,
                lng REAL,
                FOREIGN KEY (sector_id) REFERENCES sectores(id),
                FOREIGN KEY (cliente_id) REFERENCES clientes(id)
            )",

            // Módulo 3: Configuración
            "CREATE TABLE IF NOT EXISTS tarifas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                precio_base REAL NOT NULL
            )",

            "CREATE TABLE IF NOT EXISTS usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                rol TEXT NOT NULL DEFAULT 'Operador' -- 'Admin', 'Operador'
            )",

            // Datos Iniciales
            "INSERT INTO sectores (nombre) VALUES ('Sector Norte'), ('Sector Sur'), ('Residencial Las Margaritas')",
            "INSERT INTO tarifas (nombre, precio_base) VALUES ('Residencial', 10.50), ('Comercial', 25.00)"
        ];

        foreach ($queries as $query) {
            $this->pdo->exec($query);
        }
    }
}
