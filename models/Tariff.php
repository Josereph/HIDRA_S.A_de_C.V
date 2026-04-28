<?php
// models/Tariff.php

class Tariff {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM tarifas ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }
}
