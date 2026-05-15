<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$q = trim($_GET['q'] ?? '');
if (empty($q)) {
    echo json_encode(['success' => false, 'error' => 'Búsqueda vacía']);
    exit;
}

try {
    $pdo = Database::getInstance();

    // Buscar estrictamente en vista_facturas_pendientes por codigo_usuario
    $stmt = $pdo->prepare("
        SELECT id_factura, numero_factura, mes, anio, total, saldo_pendiente, cliente
        FROM vista_facturas_pendientes
        WHERE codigo_usuario = :codigo
    ");
    $stmt->execute(['codigo' => $q]);
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'facturas' => $facturas]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error de servidor BD: ' . $e->getMessage()]);
}
