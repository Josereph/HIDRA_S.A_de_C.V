<?php
// models/User.php

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, nombre, email, rol FROM usuarios ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }
}
