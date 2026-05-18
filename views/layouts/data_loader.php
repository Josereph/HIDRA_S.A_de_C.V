<?php
session_start();
if (!isset($_SESSION['operador_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';

/**
 * hidra_query_safe()
 * Ejecuta una consulta PDO y devuelve fetchAll() de forma segura.
 * Si la tabla no existe o hay cualquier error SQL, retorna [] en vez de
 * lanzar un TypeError / fatal que colapsa toda la página.
 */
function hidra_query_safe(PDO $pdo, string $sql, array $params = []): array
{
    try {
        if (empty($params)) {
            $stmt = $pdo->query($sql);
        } else {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (Throwable $e) {
        // Registrar en el error log de PHP sin colapsar la vista
        error_log('[HIDRA data_loader] ' . $e->getMessage());
        return [];
    }
}

/**
 * hidra_scalar_safe()
 * Igual que hidra_query_safe() pero retorna fetchColumn() (escalar).
 */
function hidra_scalar_safe(PDO $pdo, string $sql, array $params = [], $default = 0)
{
    try {
        if (empty($params)) {
            $stmt = $pdo->query($sql);
        } else {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
        $val = $stmt ? $stmt->fetchColumn() : $default;
        return $val !== false ? $val : $default;
    } catch (Throwable $e) {
        error_log('[HIDRA data_loader] ' . $e->getMessage());
        return $default;
    }
}

$pdo = Database::getInstance();

// ── KPIs Dashboard ──────────────────────────────────────────────────────────
$stats = [
    'clientes_activos' => 0,
    'facturas_mes'     => 0,
    'facturado_mes'    => 0.00,
    'morosos'          => 0,
    'sectores_activos' => 0,
];

$stats['clientes_activos'] = hidra_scalar_safe($pdo,
    "SELECT COUNT(*) FROM usuarios WHERE estado = 'activo'"
);

$stats['facturas_mes'] = hidra_scalar_safe($pdo,
    "SELECT COUNT(*) FROM facturas
     WHERE MONTH(fecha_emision) = MONTH(CURRENT_DATE)
       AND YEAR(fecha_emision)  = YEAR(CURRENT_DATE)"
);

$stats['facturado_mes'] = hidra_scalar_safe($pdo,
    "SELECT IFNULL(SUM(total), 0) FROM facturas
     WHERE MONTH(fecha_emision) = MONTH(CURRENT_DATE)
       AND YEAR(fecha_emision)  = YEAR(CURRENT_DATE)",
    [], 0.00
);

$stats['morosos'] = hidra_scalar_safe($pdo,
    "SELECT COUNT(DISTINCT id_usuario) FROM facturas
     WHERE estado = 'pendiente' AND fecha_vencimiento < CURRENT_DATE"
);

$stats['sectores_activos'] = hidra_scalar_safe($pdo,
    "SELECT COUNT(*) FROM sectores"
);

// ── Últimas facturas (Dashboard) ─────────────────────────────────────────────
$facturas_recientes = hidra_query_safe($pdo,
    "SELECT f.*, CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente
     FROM facturas f
     JOIN usuarios u ON f.id_usuario = u.id_usuario
     ORDER BY f.fecha_emision DESC
     LIMIT 5"
);

// ── Clientes (Listado + selects) ─────────────────────────────────────────────
$clientes_lista = hidra_query_safe($pdo,
    "SELECT u.*,
            CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente,
            s.nombre_sector AS sector,
            m.numero_medidor, m.numero_medidor AS medidor_codigo,
            m.estado AS estado_medidor,
            u.estado AS estado_usuario
     FROM usuarios u
     LEFT JOIN sectores s ON u.id_sector = s.id_sector
     LEFT JOIN medidores m ON u.id_usuario = m.id_usuario
     ORDER BY u.fecha_registro DESC"
);

// ── Sectores ─────────────────────────────────────────────────────────────────
$sectores_lista = hidra_query_safe($pdo,
    "SELECT s.*,
            (SELECT COUNT(*) FROM usuarios u WHERE u.id_sector = s.id_sector) AS total_casas,
            (SELECT COUNT(DISTINCT f.id_usuario)
             FROM facturas f
             JOIN usuarios u2 ON f.id_usuario = u2.id_usuario
             WHERE u2.id_sector = s.id_sector
               AND f.estado = 'pendiente'
               AND f.fecha_vencimiento < CURRENT_DATE) AS en_mora
     FROM sectores s"
);

// ── Tarifas ───────────────────────────────────────────────────────────────────
$tarifas_lista = hidra_query_safe($pdo, "SELECT * FROM tarifas");

// ── Moras historial ───────────────────────────────────────────────────────────
$moras_lista = hidra_query_safe($pdo, "SELECT * FROM moras ORDER BY fecha_creacion DESC");

// ── Operadores ────────────────────────────────────────────────────────────────
$operadores_lista = hidra_query_safe($pdo, "SELECT * FROM operadores");

// ── Lecturas recientes (Operaciones → tab Lecturas) ───────────────────────────
// NOTA: Esta variable es la que causaba el fatal en operaciones.php línea 81.
// Si la tabla 'lecturas' aún no existe, se devuelve [] y el foreach simplemente
// no itera, sin colapsar el módulo.
$lecturas_recientes = hidra_query_safe($pdo,
    "SELECT l.*, m.numero_medidor,
            CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente
     FROM lecturas l
     JOIN medidores m ON l.id_medidor = m.id_medidor
     JOIN usuarios u ON m.id_usuario = u.id_usuario
     ORDER BY l.fecha_lectura DESC
     LIMIT 5"
);

// ── Facturas recientes (Operaciones → tab Facturas) ───────────────────────────
$facturas_operaciones = hidra_query_safe($pdo,
    "SELECT f.*, CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente
     FROM facturas f
     JOIN usuarios u ON f.id_usuario = u.id_usuario
     ORDER BY f.fecha_emision DESC
     LIMIT 5"
);

// ── Moras gestión ─────────────────────────────────────────────────────────────
$gestion_moras = hidra_query_safe($pdo,
    "SELECT f.*,
            CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente,
            m.numero_medidor,
            DATEDIFF(CURRENT_DATE, f.fecha_vencimiento) AS dias_retraso
     FROM facturas f
     JOIN usuarios u ON f.id_usuario = u.id_usuario
     LEFT JOIN medidores m ON u.id_usuario = m.id_usuario
     WHERE f.estado = 'pendiente'
       AND f.fecha_vencimiento < CURRENT_DATE
     ORDER BY dias_retraso DESC"
);
?>
