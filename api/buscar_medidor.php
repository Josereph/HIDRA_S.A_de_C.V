<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
if (empty($q)) {
    echo json_encode(['success' => false, 'error' => 'Término de búsqueda vacío']);
    exit;
}

try {
    $pdo = Database::getInstance();
    
    // Buscar en la vista proporcionada por el script SQL de inicialización
    $stmt = $pdo->prepare("
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
