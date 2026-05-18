<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) $data = $_POST;

if (empty($data['nombres']) || empty($data['prefijo_codigo'])) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos.']);
    exit;
}

try {
    $pdo = Database::getInstance();

    $prefijo = $data['prefijo_codigo'] === 'JRD-' ? 'JRD' : 'USR';
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE codigo_usuario LIKE ?");
    $stmt->execute([$prefijo . '-%']);
    $count = $stmt->fetchColumn();
    
    $numero = $count + 1;
    $sufijo = str_pad($numero, 4, '0', STR_PAD_LEFT);
    $codigo_final = $prefijo . '-' . $sufijo;

    $id_tipo_usuario = $prefijo === 'JRD' ? 2 : 1; 

    $dui = null;
    $nit = null;
    if ($prefijo === 'USR') {
        $dui = $data['identificador'] ?? null;
    } else {
        $nit = $data['identificador'] ?? null;
    }

    $stmt = $pdo->prepare("
        INSERT INTO usuarios (
            codigo_usuario, id_tipo_usuario, id_sector, nombres, 
            dui, nit, direccion, fecha_registro
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, CURDATE()
        )
    ");
    
    $stmt->execute([
        $codigo_final,
        $id_tipo_usuario,
        $data['id_sector'] ?? 1,
        trim($data['nombres'] . ' ' . ($data['apellidos'] ?? '')),
        $dui,
        $nit,
        $data['direccion'] ?? ''
    ]);

    echo json_encode(['success' => true, 'codigo_usuario' => $codigo_final]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
