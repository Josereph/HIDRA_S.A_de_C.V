<?php
// models/Client.php

class Client {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO clientes (tipo_persona, nombre, identificador, historial) VALUES (:tipo, :nombre, :identificador, :historial)");
        return $stmt->execute([
            ':tipo' => $data['tipo_persona'],
            ':nombre' => $data['nombre'],
            ':identificador' => $data['identificador'],
            ':historial' => $data['historial'] ?? ''
        ]);
    }
}
