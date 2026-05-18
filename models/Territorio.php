<?php
// models/Territorio.php
require_once __DIR__ . '/../config/database.php';

class Territorio {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // ══════════════════════════════════════════════
    // SECTORES
    // ══════════════════════════════════════════════

    public function getSectores() {
        $stmt = $this->pdo->query("
            SELECT s.*,
                   (SELECT COUNT(*) FROM viviendas v WHERE v.sector_id = s.id_sector) AS total_viviendas,
                   (SELECT COUNT(*) FROM viviendas v WHERE v.sector_id = s.id_sector AND v.estado = 'Activa') AS viviendas_activas,
                   (SELECT COUNT(*) FROM viviendas v WHERE v.sector_id = s.id_sector AND v.estado = 'Suspendida') AS viviendas_suspendidas
            FROM sectores s
            ORDER BY s.nombre_sector ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Consulta ligera: solo sectores activos con id y nombre.
     * Usada para poblar dinámicamente el <select> del modal de vivienda.
     */
    public function getSectoresActivos() {
        $stmt = $this->pdo->query("
            SELECT id_sector, nombre_sector
            FROM sectores
            WHERE estado = 'activo'
            ORDER BY nombre_sector ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getSectorById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM sectores WHERE id_sector = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createSector($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO sectores (nombre_sector, descripcion, estado)
            VALUES (:nombre_sector, :descripcion, :estado)
        ");
        return $stmt->execute([
            'nombre_sector' => $data['nombre_sector'],
            'descripcion'   => $data['descripcion'] ?? null,
            'estado'        => $data['estado'] ?? 'activo',
        ]);
    }

    public function updateSector($data) {
        $stmt = $this->pdo->prepare("
            UPDATE sectores
            SET nombre_sector = :nombre_sector,
                descripcion   = :descripcion,
                estado        = :estado
            WHERE id_sector = :id
        ");
        return $stmt->execute([
            'nombre_sector' => $data['nombre_sector'],
            'descripcion'   => $data['descripcion'] ?? null,
            'estado'        => $data['estado'],
            'id'            => $data['id_sector'],
        ]);
    }

    public function deleteSector($id) {
        // Verifica que no tenga viviendas
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM viviendas WHERE sector_id = :id");
        $check->execute(['id' => $id]);
        if ($check->fetchColumn() > 0) {
            return ['error' => 'No se puede eliminar: el sector tiene viviendas registradas.'];
        }
        $stmt = $this->pdo->prepare("DELETE FROM sectores WHERE id_sector = :id");
        $stmt->execute(['id' => $id]);
        return ['success' => true];
    }

    // ══════════════════════════════════════════════
    // VIVIENDAS
    // ══════════════════════════════════════════════

    public function getViviendas(int $page = 1, int $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("
            SELECT v.*,
                   s.nombre_sector,
                   CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente_nombre,
                   u.codigo_usuario,
                   u.telefono AS cliente_tel
            FROM viviendas v
            INNER JOIN sectores s ON v.sector_id = s.id_sector
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
            ORDER BY v.fecha_creacion DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countViviendas(): int {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM viviendas")->fetchColumn();
    }

    public function getViviendasBySector(int $sectorId) {
        $stmt = $this->pdo->prepare("
            SELECT v.*,
                   s.nombre_sector,
                   CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente_nombre,
                   u.codigo_usuario,
                   u.telefono AS cliente_tel,
                   u.correo AS cliente_correo
            FROM viviendas v
            INNER JOIN sectores s ON v.sector_id = s.id_sector
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
            WHERE v.sector_id = :sector_id
            ORDER BY v.direccion ASC
        ");
        $stmt->execute(['sector_id' => $sectorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getViviendaById(int $id) {
        $stmt = $this->pdo->prepare("
            SELECT v.*,
                   s.nombre_sector,
                   CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente_nombre,
                   u.codigo_usuario,
                   u.telefono AS cliente_tel,
                   u.correo AS cliente_correo,
                   u.dui AS cliente_dui,
                   u.estado AS cliente_estado,
                   u.direccion AS cliente_direccion,
                   m.numero_medidor, m.marca AS medidor_marca,
                   m.estado AS medidor_estado
            FROM viviendas v
            INNER JOIN sectores s ON v.sector_id = s.id_sector
            LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
            LEFT JOIN medidores m ON u.id_usuario = m.id_usuario
            WHERE v.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createVivienda($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO viviendas (sector_id, cliente_id, direccion, lat, lng, estado)
            VALUES (:sector_id, :cliente_id, :direccion, :lat, :lng, :estado)
        ");
        return $stmt->execute([
            'sector_id'  => $data['sector_id'],
            'cliente_id' => !empty($data['cliente_id']) ? $data['cliente_id'] : null,
            'direccion'  => $data['direccion'],
            'lat'        => !empty($data['lat']) ? $data['lat'] : null,
            'lng'        => !empty($data['lng']) ? $data['lng'] : null,
            'estado'     => $data['estado'] ?? 'En revisión',
        ]);
    }

    public function updateVivienda($data) {
        $stmt = $this->pdo->prepare("
            UPDATE viviendas
            SET sector_id  = :sector_id,
                cliente_id = :cliente_id,
                direccion  = :direccion,
                lat        = :lat,
                lng        = :lng,
                estado     = :estado
            WHERE id = :id
        ");
        return $stmt->execute([
            'sector_id'  => $data['sector_id'],
            'cliente_id' => !empty($data['cliente_id']) ? $data['cliente_id'] : null,
            'direccion'  => $data['direccion'],
            'lat'        => !empty($data['lat']) ? $data['lat'] : null,
            'lng'        => !empty($data['lng']) ? $data['lng'] : null,
            'estado'     => $data['estado'],
            'id'         => $data['id'],
        ]);
    }

    public function deleteVivienda(int $id) {
        $stmt = $this->pdo->prepare("DELETE FROM viviendas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return ['success' => true];
    }

    // ══════════════════════════════════════════════
    // AUXILIARES
    // ══════════════════════════════════════════════

    public function getClientesDisponibles() {
        $stmt = $this->pdo->query("
            SELECT id_usuario, CONCAT(nombres, ' ', IFNULL(apellidos,'')) AS nombre_completo, codigo_usuario
            FROM usuarios
            WHERE estado = 'activo'
            ORDER BY nombres ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
