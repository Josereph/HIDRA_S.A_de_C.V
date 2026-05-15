<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$id_factura = $input['id_factura'] ?? '';
$monto = $input['monto_pagado'] ?? 0;
$metodo = $input['metodo_pago'] ?? 'efectivo';
$referencia = $input['referencia'] ?? null;
$id_operador = $_SESSION['operador_id'];

if (empty($id_factura) || $monto <= 0) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

$pdo = Database::getInstance();

try {
    // Insertar el pago (El trigger trg_pagos_after_insert actualizará la factura)
    $stmt = $pdo->prepare("
        INSERT INTO pagos (id_factura, id_operador_registra, fecha_pago, monto_pagado, metodo_pago, referencia)
        VALUES (:id_factura, :id_operador, NOW(), :monto, :metodo, :referencia)
    ");
    
    $stmt->execute([
        'id_factura' => $id_factura,
        'id_operador' => $id_operador,
        'monto' => $monto,
        'metodo' => $metodo,
        'referencia' => $referencia
    ]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error BD: ' . $e->getMessage()]);
}
