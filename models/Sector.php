<?php
// models/Sector.php

class Sector {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM sectores ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }
}
