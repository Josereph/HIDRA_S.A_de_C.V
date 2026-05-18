<?php
require_once __DIR__ . '/data_loader.php';


function hidra_render_partial_seguro(string $partialPath, string $viewId, string $tituloModulo): void
{
    $nivelBuffer = ob_get_level();

    try {
        if (!file_exists($partialPath)) {
            throw new RuntimeException('No se encontró el archivo partial: ' . $partialPath);
        }

        // ── CORRECCIÓN CRÍTICA ────────────────────────────────────────────────
        // Los includes dentro de una función PHP tienen su propio scope aislado.
        // Las variables definidas en data_loader.php ($lecturas_recientes,
        // $sectores_lista, $clientes_lista, etc.) son globales pero NO están
        // disponibles automáticamente dentro de esta función.
        // extract($GLOBALS) las inyecta al scope local antes del include,
        // resolviendo el "Undefined variable $lecturas_recientes" en operaciones.php.
        // Se excluyen claves internas de PHP para evitar colisiones.
        $safeGlobals = array_diff_key(
            $GLOBALS,
            array_flip(['GLOBALS', '_SERVER', '_GET', '_POST', '_FILES',
                        '_COOKIE', '_SESSION', '_REQUEST', '_ENV'])
        );
        extract($safeGlobals, EXTR_SKIP);
        // ─────────────────────────────────────────────────────────────────────

        ob_start();
        include $partialPath;
        echo ob_get_clean();

    } catch (Throwable $e) {
        while (ob_get_level() > $nivelBuffer) {
            ob_end_clean();
        }

        $mensajeTecnico = $e->getMessage();
        // Incluir archivo + línea para acelerar el debug
        $mensajeTecnico .= ' — en ' . str_replace(realpath(__DIR__ . '/../../'), '', $e->getFile())
                         . ':' . $e->getLine();
        ?>
        <div class="view" id="view-<?= htmlspecialchars($viewId, ENT_QUOTES, 'UTF-8') ?>">
            <div class="page-header">
                <div>
                    <h1 class="page-title"><?= htmlspecialchars($tituloModulo, ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="page-subtitle">Este módulo no se pudo cargar porque falta una tabla o hay una consulta pendiente de ajustar.</p>
                </div>
            </div>

            <div class="card" style="border:1px solid rgba(255,140,0,.45); background:rgba(255,140,0,.08);">
                <div class="card-header">
                    <span class="card-title" style="color:#ffd166;">
                        <i class="fas fa-exclamation-triangle"></i> Módulo temporalmente detenido
                    </span>
                </div>
                <p style="margin:0 0 10px; color:var(--text-muted);">
                    La pantalla principal sigue funcionando. El problema real está dentro del módulo
                    <strong><?= htmlspecialchars($tituloModulo, ENT_QUOTES, 'UTF-8') ?></strong>.
                </p>
                <div style="font-family:'JetBrains Mono', monospace; font-size:12px; padding:12px; border-radius:10px; background:rgba(0,0,0,.35); color:#ffd166; overflow:auto;">
                    <?= htmlspecialchars($mensajeTecnico, ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HIDRA S.A. de C.V. — Sistema de Facturación</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <!-- ═══ Estilos HIDRA ═══ -->
  <link rel="stylesheet" href="../../assets/css/variables.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="stylesheet" href="../../assets/css/base.css" />
  <link rel="stylesheet" href="../../assets/css/sidebar.css" />
  <link rel="stylesheet" href="../../assets/css/layout.css" />
  <link rel="stylesheet" href="../../assets/css/components.css" />
  <link rel="stylesheet" href="../../assets/css/modals.css" />
  <link rel="stylesheet" href="../../assets/css/utilities.css" />
  <link rel="stylesheet" href="../../assets/css/operaciones.css" />
  <link rel="stylesheet" href="../../assets/css/territorio.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
</head>
<body>

<div class="app-shell">

  <!-- ═══════════════════════════════════════
       SIDEBAR
  ═══════════════════════════════════════ -->
  <aside class="sidebar" id="sidebar">

    <!-- BRAND: fondo blanco, logo completo + icono/trinche -->
    <div class="sidebar-brand">
      <img class="brand-logo"      src="../../assets/img/logos/HIDRA.png"      alt="HIDRA S.A. de C.V." />
      <img class="brand-logo-icon" src="../../assets/img/logos/HIDRA-icon.png" alt="HIDRA" />
    </div>
    <button class="sidebar-toggle" id="sidebarToggle" title="Colapsar menú">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </button>

    <nav class="sidebar-nav">

      <div class="nav-section-title">Principal</div>

      <div class="nav-item active" data-view="dashboard" data-tooltip="Dashboard">
        <span class="nav-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
        </span>
        <span class="nav-label">Dashboard</span>
      </div>

      <div class="nav-item" data-view="clientes" data-tooltip="Clientes">
        <span class="nav-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </span>
        <span class="nav-label">Clientes</span>
      </div>

      <div class="nav-item" data-view="territorio" data-tooltip="Territorio">
        <span class="nav-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
            <line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/>
          </svg>
        </span>
        <span class="nav-label">Territorio</span>
      </div>

      <div class="nav-section-title">Gestión</div>

      <div class="nav-item" data-view="operaciones" data-tooltip="Operaciones">
        <span class="nav-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
          </svg>
        </span>
        <span class="nav-label">Operaciones</span>
        <span class="nav-badge">12</span>
      </div>

      <div class="nav-item" data-view="cobros" data-tooltip="Cobros">
        <span class="nav-icon" style="display:flex;align-items:center;justify-content:center;">
          <i class="fas fa-money-bill-wave" style="font-size: 1rem;"></i>
        </span>
        <span class="nav-label">Cobros</span>
      </div>

      <div class="nav-item" data-view="reportes" data-tooltip="Reportes">
        <span class="nav-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
        </span>
        <span class="nav-label">Reportes</span>
      </div>

      <div class="nav-item" data-view="estadisticas" data-tooltip="Estadísticas">
        <span class="nav-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
            <line x1="12" y1="22.08" x2="12" y2="12"/>
          </svg>
        </span>
        <span class="nav-label">Estadísticas</span>
      </div>

      <div class="nav-item" data-view="config" data-tooltip="Configuración">
        <span class="nav-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
          </svg>
        </span>
        <span class="nav-label">Configuración</span>
      </div>

    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar"><?= strtoupper(substr($_SESSION['operador_nombre'] ?? 'AD', 0, 2)) ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($_SESSION['operador_nombre'] ?? 'Administrador') ?></div>
          <div class="user-role"><?= ucfirst(htmlspecialchars($_SESSION['operador_rol'] ?? 'Sistema HIDRA')) ?></div>
        </div>
      </div>
    </div>

  </aside>

  <!-- ═══════════════════════════════════════
       MAIN AREA
  ═══════════════════════════════════════ -->
  <div class="main-area">

    <header class="top-navbar">
      <div class="navbar-breadcrumb">
        <span class="breadcrumb-item">HIDRA</span>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-item active" id="breadPage">Dashboard</span>
      </div>
      <div class="navbar-actions">
        <div class="navbar-search">
          <i class="fas fa-search" style="color:var(--text-muted);font-size:.9rem;"></i>
          <input type="text" placeholder="Buscar en el sistema…" />
        </div>
        <button class="btn-icon" title="Notificaciones">
          <i class="fas fa-bell"></i>
          <span class="notif-dot"></span>
        </button>
        <button class="btn-icon" title="Ayuda"><i class="fas fa-question-circle"></i></button>
        <button class="btn-icon" title="Cerrar sesión" onclick="window.location.href = '../login.php'"><i class="fas fa-sign-out-alt"></i></button>
      </div>
    </header>

    <main class="page-content">

      <!-- ══════════════════════════════════
           VISTA 1: DASHBOARD
      ══════════════════════════════════ -->
      <div class="view active" id="view-dashboard">

        <div class="page-header">
          <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Resumen general del sistema — Abril 2026</p>
          </div>
          <div class="btn-group">
            <button class="btn btn-ghost btn-sm">Exportar</button>
            <button class="btn btn-primary btn-sm" onclick="openModal()"><i class="fas fa-plus"></i> Nueva Factura</button>
          </div>
        </div>

        <div class="alert alert-warning mb-16">
          <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
          <div class="alert-body">
            <div class="alert-title">Morosos pendientes de atención</div>
            <div class="alert-msg">8 clientes con facturas vencidas hace más de 30 días. Revisar en Operaciones <i class="fas fa-arrow-right" style="font-size:.75em;"></i> Vencidas.</div>
          </div>
        </div>

        <div class="kpi-grid">
          <div class="kpi-card glow">
            <div class="kpi-icon"><i class="fas fa-users"></i></div>
            <div class="kpi-label">Clientes activos</div>
            <div class="kpi-value"><?= number_format($stats['clientes_activos']) ?></div>
            <span class="kpi-delta up">Al día</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-tint"></i></div>
            <div class="kpi-label">Facturas del mes</div>
            <div class="kpi-value"><?= number_format($stats['facturas_mes']) ?></div>
            <span class="kpi-delta up">Al día</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="kpi-label">Facturado (mes)</div>
            <div class="kpi-value">$<?= number_format($stats['facturado_mes'], 2) ?></div>
            <span class="kpi-delta up">Al día</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="kpi-label">Morosos</div>
            <div class="kpi-value"><?= number_format($stats['morosos']) ?></div>
            <span class="kpi-delta down">Pendientes</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon"><i class="fas fa-map"></i></div>
            <div class="kpi-label">Sectores activos</div>
            <div class="kpi-value"><?= number_format($stats['sectores_activos']) ?></div>
            <span class="kpi-delta neutral">100%</span>
          </div>
        </div>

        <div class="grid-3-1 mb-24">
          <div class="card">
            <div class="card-header">
              <span class="card-title">Facturación mensual</span>
              <span class="card-action">Ver detalle <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="section-tabs" data-group="dash-chart">
              <div class="section-tab active" data-panel="chart-bar" data-group="dash-chart">Barras</div>
              <div class="section-tab" data-panel="chart-donut" data-group="dash-chart">Distribución</div>
            </div>
            <div class="tab-panel active" data-panel="chart-bar" data-group="dash-chart">
              <div class="bar-chart">
                <div class="bar-group"><div class="bar" data-pct="55" style="height:4px"></div><div class="bar-label">Nov</div></div>
                <div class="bar-group"><div class="bar" data-pct="70" style="height:4px"></div><div class="bar-label">Dic</div></div>
                <div class="bar-group"><div class="bar" data-pct="62" style="height:4px"></div><div class="bar-label">Ene</div></div>
                <div class="bar-group"><div class="bar" data-pct="80" style="height:4px"></div><div class="bar-label">Feb</div></div>
                <div class="bar-group"><div class="bar" data-pct="74" style="height:4px"></div><div class="bar-label">Mar</div></div>
                <div class="bar-group"><div class="bar accent" data-pct="92" style="height:4px"></div><div class="bar-label">Abr</div></div>
              </div>
            </div>
            <div class="tab-panel" data-panel="chart-donut" data-group="dash-chart">
              <div class="donut-wrap" style="justify-content:center; padding:8px 0;">
                <svg class="donut-svg" width="110" height="110" viewBox="0 0 42 42">
                  <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--celeste-xlt)" stroke-width="4.5"/>
                  <circle class="donut-ring" cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--celeste)" stroke-width="4.5" stroke-linecap="round" data-offset="62" transform="rotate(-90 21 21)"/>
                  <circle class="donut-ring" cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--negro)" stroke-width="4.5" stroke-linecap="round" data-offset="45" transform="rotate(-90 21 21)" style="opacity:.5"/>
                  <text x="21" y="24" text-anchor="middle" fill="var(--negro)" font-size="5.5" font-weight="800">92%</text>
                </svg>
                <div class="donut-legend">
                  <div class="legend-item"><div class="legend-dot" style="background:var(--celeste)"></div>Al día (92%)</div>
                  <div class="legend-item"><div class="legend-dot" style="background:var(--negro)"></div>Pendiente (5%)</div>
                  <div class="legend-item"><div class="legend-dot" style="background:var(--danger)"></div>Moroso (3%)</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <span class="card-title">Alertas</span>
              <span class="card-action">Ver todas</span>
            </div>
            <div style="display:flex; flex-direction:column; gap:10px;">

              <!-- Alerta: vencida — azul profundo (--pending) -->
              <div class="alert mb-0" style="padding:9px 12px; background:rgba(21,101,192,0.10); border-color:var(--pending); color:var(--pending);">
                <span class="alert-icon" style="font-size:.85rem;"><i class="fas fa-circle" style="color:var(--pending)"></i></span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">García López — vencida</div>
                  <div class="alert-msg">45 días sin pago</div>
                </div>
              </div>

              <!-- Alerta: corte pendiente — celeste oscuro (--celeste-dk) -->
              <div class="alert mb-0" style="padding:9px 12px; background:rgba(62,150,240,0.12); border-color:var(--celeste-dk); color:var(--celeste-dk);">
                <span class="alert-icon" style="font-size:.85rem;"><i class="fas fa-circle" style="color:var(--celeste-dk)"></i></span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">Sector B — corte pendiente</div>
                  <div class="alert-msg">Programado para 30/04</div>
                </div>
              </div>

              <!-- Alerta: informativa — celeste muy claro (--celeste-xlt) -->
              <div class="alert alert-info mb-0" style="padding:9px 12px;">
                <span class="alert-icon" style="font-size:.85rem;"><i class="fas fa-tint" style="color:var(--celeste)"></i></span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">Nuevas tarifas 2026</div>
                  <div class="alert-msg">Vigentes desde mayo</div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <span class="card-title">Últimas transacciones</span>
            <span class="card-action" onclick="showView('operaciones')">Ver todas <i class="fas fa-arrow-right"></i></span>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th># Factura</th><th>Cliente</th><th>Sector</th><th>Fecha</th><th>Monto</th><th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($facturas_recientes as $f): ?>
                <tr>
                  <td class="td-mono"><?= htmlspecialchars($f['numero_factura']) ?></td>
                  <td class="td-primary"><?= htmlspecialchars($f['cliente']) ?></td>
                  <td>-</td>
                  <td><?= htmlspecialchars(date('d/m/Y', strtotime($f['fecha_emision']))) ?></td>
                  <td class="td-mono">$<?= number_format($f['total'], 2) ?></td>
                  <td>
                    <span class="badge badge-<?= $f['estado'] === 'pagada' ? 'green' : ($f['estado'] === 'vencida' ? 'red' : 'yellow') ?>">
                      <?= ucfirst(htmlspecialchars($f['estado'])) ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($facturas_recientes)): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);">No hay transacciones recientes</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div><!-- /dashboard -->


      <!-- ══════════════════════════════════
           VISTA 2: CLIENTES
      ══════════════════════════════════ -->
      <div class="view" id="view-clientes">
        <div class="page-header">
          <div>
            <h1 class="page-title">Clientes</h1>
            <p class="page-subtitle">Gestión del padrón de abonados</p>
          </div>
        </div>

        <div class="section-tabs" data-group="clientes-tabs">
          <div class="section-tab active" data-panel="cli-listado" data-group="clientes-tabs"><i class="fas fa-list"></i> Listado</div>
          <div class="section-tab" data-panel="cli-registro" data-group="clientes-tabs"><i class="fas fa-edit"></i> Registro</div>
        </div>

        <div class="tab-panel active" data-panel="cli-listado" data-group="clientes-tabs">
          <div class="flex-between mb-16" style="gap:12px; flex-wrap:wrap;">
            <div class="search-bar">
              <span class="search-icon"><i class="fas fa-search"></i></span>
              <input type="text" id="clienteSearch" placeholder="Buscar nombre, código, sector…" />
            </div>
            <div class="flex-gap">
              <select class="form-control" style="width:auto; padding:8px 12px;">
                <option>Todos los sectores</option>
                <option>Sector A</option><option>Sector B</option><option>Sector C</option><option>Sector D</option>
              </select>
              <select class="form-control" style="width:auto; padding:8px 12px;">
                <option>Todos los estados</option>
                <option>Al día</option><option>Pendiente</option><option>Moroso</option>
              </select>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th class="sortable">Código <span class="sort-icon"><i class="fas fa-sort"></i></span></th>
                  <th class="sortable">Nombre <span class="sort-icon"><i class="fas fa-sort"></i></span></th>
                  <th>Dirección</th><th>Sector</th><th>Teléfono</th><th>Tarifa</th>
                  <th class="sortable">Estado <span class="sort-icon"><i class="fas fa-sort"></i></span></th><th>Acciones</th>
                </tr>
              </thead>
              <tbody id="clienteTabla">
                <?php foreach($clientes_lista as $c): ?>
                <tr>
                  <td class="td-mono"><?= htmlspecialchars($c['codigo_usuario']) ?></td>
                  <td class="td-primary"><?= htmlspecialchars($c['cliente']) ?></td>
                  <td>-</td>
                  <td><?= htmlspecialchars($c['sector']) ?></td>
                  <td class="td-mono">-</td>
                  <td class="td-mono">-</td>
                  <td>
                    <span class="badge badge-<?= $c['estado_usuario'] === 'activo' ? 'green' : 'red' ?>">
                      <?= ucfirst(htmlspecialchars($c['estado_usuario'])) ?>
                    </span>
                  </td>
                  <td>
                    <div class="flex-gap">
                      <button class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i> Editar</button>
                      <button class="btn btn-agua btn-sm"><i class="fas fa-file-alt"></i> Historial</button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($clientes_lista)): ?>
                <tr><td colspan="8" style="text-align:center;color:var(--text-muted);">No hay clientes registrados</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="flex-between mt-16" style="color:var(--text-muted); font-size:.75rem;">
            <span>Mostrando 1–6 de 342 clientes</span>
            <div class="flex-gap">
              <button class="btn btn-ghost btn-sm"><i class="fas fa-chevron-left"></i> Anterior</button>
              <span style="color:var(--negro); font-weight:800; background:var(--celeste-xlt); padding:4px 10px; border-radius:6px;">1</span>
              <button class="btn btn-ghost btn-sm">2</button>
              <button class="btn btn-ghost btn-sm">3</button>
              <button class="btn btn-ghost btn-sm">Siguiente <i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
        </div>

        <div class="tab-panel" data-panel="cli-registro" data-group="clientes-tabs">
          <div class="card" style="max-width:620px;">
            <div class="card-header"><span class="card-title">Nuevo Cliente</span></div>
            <form id="formRegistroCliente" onsubmit="registrarClienteNuevo(event)">
              <div class="form-group">
                <label class="form-label">Código de Usuario</label>
                <div class="input-group" style="display:flex;">
                  <select class="form-control form-select" id="reg_prefijo" style="max-width:120px;border-top-right-radius:0;border-bottom-right-radius:0;border-right:none;">
                    <option value="USR-">USR-</option>
                    <option value="JRD-">JRD-</option>
                  </select>
                  <input type="text" class="form-control" placeholder="Autogenerado al guardar" disabled style="border-top-left-radius:0;border-bottom-left-radius:0;background:var(--bg-card);color:var(--text-muted);" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group"><label class="form-label">Nombres</label><input type="text" id="reg_nombres" class="form-control" placeholder="Nombre(s)" required /></div>
                <div class="form-group"><label class="form-label">Apellidos</label><input type="text" id="reg_apellidos" class="form-control" placeholder="Apellido(s)" required /></div>
              </div>
              <div class="form-row">
                <div class="form-group"><label class="form-label">Identificador (DUI/NIT)</label><input type="text" id="reg_identificador" class="form-control" placeholder="00000000-0" /></div>
                <div class="form-group"><label class="form-label">Teléfono</label><input type="tel" id="reg_telefono" class="form-control" placeholder="0000-0000" /></div>
              </div>
              <div class="form-group"><label class="form-label">Dirección</label><input type="text" id="reg_direccion" class="form-control" placeholder="Calle, colonia, número…" /></div>
              <div class="form-row">
                <div class="form-group"><label class="form-label">Sector</label>
                  <select id="reg_sector" class="form-control form-select">
                    <?php foreach($sectores_lista as $s): ?>
                      <option value="<?= $s['id_sector'] ?>"><?= htmlspecialchars($s['nombre_sector']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group"><label class="form-label">Tarifa mensual</label>
                  <select id="reg_tarifa" class="form-control form-select">
                    <option>$12.50 — Doméstica básica</option>
                    <option>$15.00 — Doméstica plus</option>
                    <option>$25.00 — Comercial</option>
                  </select>
                </div>
              </div>
              <div class="flex-gap mt-16">
                <button type="submit" class="btn btn-primary">Guardar cliente</button>
                <button type="reset" class="btn btn-ghost">Limpiar</button>
              </div>
            </form>
          </div>
        </div>


      </div><!-- /clientes -->


      <!-- ══ VISTA 3: TERRITORIO (partial) ══ -->
      <?php hidra_render_partial_seguro(__DIR__ . '/partials/territorio.php', 'territorio', 'Territorio'); ?>


      <!-- ══ VISTA 4: OPERACIONES (partial) ══ -->
      <?php hidra_render_partial_seguro(__DIR__ . '/partials/operaciones.php', 'operaciones', 'Operaciones'); ?>

      <!-- ══ VISTA 4a: COBROS (partial) ══ -->
      <?php hidra_render_partial_seguro(__DIR__ . '/partials/cobros.php', 'cobros', 'Cobros'); ?>

      <!-- ══ VISTA 4b: ESTADÍSTICAS (partial) ══ -->
      <?php hidra_render_partial_seguro(__DIR__ . '/partials/estadisticas.php', 'estadisticas', 'Estadísticas'); ?>


      <!-- ══ VISTA 5: REPORTES (partial) ══ -->
      <?php hidra_render_partial_seguro(__DIR__ . '/partials/reportes.php', 'reportes', 'Reportes'); ?>


      <!-- ══════════════════════════════════
           VISTA 6: CONFIGURACIÓN
      ══════════════════════════════════ -->
      <div class="view" id="view-config">
        <div class="page-header">
          <div><h1 class="page-title">Configuración</h1><p class="page-subtitle">Parámetros del sistema, tarifas y perfiles de usuario</p></div>
        </div>

        <div class="section-tabs" data-group="cfg-tabs">
          <div class="section-tab active" data-panel="cfg-general"  data-group="cfg-tabs"><i class="fas fa-cog"></i> General</div>
          <div class="section-tab"        data-panel="cfg-tarifas" data-group="cfg-tabs"><i class="fas fa-dollar-sign"></i> Tarifas</div>
          <div class="section-tab"        data-panel="cfg-moras"   data-group="cfg-tabs"><i class="fas fa-exclamation-triangle"></i> Moras</div>
          <div class="section-tab"        data-panel="cfg-usuarios" data-group="cfg-tabs"><i class="fas fa-user"></i> Usuarios y roles</div>
        </div>

        <div class="tab-panel active" data-panel="cfg-general" data-group="cfg-tabs">
          <div class="card" style="max-width:600px;">
            <div class="card-header"><span class="card-title">Datos de la empresa</span></div>
            <div class="form-group"><label class="form-label">Nombre de la empresa</label><input type="text" class="form-control" value="HIDRA S.A. de C.V." /></div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">NIT</label><input type="text" class="form-control" value="0614-010101-000-0" /></div>
              <div class="form-group"><label class="form-label">NRC</label><input type="text" class="form-control" value="123456-7" /></div>
            </div>
            <div class="form-group"><label class="form-label">Dirección fiscal</label><input type="text" class="form-control" value="Calle Principal #1, Ilobasco, Cabañas" /></div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">Teléfono</label><input type="text" class="form-control" value="2362-0000" /></div>
              <div class="form-group"><label class="form-label">Día de vencimiento</label><select class="form-control form-select"><option selected>Día 5 de cada mes</option><option>Día 10</option><option>Día 15</option></select></div>
            </div>
            <button class="btn btn-primary mt-16" onclick="showToast('Configuración guardada correctamente','success')">Guardar cambios</button>
          </div>
        </div>

        <div class="tab-panel" data-panel="cfg-tarifas" data-group="cfg-tabs">
          <div class="grid-2" style="max-width:900px;">
            <div class="card">
              <div class="card-header"><span class="card-title">Tarifas vigentes</span><button class="btn btn-agua btn-sm">+ Nueva tarifa</button></div>
              <div class="table-wrap mb-0">
                <table>
                  <thead><tr><th>Categoría</th><th>Tarifa / mes</th><th>Límite m³</th><th></th></tr></thead>
                  <tbody>
                    <?php foreach($tarifas_lista as $t): ?>
                    <tr>
                      <td class="td-primary"><?= htmlspecialchars($t['nombre_tarifa']) ?></td>
                      <td class="td-mono">$<?= number_format($t['precio_m3'], 4) ?></td>
                      <td>Cargo fijo: $<?= number_format($t['cargo_fijo'], 2) ?></td>
                      <td><button class="btn btn-ghost btn-sm" onclick="showToast('Editando tarifa…','info')"><i class="fas fa-pencil-alt"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Parámetros de mora</span></div>
              <div class="form-group"><label class="form-label">Días antes de aplicar mora</label><input type="number" class="form-control" value="15" /></div>
              <div class="form-group"><label class="form-label">% recargo por mora mensual</label><input type="number" class="form-control" value="5" /></div>
              <div class="form-group"><label class="form-label">Días para iniciar proceso de corte</label><input type="number" class="form-control" value="30" /></div>
              <div class="form-group"><label class="form-label">Cargo por reconexión</label><input type="text" class="form-control" value="$5.00" /></div>
              <button class="btn btn-primary btn-sm mt-16" onclick="showToast('Parámetros de mora actualizados','success')">Guardar parámetros</button>
            </div>
          </div>
        </div>

        <!-- ── TAB: MORAS ──────────────────────────────── -->
        <div class="tab-panel" data-panel="cfg-moras" data-group="cfg-tabs">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:900px;">

            <!-- Formulario de reglas de mora -->
            <div class="card">
              <div class="card-header"><h2 class="card-title">Regla de mora activa</h2></div>

              <div class="alert alert-info mb-16">
                <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="alert-body">
                  <div class="alert-title">Solo administradores</div>
                  <div class="alert-msg">Los cambios afectan el cálculo de todas las facturas futuras.</div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Días de gracia</label>
                <input type="number" class="form-control" id="mora-dias" value="10" min="0" max="30" />
                <span style="font-size:.72rem;color:var(--text-muted);margin-top:4px;display:block;">Días después del vencimiento antes de aplicar mora.</span>
              </div>

              <div class="form-group">
                <label class="form-label">Tipo de recargo</label>
                <select class="form-control form-select" id="mora-tipo" onchange="toggleMoraValor(this.value)">
                  <option value="fijo">Monto fijo ($)</option>
                  <option value="porcentaje">Porcentaje (%)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" id="mora-valor-label">Valor de mora ($)</label>
                <div style="display:flex;align-items:center;gap:8px;">
                  <input type="number" class="form-control" id="mora-valor" value="1.00" step="0.01" min="0" style="flex:1;" />
                  <span id="mora-unidad" style="font-weight:700;color:var(--text-muted);white-space:nowrap;">$ por factura</span>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Aplica a partir de</label>
                <select class="form-control form-select">
                  <option selected>Fecha de vencimiento + días de gracia</option>
                  <option>Fecha de emisión de factura</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Estado de la regla</label>
                <select class="form-control form-select" id="mora-estado">
                  <option value="activo" selected>Activo</option>
                  <option value="inactivo">Inactivo</option>
                </select>
              </div>

              <div class="card-footer">
                <button class="btn btn-ghost" type="button">Cancelar</button>
                <button class="btn btn-primary" type="button"
                  onclick="showToast('Regla de mora guardada correctamente','success')">
                  Guardar configuración
                </button>
              </div>
            </div>

            <!-- Historial de reglas -->
            <div class="card">
              <div class="card-header"><h2 class="card-title">Historial de reglas</h2></div>
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr><th>Fecha</th><th>Tipo</th><th>Valor</th><th>Gracia</th><th>Usuario</th><th>Estado</th></tr>
                  </thead>
                  <tbody>
                    <?php foreach($moras_lista as $m): ?>
                    <tr>
                      <td><?= htmlspecialchars(date('d/m/Y', strtotime($m['fecha_inicio']))) ?></td>
                      <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $m['tipo_mora']))) ?></td>
                      <td class="td-mono"><?= $m['tipo_mora'] === 'monto_fijo' ? '$' . number_format($m['monto_fijo'], 2) : number_format($m['porcentaje'], 2) . '%' ?></td>
                      <td><?= htmlspecialchars($m['dias_gracia']) ?> días</td>
                      <td>-</td>
                      <td>
                        <span class="badge badge-<?= $m['estado'] === 'activa' ? 'green' : 'yellow' ?>">
                          <?= ucfirst(htmlspecialchars($m['estado'])) ?>
                        </span>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div><!-- /cfg-moras -->

        <div class="tab-panel" data-panel="cfg-usuarios" data-group="cfg-tabs">
          <div class="flex-between mb-16">
            <div></div>
            <button class="btn btn-primary btn-sm">+ Nuevo usuario</button>
          </div>
          <div class="table-wrap" style="max-width:800px;">
            <table>
              <thead><tr><th>Usuario</th><th>Correo</th><th>Perfil / Rol</th><th>Permisos</th><th>Estado</th><th>Acciones</th></tr></thead>
              <tbody>
                <?php foreach($operadores_lista as $op): ?>
                <tr>
                  <td>
                    <div class="flex-gap">
                      <div class="user-avatar" style="width:30px;height:30px;font-size:.7rem;background:var(--grad-brand);border:none;">
                        <?= strtoupper(substr($op['nombre_completo'], 0, 2)) ?>
                      </div>
                      <span class="td-primary"><?= htmlspecialchars($op['nombre_completo']) ?></span>
                    </div>
                  </td>
                  <td class="td-mono"><?= htmlspecialchars($op['correo'] ?? '-') ?></td>
                  <td><span class="badge badge-blue"><?= ucfirst(htmlspecialchars($op['rol'])) ?></span></td>
                  <td style="font-size:.72rem; color:var(--text-muted);">-</td>
                  <td>
                    <span class="badge badge-<?= $op['estado'] === 'activo' ? 'green' : 'red' ?>">
                      <?= ucfirst(htmlspecialchars($op['estado'])) ?>
                    </span>
                  </td>
                  <td><button class="btn btn-ghost btn-sm"><i class="fas fa-pencil-alt"></i> Editar</button></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /config -->

    </main>
  </div><!-- /main-area -->
</div><!-- /app-shell -->

<!-- ═══ MODAL ══════════════════════════════════════ -->
<div class="modal-overlay" id="clienteModal" style="display:none;">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fas fa-file-medical"></i> Registrar nueva factura / cliente</span>
      <button class="modal-close" id="modalClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <form id="formCliente">
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nombres</label><input type="text" class="form-control" placeholder="Nombre(s)" required /></div>
          <div class="form-group"><label class="form-label">Apellidos</label><input type="text" class="form-control" placeholder="Apellido(s)" required /></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">DUI</label><input type="text" class="form-control" placeholder="00000000-0" /></div>
          <div class="form-group"><label class="form-label">Teléfono</label><input type="tel" class="form-control" placeholder="0000-0000" /></div>
        </div>
        <div class="form-group"><label class="form-label">Dirección</label><input type="text" class="form-control" placeholder="Calle, colonia, número…" /></div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Sector</label><select class="form-control form-select"><option>A-1</option><option>A-2</option><option>A-3</option><option>B-1</option><option>B-2</option><option>B-3</option></select></div>
          <div class="form-group"><label class="form-label">Tarifa</label><select class="form-control form-select"><option>$12.50 — Doméstica básica</option><option>$15.00 — Doméstica plus</option><option>$25.00 — Comercial</option></select></div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal()">Cancelar</button>
      <button class="btn btn-primary" onclick="closeModal(); showToast('Registro guardado correctamente','success')">Guardar</button>
    </div>
  </div>
</div>

<!-- Toast container -->
<div id="toastContainer"></div>

<!-- ═══ Scripts HIDRA ═══ -->
<script src="../../assets/js/sidebar.js"            defer></script>
<script src="../../assets/js/sidebar-animations.js" defer></script>
<script src="../../assets/js/router.js"             defer></script>
<script src="../../assets/js/dashboard.js"          defer></script>
<script>window.TERR_API = '../../api/territorio_api.php';</script>
<script src="../../assets/js/territorio.js"></script>
<script src="../../assets/js/operaciones.js"        defer></script>
<script src="../../assets/js/cobros.js"             defer></script>
<script src="../../assets/js/ui.js"                 defer></script>
<script>
async function registrarClienteNuevo(e) {
  e.preventDefault();
  const data = {
    prefijo_codigo: document.getElementById('reg_prefijo').value,
    nombres: document.getElementById('reg_nombres').value,
    apellidos: document.getElementById('reg_apellidos').value,
    identificador: document.getElementById('reg_identificador').value,
    direccion: document.getElementById('reg_direccion').value,
    id_sector: document.getElementById('reg_sector').value
  };

  try {
    const res = await fetch('../../api/registrar_cliente.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    });
    const result = await res.json();
    if (result.success) {
      Swal.fire({
        icon: 'success',
        title: 'Cliente guardado',
        text: 'Código asignado: ' + result.codigo_usuario,
        confirmButtonColor: 'var(--celeste)'
      }).then(() => {
        window.location.reload();
      });
    } else {
      Swal.fire({icon: 'error', title: 'Error', text: result.error});
    }
  } catch (err) {
    Swal.fire({icon: 'error', title: 'Error', text: 'Error de conexión'});
  }
}

function toggleMoraValor(tipo) {
  const lbl   = document.getElementById('mora-valor-label');
  const unidad = document.getElementById('mora-unidad');
  const input  = document.getElementById('mora-valor');
  if (!lbl) return;
  if (tipo === 'porcentaje') {
    lbl.textContent    = 'Porcentaje de mora (%)';
    unidad.textContent = '% del saldo pendiente';
    input.value        = '5';
    input.step         = '0.5';
  } else {
    lbl.textContent    = 'Valor de mora ($)';
    unidad.textContent = '$ por factura';
    input.value        = '1.00';
    input.step         = '0.01';
  }
}
</script>

<!-- FIX SEGURO DE NAVEGACIÓN SIDEBAR - -->
<script>
(function () {
  const viewLabels = {
    dashboard: 'Dashboard',
    clientes: 'Clientes',
    territorio: 'Territorio',
    operaciones: 'Operaciones',
    cobros: 'Cobros',
    reportes: 'Reportes',
    estadisticas: 'Estadísticas',
    config: 'Configuración'
  };

  function activarVista(nombreVista) {
    const vistaObjetivo = document.getElementById('view-' + nombreVista);

    if (!vistaObjetivo) {
      console.warn('No existe la vista:', 'view-' + nombreVista);
      return;
    }

    document.querySelectorAll('.view').forEach(function (vista) {
      vista.classList.remove('active');
    });

    vistaObjetivo.classList.add('active');

    document.querySelectorAll('.nav-item[data-view]').forEach(function (item) {
      item.classList.remove('active');

      if (item.getAttribute('data-view') === nombreVista) {
        item.classList.add('active');
      }
    });

    const breadPage = document.getElementById('breadPage');
    if (breadPage) {
      breadPage.textContent = viewLabels[nombreVista] || nombreVista;
    }

    try {
      history.replaceState(null, '', '#' + nombreVista);
    } catch (e) {}

    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }

  window.addEventListener('load', function () {
    window.showView = activarVista;

    document.querySelectorAll('.nav-item[data-view]').forEach(function (item) {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        const nombreVista = item.getAttribute('data-view');
        activarVista(nombreVista);
      }, true);
    });

    const vistaInicial = window.location.hash.replace('#', '');

    if (vistaInicial && document.getElementById('view-' + vistaInicial)) {
      activarVista(vistaInicial);
    }
  });
})();
</script>

</body>
</html>
