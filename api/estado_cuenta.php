<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$codigo = trim($_GET['codigo'] ?? '');

if (empty($codigo)) {
    echo json_encode(['success' => false, 'error' => 'Código vacío']);
    exit;
}

try {
    $pdo = Database::getInstance();

    // 1. Obtener usuario
    $stmt = $pdo->prepare("SELECT id_usuario, codigo_usuario, CONCAT(nombres, ' ', IFNULL(apellidos, '')) as cliente FROM usuarios WHERE codigo_usuario = :codigo");
    $stmt->execute(['codigo' => $codigo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['success' => false, 'error' => 'Cliente no encontrado']);
        exit;
    }

    $id_usuario = $usuario['id_usuario'];

    // 2. KPIs
    // Saldo pendiente total
    $stmtSaldo = $pdo->prepare("SELECT SUM(saldo_pendiente) FROM facturas WHERE id_usuario = ? AND estado IN ('pendiente', 'vencida')");
    $stmtSaldo->execute([$id_usuario]);
    $saldo_pendiente = (float)$stmtSaldo->fetchColumn();

    // Total pagado este año
    $anio_actual = date('Y');
    $stmtPagado = $pdo->prepare("
        SELECT SUM(p.monto_pagado) 
        FROM pagos p
        JOIN facturas f ON p.id_factura = f.id_factura
        WHERE f.id_usuario = ? AND YEAR(p.fecha_pago) = ?
    ");
    $stmtPagado->execute([$id_usuario, $anio_actual]);
    $pagado_anio = (float)$stmtPagado->fetchColumn();

    // Facturas vencidas
    $stmtVencidas = $pdo->prepare("SELECT COUNT(*) FROM facturas WHERE id_usuario = ? AND estado = 'vencida'");
    $stmtVencidas->execute([$id_usuario]);
    $vencidas = (int)$stmtVencidas->fetchColumn();

    // Consumo total este año
    $stmtConsumo = $pdo->prepare("SELECT SUM(consumo_m3) FROM facturas WHERE id_usuario = ? AND anio = ?");
    $stmtConsumo->execute([$id_usuario, $anio_actual]);
    $consumo_total = (float)$stmtConsumo->fetchColumn();

    // 3. Historial de facturas (últimas 12)
    $stmtHistorial = $pdo->prepare("
        SELECT mes, anio, consumo_m3, total, saldo_pendiente, estado
        FROM facturas 
        WHERE id_usuario = ? 
        ORDER BY anio DESC, mes DESC
        LIMIT 12
    ");
    $stmtHistorial->execute([$id_usuario]);
    $historial = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

    // Mapear meses
    $meses = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    foreach ($historial as &$h) {
        $h['mes_nombre'] = $meses[(int)$h['mes']] . ' ' . $h['anio'];
        $h['pagado'] = $h['total'] - $h['saldo_pendiente'];
    }

    echo json_encode([
        'success' => true,
        'cliente' => $usuario,
        'kpis' => [
            'saldo_pendiente' => $saldo_pendiente,
            'pagado_anio' => $pagado_anio,
            'vencidas' => $vencidas,
            'consumo_total' => $consumo_total
        ],
        'historial' => $historial
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
}
