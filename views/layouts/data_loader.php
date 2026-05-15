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
$stats['clientes_activos']  = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE estado = 'activo'")->fetchColumn();

$stats['facturas_mes']      = $pdo->query("
    SELECT COUNT(*) FROM facturas
    WHERE MONTH(fecha_emision) = MONTH(CURRENT_DATE)
      AND YEAR(fecha_emision)  = YEAR(CURRENT_DATE)
")->fetchColumn();

$stats['facturado_mes']     = $pdo->query("
    SELECT IFNULL(SUM(total), 0) FROM facturas
    WHERE MONTH(fecha_emision) = MONTH(CURRENT_DATE)
      AND YEAR(fecha_emision)  = YEAR(CURRENT_DATE)
")->fetchColumn();

$stats['morosos']           = $pdo->query("
    SELECT COUNT(DISTINCT id_usuario) FROM facturas WHERE estado = 'pendiente'
    AND fecha_vencimiento < CURRENT_DATE
")->fetchColumn();

$stats['sectores_activos']  = $pdo->query("SELECT COUNT(*) FROM sectores")->fetchColumn();

// Últimas facturas recientes (Dashboard)
$facturas_recientes = $pdo->query("
    SELECT f.*, CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente
    FROM facturas f
    JOIN usuarios u ON f.id_usuario = u.id_usuario
    ORDER BY f.fecha_emision DESC
    LIMIT 5
")->fetchAll();

// Clientes (Listado)
$clientes_lista = $pdo->query("
    SELECT u.*, 
           CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente,
           s.nombre_sector AS sector, 
           m.numero_medidor, m.numero_medidor AS medidor_codigo,
           m.estado AS estado_medidor,
           u.estado AS estado_usuario
    FROM usuarios u
    LEFT JOIN sectores s ON u.id_sector = s.id_sector
    LEFT JOIN medidores m ON u.id_usuario = m.id_usuario
    ORDER BY u.fecha_registro DESC
")->fetchAll();

// Sectores
$sectores_lista = $pdo->query("
    SELECT s.*,
           (SELECT COUNT(*) FROM usuarios u WHERE u.id_sector = s.id_sector) AS total_casas,
           (SELECT COUNT(DISTINCT f.id_usuario)
            FROM facturas f
            JOIN usuarios u2 ON f.id_usuario = u2.id_usuario
            WHERE u2.id_sector = s.id_sector
              AND f.estado = 'pendiente'
              AND f.fecha_vencimiento < CURRENT_DATE) AS en_mora
    FROM sectores s
")->fetchAll();

// Tarifas
$tarifas_lista = $pdo->query("SELECT * FROM tarifas")->fetchAll();

// Moras (historial)
$moras_lista = $pdo->query("SELECT * FROM moras ORDER BY fecha_creacion DESC")->fetchAll();

// Operadores
$operadores_lista = $pdo->query("SELECT * FROM operadores")->fetchAll();

// Lecturas recientes
$lecturas_recientes = $pdo->query("
    SELECT l.*, m.numero_medidor,
           CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente
    FROM lecturas l
    JOIN medidores m ON l.id_medidor = m.id_medidor
    JOIN usuarios u ON m.id_usuario = u.id_usuario
    ORDER BY l.fecha_lectura DESC
    LIMIT 5
")->fetchAll();

// Facturas recientes (Operaciones)
$facturas_operaciones = $pdo->query("
    SELECT f.*, CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente
    FROM facturas f
    JOIN usuarios u ON f.id_usuario = u.id_usuario
    ORDER BY f.id_factura DESC
    LIMIT 5
")->fetchAll();

// Moras (Gestión)
$gestion_moras = $pdo->query("
    SELECT f.*,
           CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente,
           m.numero_medidor,
           DATEDIFF(CURRENT_DATE, f.fecha_vencimiento) AS dias_retraso
    FROM facturas f
    JOIN usuarios u ON f.id_usuario = u.id_usuario
    LEFT JOIN medidores m ON u.id_usuario = m.id_usuario
    WHERE f.estado = 'pendiente'
      AND f.fecha_vencimiento < CURRENT_DATE
    ORDER BY dias_retraso DESC
")->fetchAll();
?>
