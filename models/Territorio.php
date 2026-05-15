<?php
require_once __DIR__ . '/../config/database.php';

class Territorio {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // --- SECTORES CRUD ---
    public function getSectores() {
        $stmt = $this->pdo->query("SELECT id_sector as id, nombre_sector as nombre, descripcion, departamento, municipio, canton, villa, estado FROM sectores ORDER BY departamento, municipio, canton, villa, nombre_sector ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSectorById($id) {
        $stmt = $this->pdo->prepare("SELECT id_sector as id, nombre_sector as nombre, descripcion, departamento, municipio, canton, villa, estado FROM sectores WHERE id_sector = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createSector($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO sectores (nombre_sector, descripcion, departamento, municipio, canton, villa, estado)
            VALUES (:nombre, :descripcion, :departamento, :municipio, :canton, :villa, :estado)
        ");
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'departamento' => $data['departamento'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'canton' => $data['canton'] ?? null,
            'villa' => $data['villa'] ?? null,
            'estado' => $data['estado'] ?? 'activo'
        ]);
    }

    public function updateSector($data) {
        $stmt = $this->pdo->prepare("
            UPDATE sectores
            SET nombre_sector = :nombre, descripcion = :descripcion, departamento = :departamento, municipio = :municipio, canton = :canton, villa = :villa, estado = :estado
            WHERE id_sector = :id
        ");
        return $stmt->execute([
            'id' => $data['id'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'departamento' => $data['departamento'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'canton' => $data['canton'] ?? null,
            'villa' => $data['villa'] ?? null,
            'estado' => $data['estado'] ?? 'activo'
        ]);
    }

    public function deleteSector($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sectores WHERE id_sector = ?");
        return $stmt->execute([$id]);
    }

    // --- CASAS (VIVIENDAS) CRUD ---
    public function getCasas() {
        $stmt = $this->pdo->query("
            SELECT 
                v.id, v.sector_id, v.nombre, v.numero_medidor, v.direccion, v.lat, v.lng, v.estado, v.cliente_id,
                CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) as cliente_nombre
            FROM viviendas v
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCasasBySector($sector_id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                v.id, v.sector_id, v.nombre, v.numero_medidor, v.direccion, v.lat, v.lng, v.estado, v.cliente_id,
                CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) as cliente_nombre
            FROM viviendas v
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
            WHERE v.sector_id = ?
        ");
        $stmt->execute([$sector_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCasaById($id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                v.id, v.sector_id, v.nombre, v.numero_medidor, v.direccion, v.lat, v.lng, v.estado, v.cliente_id, v.fecha_creacion,
                CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) as cliente_nombre, u.telefono as cliente_telefono, u.correo as cliente_correo,
                s.nombre_sector as sector_nombre
            FROM viviendas v
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
            LEFT JOIN sectores s ON v.sector_id = s.id_sector
            WHERE v.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCasa($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO viviendas (sector_id, cliente_id, nombre, numero_medidor, direccion, lat, lng, estado)
            VALUES (:sector_id, :cliente_id, :nombre, :numero_medidor, :direccion, :lat, :lng, :estado)
        ");
        return $stmt->execute([
            'sector_id' => $data['sector_id'],
            'cliente_id' => !empty($data['cliente_id']) ? $data['cliente_id'] : null,
            'nombre' => $data['nombre'] ?? null,
            'numero_medidor' => $data['numero_medidor'] ?? null,
            'direccion' => $data['direccion'],
            'lat' => !empty($data['lat']) ? $data['lat'] : null,
            'lng' => !empty($data['lng']) ? $data['lng'] : null,
            'estado' => $data['estado'] ?? 'En revisión'
        ]);
    }

    public function updateCasa($data) {
        $stmt = $this->pdo->prepare("
            UPDATE viviendas
            SET sector_id = :sector_id, cliente_id = :cliente_id, nombre = :nombre, numero_medidor = :numero_medidor, direccion = :direccion, lat = :lat, lng = :lng, estado = :estado
            WHERE id = :id
        ");
        return $stmt->execute([
            'sector_id' => $data['sector_id'],
            'cliente_id' => !empty($data['cliente_id']) ? $data['cliente_id'] : null,
            'nombre' => $data['nombre'] ?? null,
            'numero_medidor' => $data['numero_medidor'] ?? null,
            'direccion' => $data['direccion'],
            'lat' => !empty($data['lat']) ? $data['lat'] : null,
            'lng' => !empty($data['lng']) ? $data['lng'] : null,
            'estado' => $data['estado'],
            'id' => $data['house_id']
        ]);
    }
    
    public function deleteCasa($id) {
        $stmt = $this->pdo->prepare("DELETE FROM viviendas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
