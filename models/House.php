<?php
// models/House.php

class House {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getBySector($sector_id) {
        $stmt = $this->db->prepare("
            SELECT casas.*, clientes.nombre as cliente_nombre 
            FROM casas 
            LEFT JOIN clientes ON casas.cliente_id = clientes.id 
            WHERE sector_id = :sector_id
        ");
        $stmt->execute([':sector_id' => $sector_id]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO casas (sector_id, direccion, estado, lat, lng) VALUES (:sector_id, :direccion, :estado, :lat, :lng)");
        return $stmt->execute([
            ':sector_id' => $data['sector_id'],
            ':direccion' => $data['direccion'],
            ':estado' => 'Activa',
            ':lat' => $data['lat'],
            ':lng' => $data['lng']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE casas SET estado = :estado, cliente_id = :cliente_id WHERE id = :id");
        $cliente_id = !empty($data['cliente_id']) ? $data['cliente_id'] : null;
        return $stmt->execute([
            ':estado' => $data['estado'],
            ':cliente_id' => $cliente_id,
            ':id' => $id
        ]);
    }
}
