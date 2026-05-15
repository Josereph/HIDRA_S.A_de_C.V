<?php
require_once __DIR__ . '/../config/database.php';

class Territorio {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getSectores($includeInactive = true) {
        $sql = "
            SELECT 
                s.id_sector AS id,
                s.nombre_sector AS nombre,
                s.descripcion,
                s.estado,
                s.fecha_creacion,
                COUNT(v.id) AS total_casas
            FROM sectores s
            LEFT JOIN viviendas v ON v.sector_id = s.id_sector
        ";

        if (!$includeInactive) {
            $sql .= " WHERE s.estado = 'activo' ";
        }

        $sql .= "
            GROUP BY s.id_sector, s.nombre_sector, s.descripcion, s.estado, s.fecha_creacion
            ORDER BY 
                CASE WHEN s.estado = 'activo' THEN 0 ELSE 1 END,
                s.nombre_sector ASC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCasas() {
        $stmt = $this->pdo->query("
            SELECT 
                v.id,
                v.sector_id,
                v.cliente_id,
                v.direccion,
                v.lat,
                v.lng,
                v.estado,
                v.fecha_creacion,
                s.nombre_sector,
                s.estado AS estado_sector,
                TRIM(CONCAT(u.nombres, ' ', COALESCE(u.apellidos, ''))) AS cliente_nombre,
                COALESCE(u.codigo_usuario, u.dui, u.nit) AS cliente_codigo
            FROM viviendas v
            INNER JOIN sectores s ON v.sector_id = s.id_sector
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
            ORDER BY s.nombre_sector ASC, v.direccion ASC, v.id ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCasa($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO viviendas (sector_id, cliente_id, direccion, lat, lng, estado)
            VALUES (:sector_id, :cliente_id, :direccion, :lat, :lng, :estado)
        ");

        return $stmt->execute([
            'sector_id' => (int)$data['sector_id'],
            'cliente_id' => $this->nullableInt($data['cliente_id'] ?? null),
            'direccion' => $data['direccion'],
            'lat' => $this->nullableDecimal($data['lat'] ?? null),
            'lng' => $this->nullableDecimal($data['lng'] ?? null),
            'estado' => $this->normalizeCasaEstado($data['estado'] ?? 'En revisión')
        ]);
    }

    public function updateCasa($data) {
        $stmt = $this->pdo->prepare("
            UPDATE viviendas
            SET 
                sector_id = :sector_id,
                cliente_id = :cliente_id,
                direccion = :direccion,
                lat = :lat,
                lng = :lng,
                estado = :estado
            WHERE id = :id
        ");

        return $stmt->execute([
            'sector_id' => (int)$data['sector_id'],
            'cliente_id' => $this->nullableInt($data['cliente_id'] ?? null),
            'direccion' => $data['direccion'],
            'lat' => $this->nullableDecimal($data['lat'] ?? null),
            'lng' => $this->nullableDecimal($data['lng'] ?? null),
            'estado' => $this->normalizeCasaEstado($data['estado'] ?? 'En revisión'),
            'id' => (int)$data['id']
        ]);
    }

    public function deleteCasa($id) {
        $stmt = $this->pdo->prepare("DELETE FROM viviendas WHERE id = :id");
        return $stmt->execute(['id' => (int)$id]);
    }

    public function createTerritorio($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO sectores (nombre_sector, descripcion, estado)
            VALUES (:nombre, :descripcion, :estado)
        ");

        return $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? $data['descripcion'] : null,
            'estado' => $this->normalizeSectorEstado($data['estado'] ?? 'activo')
        ]);
    }

    public function updateTerritorio($data) {
        $stmt = $this->pdo->prepare("
            UPDATE sectores
            SET nombre_sector = :nombre,
                descripcion = :descripcion,
                estado = :estado
            WHERE id_sector = :id
        ");

        return $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? $data['descripcion'] : null,
            'estado' => $this->normalizeSectorEstado($data['estado'] ?? 'activo'),
            'id' => (int)$data['id']
        ]);
    }

    public function deleteTerritorio($id) {
        $stmt = $this->pdo->prepare("
            UPDATE sectores
            SET estado = 'inactivo'
            WHERE id_sector = :id
        ");
        return $stmt->execute(['id' => (int)$id]);
    }

    public function assignCasaTerritorio($idCasa, $idSector) {
        $stmt = $this->pdo->prepare("
            UPDATE viviendas
            SET sector_id = :id_sector
            WHERE id = :id_casa
        ");

        return $stmt->execute([
            'id_sector' => (int)$idSector,
            'id_casa' => (int)$idCasa
        ]);
    }

    private function nullableInt($value) {
        if ($value === null || $value === '') {
            return null;
        }
        return (int)$value;
    }

    private function nullableDecimal($value) {
        if ($value === null || $value === '') {
            return null;
        }
        return $value;
    }

    private function normalizeCasaEstado($estado) {
        $valid = ['Activa', 'Suspendida', 'En revisión'];
        return in_array($estado, $valid, true) ? $estado : 'En revisión';
    }

    private function normalizeSectorEstado($estado) {
        return $estado === 'inactivo' ? 'inactivo' : 'activo';
    }
}
