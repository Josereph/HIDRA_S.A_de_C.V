<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['operador_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

// Datos esperados
$id_usuario = $input['id_usuario'] ?? '';
$id_lectura = $input['id_lectura'] ?? null; // Puede ser null
$id_tarifa  = $input['id_tarifa'] ?? 1;
$mes        = $input['mes'] ?? date('n');
$anio       = $input['anio'] ?? date('Y');
$consumo_m3 = $input['consumo_m3'] ?? 0;
$cargo_fijo = $input['cargo_fijo'] ?? 0;
$precio_m3  = $input['precio_m3'] ?? 0;
$mora       = $input['monto_mora'] ?? 0;

$id_operador = $_SESSION['operador_id'];

if (empty($id_usuario)) {
    echo json_encode(['success' => false, 'error' => 'Usuario requerido']);
    exit;
}

$pdo = Database::getInstance();

try {
    // Generar numero de factura ej: FAC-202605-001
    $numero_factura = 'FAC-' . $anio . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . strtoupper(uniqid());

    // Asegurar que no exista ya para este mes/año (por constraint único)
    $stmt = $pdo->prepare("
        INSERT INTO facturas (
            numero_factura, id_usuario, id_lectura, id_tarifa, id_operador_emite, 
            mes, anio, fecha_emision, fecha_vencimiento, 
            consumo_m3, cargo_fijo, precio_m3, monto_mora, estado
        ) VALUES (
            :num, :usu, :lec, :tar, :op, 
            :mes, :anio, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY),
            :cons, :fijo, :pre, :mora, 'pendiente'
        )
    ");
    
    // Si no hay id_lectura, pasamos una lectura dummy si es requerida o manejamos null si no lo es
    // Ojo: id_lectura es UNIQUE NOT NULL según el esquema si la factura es por lectura,
    // pero podemos hacer un bypass buscando la última lectura del usuario.
    if (!$id_lectura) {
        $st = $pdo->prepare("SELECT id_lectura FROM lecturas l JOIN medidores m ON l.id_medidor = m.id_medidor WHERE m.id_usuario = ? ORDER BY id_lectura DESC LIMIT 1");
        $st->execute([$id_usuario]);
        $id_lectura = $st->fetchColumn();
    }
    
    if (!$id_lectura) {
        // En caso de que no haya ninguna lectura y sea obligatorio, creamos una dummy
        // Necesitamos un medidor
        $stM = $pdo->prepare("SELECT id_medidor FROM medidores WHERE id_usuario = ? LIMIT 1");
        $stM->execute([$id_usuario]);
        $id_medidor = $stM->fetchColumn();
        
        if (!$id_medidor) {
            $pdo->exec("INSERT INTO medidores (id_usuario, numero_medidor, fecha_instalacion) VALUES ($id_usuario, 'M-DUMMY-$id_usuario', CURDATE())");
            $id_medidor = $pdo->lastInsertId();
        }

        $pdo->exec("INSERT INTO lecturas (id_medidor, mes, anio, lectura_anterior, lectura_actual, fecha_lectura) VALUES ($id_medidor, $mes, $anio, 0, $consumo_m3, CURDATE())");
        $id_lectura = $pdo->lastInsertId();
    }

    $stmt->execute([
        'num'  => $numero_factura,
        'usu'  => $id_usuario,
        'lec'  => $id_lectura,
        'tar'  => $id_tarifa,
        'op'   => $id_operador,
        'mes'  => $mes,
        'anio' => $anio,
        'cons' => $consumo_m3,
        'fijo' => $cargo_fijo,
        'pre'  => $precio_m3,
        'mora' => $mora
    ]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error BD: ' . $e->getMessage()]);
}
