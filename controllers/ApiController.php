<?php
require_once __DIR__ . '/../models/Territorio.php';
require_once __DIR__ . '/../models/Cliente.php';

class ApiController {
    private $territorioModel;
    private $clienteModel;
    private $pdo;

    public function __construct() {
        $this->territorioModel = new Territorio();
        $this->clienteModel = new Cliente();
        $this->pdo = Database::getInstance();
        header('Content-Type: application/json');
    }

    // ════════════════════════════════════════════════════════════════
    // METER OPERATIONS
    // ════════════════════════════════════════════════════════════════

    public function searchMeter() {
        $q = $_GET['q'] ?? '';
        if (empty($q)) {
            echo json_encode(['success' => false, 'error' => 'Término de búsqueda vacío']);
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT v.*,
                       (SELECT lectura_actual
                        FROM lecturas l
                        WHERE l.id_medidor = v.id_medidor
                        ORDER BY l.anio DESC, l.mes DESC
                        LIMIT 1) as ultima_lectura_val,
                       (SELECT fecha_lectura
                        FROM lecturas l
                        WHERE l.id_medidor = v.id_medidor
                        ORDER BY l.anio DESC, l.mes DESC
                        LIMIT 1) as ultima_lectura_fecha,
                       (SELECT lectura_inicial FROM medidores m WHERE m.id_medidor = v.id_medidor LIMIT 1) as lectura_inicial
                FROM vista_clientes_medidores v
                WHERE v.numero_medidor LIKE :q1 OR v.cliente LIKE :q2 OR v.codigo_usuario LIKE :q3
                LIMIT 1
            ");
            $stmt->execute(['q1' => "%$q%", 'q2' => "%$q%", 'q3' => "%$q%"]);
            $row = $stmt->fetch();

            if ($row) {
                $ultima_lectura = $row['ultima_lectura_val'] !== null ? $row['ultima_lectura_val'] : ($row['lectura_inicial'] ?? 0);
                $fecha_lectura = $row['ultima_lectura_fecha'] ?? 'Inicial';

                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id_medidor' => $row['id_medidor'],
                        'numero_medidor' => $row['numero_medidor'],
                        'cliente' => $row['cliente'],
                        'sector' => $row['sector'],
                        'lectura_anterior' => floatval($ultima_lectura),
                        'fecha_lectura' => $fecha_lectura
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No se encontró el medidor o cliente.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function saveMeter() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) $data = $_POST;

        if (empty($data['id_medidor']) || !isset($data['lectura_act']) || empty($data['mes']) || empty($data['anio'])) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos (id_medidor, lectura_act, mes, anio).']);
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT lectura_actual FROM lecturas WHERE id_medidor = ? ORDER BY anio DESC, mes DESC LIMIT 1");
            $stmt->execute([$data['id_medidor']]);
            $prev = $stmt->fetchColumn();

            if ($prev === false) {
                $stmt = $this->pdo->prepare("SELECT lectura_inicial FROM medidores WHERE id_medidor = ?");
                $stmt->execute([$data['id_medidor']]);
                $prev = $stmt->fetchColumn();
                if ($prev === false) $prev = 0;
            }

            if (floatval($data['lectura_act']) < floatval($prev)) {
                echo json_encode(['success' => false, 'error' => 'La lectura actual ('.$data['lectura_act'].') no puede ser menor a la lectura anterior ('.$prev.').']);
                exit;
            }

            $stmt = $this->pdo->prepare("INSERT INTO lecturas (id_medidor, mes, anio, lectura_anterior, lectura_actual, fecha_lectura) VALUES (?, ?, ?, ?, ?, CURDATE())");
            $stmt->execute([
                $data['id_medidor'],
                $data['mes'],
                $data['anio'],
                $prev,
                $data['lectura_act']
            ]);

            echo json_encode([
                'success' => true,
                'consumo' => floatval($data['lectura_act']) - floatval($prev)
            ]);

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo json_encode(['success' => false, 'error' => 'Ya existe una lectura para este medidor en este periodo.']);
            } else {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    // ════════════════════════════════════════════════════════════════
    // TERRITORY OPERATIONS (Sectors & Houses)
    // ════════════════════════════════════════════════════════════════

    public function sectorStore() {
        $data = $this->getRequestData();
        if ($this->territorioModel->createSector($data)) {
            echo json_encode(['success' => true, 'message' => 'Sector creado correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al crear el sector']);
        }
    }

    public function sectorUpdate() {
        $data = $this->getRequestData();
        if (isset($data['id']) && $this->territorioModel->updateSector($data)) {
            echo json_encode(['success' => true, 'message' => 'Sector actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar el sector']);
        }
    }

    public function sectorDelete() {
        $data = $this->getRequestData();
        if (isset($data['id']) && $this->territorioModel->deleteSector($data['id'])) {
            echo json_encode(['success' => true, 'message' => 'Sector eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el sector']);
        }
    }

    public function houseStore() {
        $data = $this->getRequestData();
        if ($this->territorioModel->createCasa($data)) {
            echo json_encode(['success' => true, 'message' => 'Casa creada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al crear la casa']);
        }
    }

    public function houseUpdate() {
        $data = $this->getRequestData();
        if (isset($data['house_id']) && $this->territorioModel->updateCasa($data)) {
            echo json_encode(['success' => true, 'message' => 'Casa actualizada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar la casa']);
        }
    }

    public function houseDelete() {
        $data = $this->getRequestData();
        if (isset($data['id']) && $this->territorioModel->deleteCasa($data['id'])) {
            echo json_encode(['success' => true, 'message' => 'Casa eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar la casa']);
        }
    }

    // ════════════════════════════════════════════════════════════════
    // CLIENT OPERATIONS
    // ════════════════════════════════════════════════════════════════

    public function clientStore() {
        $data = $this->getRequestData();
        if ($this->clienteModel->create($data)) {
            echo json_encode(['success' => true, 'message' => 'Cliente creado correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al crear el cliente']);
        }
    }

    public function clientDelete() {
        $data = $this->getRequestData();
        if (isset($data['id'])) {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            if ($stmt->execute([$data['id']])) {
                echo json_encode(['success' => true, 'message' => 'Cliente eliminado correctamente']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar el cliente']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'ID de cliente requerido']);
        }
    }

    // ════════════════════════════════════════════════════════════════
    // HELPER METHODS
    // ════════════════════════════════════════════════════════════════

    private function getRequestData() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }
        return $data ?? [];
    }
}
