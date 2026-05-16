<?php
// api/territorio_api.php
// Endpoint AJAX para el módulo de Territorio (sectores y viviendas)

session_start();

// Autenticación básica
if (!isset($_SESSION['operador_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Territorio.php';

header('Content-Type: application/json');

$territorio = new Territorio();
$method     = $_SERVER['REQUEST_METHOD'];
$action     = $_GET['action'] ?? '';

// ── Leer body JSON ──────────────────────────────────
$input = [];
if ($method === 'POST' || $method === 'PUT' || $method === 'DELETE') {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true) ?? [];
    // Fallback a $_POST si no viene JSON
    if (empty($input)) $input = $_POST;
}

try {
    switch ($action) {

        // ══ SECTORES ══════════════════════════════════

        case 'get_sectores':
            echo json_encode(['success' => true, 'data' => $territorio->getSectores()]);
            break;

        // Solo id + nombre de sectores activos — para select dinámicos del modal vivienda
        case 'get_sectores_select':
            echo json_encode(['success' => true, 'data' => $territorio->getSectoresActivos()]);
            break;


        case 'get_sector':
            $id = (int)($_GET['id'] ?? 0);
            $sector = $territorio->getSectorById($id);
            echo json_encode($sector ? ['success' => true, 'data' => $sector] : ['success' => false, 'message' => 'Sector no encontrado']);
            break;

        case 'create_sector':
            $nombre = trim($input['nombre_sector'] ?? '');
            if (empty($nombre)) {
                echo json_encode(['success' => false, 'message' => 'El nombre del sector es obligatorio']);
                break;
            }
            $ok = $territorio->createSector([
                'nombre_sector' => $nombre,
                'descripcion'   => trim($input['descripcion'] ?? ''),
                'estado'        => $input['estado'] ?? 'activo',
            ]);
            echo json_encode($ok
                ? ['success' => true, 'message' => 'Sector creado correctamente']
                : ['success' => false, 'message' => 'Error al crear el sector']
            );
            break;

        case 'update_sector':
            $id = (int)($input['id_sector'] ?? 0);
            $nombre = trim($input['nombre_sector'] ?? '');
            if (!$id || empty($nombre)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                break;
            }
            $ok = $territorio->updateSector([
                'id_sector'     => $id,
                'nombre_sector' => $nombre,
                'descripcion'   => trim($input['descripcion'] ?? ''),
                'estado'        => $input['estado'] ?? 'activo',
            ]);
            echo json_encode($ok
                ? ['success' => true, 'message' => 'Sector actualizado']
                : ['success' => false, 'message' => 'Error al actualizar']
            );
            break;

        case 'delete_sector':
            $id = (int)($input['id'] ?? 0);
            if (!$id) { echo json_encode(['success' => false, 'message' => 'ID inválido']); break; }
            $result = $territorio->deleteSector($id);
            echo json_encode(isset($result['error'])
                ? ['success' => false, 'message' => $result['error']]
                : ['success' => true, 'message' => 'Sector eliminado']
            );
            break;

        // ══ VIVIENDAS ══════════════════════════════════

        case 'get_viviendas':
            $page    = max(1, (int)($_GET['page'] ?? 1));
            $perPage = 10;
            $list    = $territorio->getViviendas($page, $perPage);
            $total   = $territorio->countViviendas();
            echo json_encode([
                'success'    => true,
                'data'       => $list,
                'total'      => $total,
                'page'       => $page,
                'per_page'   => $perPage,
                'last_page'  => (int)ceil($total / $perPage),
            ]);
            break;

        case 'get_viviendas_sector':
            $sectorId = (int)($_GET['sector_id'] ?? 0);
            echo json_encode(['success' => true, 'data' => $territorio->getViviendasBySector($sectorId)]);
            break;

        case 'get_vivienda':
            $id = (int)($_GET['id'] ?? 0);
            $v  = $territorio->getViviendaById($id);
            echo json_encode($v ? ['success' => true, 'data' => $v] : ['success' => false, 'message' => 'Vivienda no encontrada']);
            break;

        case 'create_vivienda':
            $direccion = trim($input['direccion'] ?? '');
            $sectorId  = (int)($input['sector_id'] ?? 0);
            if (empty($direccion) || !$sectorId) {
                echo json_encode(['success' => false, 'message' => 'Dirección y sector son obligatorios']);
                break;
            }
            $ok = $territorio->createVivienda([
                'sector_id'  => $sectorId,
                'cliente_id' => $input['cliente_id'] ?? null,
                'direccion'  => $direccion,
                'lat'        => $input['lat'] ?? null,
                'lng'        => $input['lng'] ?? null,
                'estado'     => $input['estado'] ?? 'En revisión',
            ]);
            echo json_encode($ok
                ? ['success' => true, 'message' => 'Vivienda creada correctamente']
                : ['success' => false, 'message' => 'Error al crear la vivienda']
            );
            break;

        case 'update_vivienda':
            $id        = (int)($input['id'] ?? 0);
            $direccion = trim($input['direccion'] ?? '');
            $sectorId  = (int)($input['sector_id'] ?? 0);
            if (!$id || empty($direccion) || !$sectorId) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                break;
            }
            $ok = $territorio->updateVivienda([
                'id'         => $id,
                'sector_id'  => $sectorId,
                'cliente_id' => $input['cliente_id'] ?? null,
                'direccion'  => $direccion,
                'lat'        => $input['lat'] ?? null,
                'lng'        => $input['lng'] ?? null,
                'estado'     => $input['estado'] ?? 'En revisión',
            ]);
            echo json_encode($ok
                ? ['success' => true, 'message' => 'Vivienda actualizada']
                : ['success' => false, 'message' => 'Error al actualizar']
            );
            break;

        case 'delete_vivienda':
            $id = (int)($input['id'] ?? 0);
            if (!$id) { echo json_encode(['success' => false, 'message' => 'ID inválido']); break; }
            $result = $territorio->deleteVivienda($id);
            echo json_encode(['success' => true, 'message' => 'Vivienda eliminada']);
            break;

        case 'get_clientes':
            echo json_encode(['success' => true, 'data' => $territorio->getClientesDisponibles()]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Acción no válida: ' . htmlspecialchars($action)]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
