<?php
session_start();
if (!isset($_SESSION['operador_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
$pdo = Database::getInstance();

// KPIs Dashboard
$stats = [];
$stats['clientes_activos'] = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE estado = 'activo'")->fetchColumn();
$stats['facturas_mes'] = $pdo->query("SELECT COUNT(*) FROM facturas WHERE mes = MONTH(CURRENT_DATE) AND anio = YEAR(CURRENT_DATE)")->fetchColumn();
$stats['facturado_mes'] = $pdo->query("SELECT IFNULL(SUM(total), 0) FROM facturas WHERE mes = MONTH(CURRENT_DATE) AND anio = YEAR(CURRENT_DATE)")->fetchColumn();
$stats['morosos'] = $pdo->query("SELECT COUNT(DISTINCT id_usuario) FROM facturas WHERE estado = 'vencida'")->fetchColumn();
$stats['sectores_activos'] = $pdo->query("SELECT COUNT(*) FROM sectores WHERE estado = 'activo'")->fetchColumn();

// Últimas transacciones (Dashboard)
$facturas_recientes = $pdo->query("SELECT * FROM vista_facturas_pendientes ORDER BY fecha_emision DESC LIMIT 5")->fetchAll();

// Clientes (Listado)
$clientes_lista = $pdo->query("SELECT * FROM vista_clientes_medidores")->fetchAll();

// Sectores
$sectores_lista = $pdo->query("
    SELECT s.*, 
           (SELECT COUNT(*) FROM usuarios u WHERE u.id_sector = s.id_sector) as total_casas,
           (SELECT COUNT(DISTINCT f.id_usuario) FROM facturas f JOIN usuarios u2 ON f.id_usuario = u2.id_usuario WHERE u2.id_sector = s.id_sector AND f.estado = 'vencida') as en_mora
    FROM sectores s
")->fetchAll();

// Tarifas
$tarifas_lista = $pdo->query("SELECT t.*, tu.nombre_tipo FROM tarifas t JOIN tipos_usuario tu ON t.id_tipo_usuario = tu.id_tipo_usuario")->fetchAll();

// Reglas de Mora (Historial)
$moras_lista = $pdo->query("SELECT * FROM moras ORDER BY fecha_inicio DESC")->fetchAll();

// Operadores (Usuarios)
$operadores_lista = $pdo->query("SELECT * FROM operadores")->fetchAll();

// Lecturas Recientes
$lecturas_recientes = $pdo->query("
    SELECT l.*, m.numero_medidor, u.codigo_usuario, CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) as cliente 
    FROM lecturas l 
    JOIN medidores m ON l.id_medidor = m.id_medidor
    JOIN usuarios u ON m.id_usuario = u.id_usuario
    ORDER BY l.fecha_lectura DESC LIMIT 5
")->fetchAll();

// Facturas Recientes (Operaciones)
$facturas_operaciones = $pdo->query("SELECT * FROM vista_facturas_pendientes ORDER BY id_factura DESC LIMIT 5")->fetchAll();

// Moras (Gestión)
$gestion_moras = $pdo->query("
    SELECT f.*, u.codigo_usuario, CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) as cliente, m.numero_medidor, DATEDIFF(CURRENT_DATE, f.fecha_vencimiento) as dias_retraso
    FROM facturas f
    JOIN usuarios u ON f.id_usuario = u.id_usuario
    LEFT JOIN medidores m ON u.id_usuario = m.id_usuario
    WHERE f.estado = 'vencida'
    ORDER BY dias_retraso DESC
")->fetchAll();
?>
