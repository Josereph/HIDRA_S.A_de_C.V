<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) $data = $_POST;

if (empty($data['id_medidor']) || !isset($data['lectura_act']) || empty($data['mes']) || empty($data['anio'])) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos (id_medidor, lectura_act, mes, anio).']);
    exit;
}

try {
    $pdo = Database::getInstance();
    
    // Obtener lectura anterior real
    $stmt = $pdo->prepare("SELECT lectura_actual FROM lecturas WHERE id_medidor = ? ORDER BY anio DESC, mes DESC LIMIT 1");
    $stmt->execute([$data['id_medidor']]);
    $prev = $stmt->fetchColumn();
    
    if ($prev === false) {
        $stmt = $pdo->prepare("SELECT lectura_inicial FROM medidores WHERE id_medidor = ?");
        $stmt->execute([$data['id_medidor']]);
        $prev = $stmt->fetchColumn();
        if ($prev === false) $prev = 0;
    }

    if (floatval($data['lectura_act']) < floatval($prev)) {
        echo json_encode(['success' => false, 'error' => 'La lectura actual ('.$data['lectura_act'].') no puede ser menor a la lectura anterior ('.$prev.').']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO lecturas (id_medidor, mes, anio, lectura_anterior, lectura_actual, fecha_lectura) VALUES (?, ?, ?, ?, ?, CURDATE())");
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
    if ($e->getCode() == 23000) { // Constraint violation
        echo json_encode(['success' => false, 'error' => 'Ya existe una lectura para este medidor en este periodo.']);
    } else {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
