<?php
require_once __DIR__ . '/../config/database.php';

class Territorio {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getSectores() {
        $stmt = $this->pdo->query("SELECT id_sector as id, nombre_sector as nombre FROM sectores WHERE estado = 'activo' ORDER BY nombre_sector ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCasas() {
        $stmt = $this->pdo->query("
            SELECT 
                v.id, v.sector_id, v.direccion, v.lat, v.lng, v.estado, v.cliente_id,
                CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) as cliente_nombre
            FROM viviendas v
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCasa($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO viviendas (sector_id, direccion, lat, lng, estado)
            VALUES (:sector_id, :direccion, :lat, :lng, 'En revisión')
        ");
        return $stmt->execute([
            'sector_id' => $data['sector_id'],
            'direccion' => $data['direccion'],
            'lat' => $data['lat'],
            'lng' => $data['lng']
        ]);
    }

    public function updateCasa($data) {
        $stmt = $this->pdo->prepare("
            UPDATE viviendas
            SET cliente_id = :cliente_id, estado = :estado
            WHERE id = :id
        ");
        return $stmt->execute([
            'cliente_id' => !empty($data['cliente_id']) ? $data['cliente_id'] : null,
            'estado' => $data['estado'],
            'id' => $data['house_id']
        ]);
    }
}
