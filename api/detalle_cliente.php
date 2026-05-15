<?php
/* API: Detalle de cliente — facturas, pagos y mora */
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'ID de cliente requerido']);
    exit;
}

try {
    $pdo = Database::getInstance();

    // Datos generales del cliente
    $stmt = $pdo->prepare("
        SELECT u.*, 
               CONCAT(u.nombres, ' ', IFNULL(u.apellidos,'')) AS nombre_completo,
               tu.nombre_tipo AS tipo_usuario,
               s.nombre_sector AS sector,
               m.numero_medidor, m.estado AS estado_medidor
        FROM usuarios u
        LEFT JOIN tipos_usuario tu ON u.id_tipo_usuario = tu.id_tipo_usuario
        LEFT JOIN sectores s ON u.id_sector = s.id_sector
        LEFT JOIN medidores m ON u.id_usuario = m.id_usuario
        WHERE u.id_usuario = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    $cliente = $stmt->fetch();

    if (!$cliente) {
        echo json_encode(['success' => false, 'error' => 'Cliente no encontrado']);
        exit;
    }

    // Facturas del cliente
    $stmt = $pdo->prepare("
        SELECT f.id_factura, f.numero_factura, f.mes, f.anio,
               f.fecha_emision, f.fecha_vencimiento,
               f.consumo_m3, f.subtotal, f.monto_mora, f.descuento,
               f.total, f.saldo_pendiente, f.estado
        FROM facturas f
        WHERE f.id_usuario = :id
        ORDER BY f.anio DESC, f.mes DESC
    ");
    $stmt->execute(['id' => $id]);
    $facturas = $stmt->fetchAll();

    // Pagos del cliente
    $stmt = $pdo->prepare("
        SELECT p.id_pago, p.fecha_pago, p.monto_pagado, p.metodo_pago,
               p.referencia, p.observacion, p.estado AS estado_pago,
               f.numero_factura
        FROM pagos p
        JOIN facturas f ON p.id_factura = f.id_factura
        WHERE f.id_usuario = :id
        ORDER BY p.fecha_pago DESC
    ");
    $stmt->execute(['id' => $id]);
    $pagos = $stmt->fetchAll();

    // Resumen de mora
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS facturas_vencidas,
               IFNULL(SUM(f.saldo_pendiente), 0) AS deuda_total
        FROM facturas f
        WHERE f.id_usuario = :id
          AND f.estado IN ('pendiente','vencida')
    ");
    $stmt->execute(['id' => $id]);
    $mora = $stmt->fetch();

    echo json_encode([
        'success'  => true,
        'cliente'  => $cliente,
        'facturas' => $facturas,
        'pagos'    => $pagos,
        'mora'     => $mora
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
