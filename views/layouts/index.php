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
  <link rel="stylesheet" href="../../assets/css/base.css" />
  <link rel="stylesheet" href="../../assets/css/sidebar.css" />
  <link rel="stylesheet" href="../../assets/css/layout.css" />
  <link rel="stylesheet" href="../../assets/css/components.css" />
  <link rel="stylesheet" href="../../assets/css/modals.css" />
  <link rel="stylesheet" href="../../assets/css/utilities.css" />
  <link rel="stylesheet" href="../../assets/css/operaciones.css" />
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
        <div class="user-avatar">AD</div>
        <div class="user-info">
          <div class="user-name">Administrador</div>
          <div class="user-role">Sistema HIDRA</div>
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
          <span style="color:var(--text-muted);font-size:.9rem;">🔍</span>
          <input type="text" placeholder="Buscar en el sistema…" />
        </div>
        <button class="btn-icon" title="Notificaciones">
          🔔
          <span class="notif-dot"></span>
        </button>
        <button class="btn-icon" title="Ayuda">❓</button>
        <button class="btn-icon" title="Cerrar sesión" onclick="showToast('Sesión cerrada','info')">⏻</button>
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
            <button class="btn btn-ghost btn-sm">↓ Exportar</button>
            <button class="btn btn-primary btn-sm" onclick="openModal()">+ Nueva Factura</button>
          </div>
        </div>

        <div class="alert alert-warning mb-16">
          <span class="alert-icon">⚠</span>
          <div class="alert-body">
            <div class="alert-title">Morosos pendientes de atención</div>
            <div class="alert-msg">8 clientes con facturas vencidas hace más de 30 días. Revisar en Operaciones → Vencidas.</div>
          </div>
        </div>

        <div class="kpi-grid">
          <div class="kpi-card glow">
            <div class="kpi-icon blue">👥</div>
            <div class="kpi-label">Clientes activos</div>
            <div class="kpi-value">342</div>
            <span class="kpi-delta up">↑ 4.2%</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon cyan">💧</div>
            <div class="kpi-label">Facturas del mes</div>
            <div class="kpi-value">318</div>
            <span class="kpi-delta up">↑ 1.8%</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon green">💲</div>
            <div class="kpi-label">Facturado (mes)</div>
            <div class="kpi-value">$48,210</div>
            <span class="kpi-delta up">↑ 6.5%</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon red">⚠</div>
            <div class="kpi-label">Morosos</div>
            <div class="kpi-value">27</div>
            <span class="kpi-delta down">↑ 3 nuevos</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon yellow">🗺</div>
            <div class="kpi-label">Sectores activos</div>
            <div class="kpi-value">12</div>
            <span class="kpi-delta neutral">100%</span>
          </div>
        </div>

        <div class="grid-3-1 mb-24">
          <div class="card">
            <div class="card-header">
              <span class="card-title">Facturación mensual</span>
              <span class="card-action">Ver detalle →</span>
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
                  <div class="legend-item"><div class="legend-dot" style="background:#C62828"></div>Moroso (3%)</div>
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
              <div class="alert alert-danger mb-0" style="padding:9px 12px;">
                <span class="alert-icon" style="font-size:.85rem;">🔴</span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">García López — vencida</div>
                  <div class="alert-msg">45 días sin pago</div>
                </div>
              </div>
              <div class="alert alert-warning mb-0" style="padding:9px 12px;">
                <span class="alert-icon" style="font-size:.85rem;">🟡</span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">Sector B — corte pendiente</div>
                  <div class="alert-msg">Programado para 30/04</div>
                </div>
              </div>
              <div class="alert alert-info mb-0" style="padding:9px 12px;">
                <span class="alert-icon" style="font-size:.85rem;">💧</span>
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
            <span class="card-action" onclick="showView('operaciones')">Ver todas →</span>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th># Factura</th><th>Cliente</th><th>Sector</th><th>Fecha</th><th>Monto</th><th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr><td class="td-mono">#2026-0431</td><td class="td-primary">Ana Martínez</td><td>A-3</td><td>28/04/2026</td><td class="td-mono">$12.50</td><td><span class="badge badge-green">Pagado</span></td></tr>
                <tr><td class="td-mono">#2026-0430</td><td class="td-primary">Carlos Rivas</td><td>B-1</td><td>27/04/2026</td><td class="td-mono">$12.50</td><td><span class="badge badge-yellow">Pendiente</span></td></tr>
                <tr><td class="td-mono">#2026-0429</td><td class="td-primary">María López</td><td>C-2</td><td>26/04/2026</td><td class="td-mono">$15.00</td><td><span class="badge badge-green">Pagado</span></td></tr>
                <tr><td class="td-mono">#2026-0428</td><td class="td-primary">José Hernández</td><td>A-1</td><td>25/04/2026</td><td class="td-mono">$12.50</td><td><span class="badge badge-red">Vencido</span></td></tr>
                <tr><td class="td-mono">#2026-0427</td><td class="td-primary">Luisa Torres</td><td>D-4</td><td>24/04/2026</td><td class="td-mono">$12.50</td><td><span class="badge badge-green">Pagado</span></td></tr>
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
          <div class="btn-group">
            <button class="btn btn-ghost btn-sm">↓ Exportar CSV</button>
            <button class="btn btn-primary btn-sm" id="btnNuevoCliente">+ Nuevo cliente</button>
          </div>
        </div>

        <div class="section-tabs" data-group="clientes-tabs">
          <div class="section-tab active" data-panel="cli-listado" data-group="clientes-tabs">📋 Listado</div>
          <div class="section-tab" data-panel="cli-registro" data-group="clientes-tabs">✏ Registro</div>
          <div class="section-tab" data-panel="cli-historial" data-group="clientes-tabs">📅 Historial</div>
        </div>

        <div class="tab-panel active" data-panel="cli-listado" data-group="clientes-tabs">
          <div class="flex-between mb-16" style="gap:12px; flex-wrap:wrap;">
            <div class="search-bar">
              <span class="search-icon">🔍</span>
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
                  <th class="sortable">Código <span class="sort-icon">↕</span></th>
                  <th class="sortable">Nombre <span class="sort-icon">↕</span></th>
                  <th>Dirección</th><th>Sector</th><th>Teléfono</th><th>Tarifa</th>
                  <th class="sortable">Estado <span class="sort-icon">↕</span></th><th>Acciones</th>
                </tr>
              </thead>
              <tbody id="clienteTabla">
                <tr><td class="td-mono">CLT-001</td><td class="td-primary">Ana Isabel Martínez</td><td>Col. San José #14</td><td>A-3</td><td class="td-mono">7234-5678</td><td class="td-mono">$12.50</td><td><span class="badge badge-green">Al día</span></td><td><div class="flex-gap"><button class="btn btn-ghost btn-sm">✏ Editar</button><button class="btn btn-agua btn-sm">📄 Historial</button></div></td></tr>
                <tr><td class="td-mono">CLT-002</td><td class="td-primary">Carlos Alberto Rivas</td><td>Barrio El Centro #7</td><td>B-1</td><td class="td-mono">7890-1234</td><td class="td-mono">$12.50</td><td><span class="badge badge-yellow">Pendiente</span></td><td><div class="flex-gap"><button class="btn btn-ghost btn-sm">✏ Editar</button><button class="btn btn-agua btn-sm">📄 Historial</button></div></td></tr>
                <tr><td class="td-mono">CLT-003</td><td class="td-primary">María de Jesús López</td><td>Res. Agua Viva #22</td><td>C-2</td><td class="td-mono">7654-3210</td><td class="td-mono">$15.00</td><td><span class="badge badge-green">Al día</span></td><td><div class="flex-gap"><button class="btn btn-ghost btn-sm">✏ Editar</button><button class="btn btn-agua btn-sm">📄 Historial</button></div></td></tr>
                <tr><td class="td-mono">CLT-004</td><td class="td-primary">José Antonio Hernández</td><td>Col. Las Flores #3B</td><td>A-1</td><td class="td-mono">7321-6540</td><td class="td-mono">$12.50</td><td><span class="badge badge-red">Moroso</span></td><td><div class="flex-gap"><button class="btn btn-ghost btn-sm">✏ Editar</button><button class="btn btn-danger btn-sm">✂ Corte</button></div></td></tr>
                <tr><td class="td-mono">CLT-005</td><td class="td-primary">Luisa Esperanza Torres</td><td>Calle Principal #88</td><td>D-4</td><td class="td-mono">7111-2233</td><td class="td-mono">$12.50</td><td><span class="badge badge-green">Al día</span></td><td><div class="flex-gap"><button class="btn btn-ghost btn-sm">✏ Editar</button><button class="btn btn-agua btn-sm">📄 Historial</button></div></td></tr>
                <tr><td class="td-mono">CLT-006</td><td class="td-primary">Roberto Ernesto Díaz</td><td>Col. Modelo #15</td><td>B-3</td><td class="td-mono">7990-4455</td><td class="td-mono">$12.50</td><td><span class="badge badge-red">Moroso</span></td><td><div class="flex-gap"><button class="btn btn-ghost btn-sm">✏ Editar</button><button class="btn btn-danger btn-sm">✂ Corte</button></div></td></tr>
              </tbody>
            </table>
          </div>
          <div class="flex-between mt-16" style="color:var(--text-muted); font-size:.75rem;">
            <span>Mostrando 1–6 de 342 clientes</span>
            <div class="flex-gap">
              <button class="btn btn-ghost btn-sm">← Anterior</button>
              <span style="color:var(--negro); font-weight:800; background:var(--celeste-xlt); padding:4px 10px; border-radius:6px;">1</span>
              <button class="btn btn-ghost btn-sm">2</button>
              <button class="btn btn-ghost btn-sm">3</button>
              <button class="btn btn-ghost btn-sm">Siguiente →</button>
            </div>
          </div>
        </div>

        <div class="tab-panel" data-panel="cli-registro" data-group="clientes-tabs">
          <div class="card" style="max-width:620px;">
            <div class="card-header"><span class="card-title">Nuevo Cliente</span></div>
            <form onsubmit="event.preventDefault(); showToast('Cliente guardado correctamente','success');">
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
                <div class="form-group"><label class="form-label">Sector</label><select class="form-control form-select"><option>A-1</option><option>A-2</option><option>A-3</option><option>B-1</option><option>B-2</option><option>B-3</option><option>C-1</option><option>C-2</option></select></div>
                <div class="form-group"><label class="form-label">Tarifa mensual</label><select class="form-control form-select"><option>$12.50 — Doméstica básica</option><option>$15.00 — Doméstica plus</option><option>$25.00 — Comercial</option></select></div>
              </div>
              <div class="flex-gap mt-16">
                <button type="submit" class="btn btn-primary">💾 Guardar cliente</button>
                <button type="reset" class="btn btn-ghost">Limpiar</button>
              </div>
            </form>
          </div>
        </div>

        <div class="tab-panel" data-panel="cli-historial" data-group="clientes-tabs">
          <div class="grid-2-1">
            <div class="card">
              <div class="card-header">
                <span class="card-title">Historial de pagos</span>
                <div class="search-bar"><span class="search-icon">🔍</span><input type="text" placeholder="Buscar cliente…" style="min-width:140px;" /></div>
              </div>
              <div class="timeline">
                <div class="timeline-item"><div class="timeline-dot paid">✓</div><div class="timeline-body"><div class="timeline-title">Ana Martínez — Abril 2026</div><div class="timeline-meta">Pagado el 28/04/2026 · Ref: #2026-0431</div><div class="timeline-amount">$12.50</div></div></div>
                <div class="timeline-item"><div class="timeline-dot paid">✓</div><div class="timeline-body"><div class="timeline-title">Ana Martínez — Marzo 2026</div><div class="timeline-meta">Pagado el 05/03/2026 · Ref: #2026-0312</div><div class="timeline-amount">$12.50</div></div></div>
                <div class="timeline-item"><div class="timeline-dot overdue">✕</div><div class="timeline-body"><div class="timeline-title">Ana Martínez — Febrero 2026</div><div class="timeline-meta">Vencido — sin pago registrado</div><div class="timeline-amount" style="color:var(--danger)">$12.50</div></div></div>
                <div class="timeline-item"><div class="timeline-dot paid">✓</div><div class="timeline-body"><div class="timeline-title">Ana Martínez — Enero 2026</div><div class="timeline-meta">Pagado el 10/01/2026 · Ref: #2026-0101</div><div class="timeline-amount">$12.50</div></div></div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Perfil del cliente</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Código</span><span class="td-mono">CLT-001</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Nombre</span><span style="font-weight:700; font-size:.84rem;">Ana Martínez</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Sector</span><span class="badge badge-blue">A-3</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Estado</span><span class="badge badge-green">Al día</span></div>
              <div class="stat-row" style="margin-bottom:16px;"><span class="text-muted" style="font-size:.75rem">Tarifa</span><span class="td-mono">$12.50/mes</span></div>
              <div style="font-size:.7rem; color:var(--text-muted); margin-bottom:6px;">Cumplimiento de pago</div>
              <div class="progress-bar-wrap mb-8"><div class="progress-bar-fill" style="width:83%"></div></div>
              <div style="font-size:.7rem; color:var(--text-muted);">10 de 12 meses pagados a tiempo (83%)</div>
              <div class="btn-group mt-16"><button class="btn btn-primary btn-sm w-full" onclick="showToast('Abriendo registro de pago…','info')">+ Registrar pago</button></div>
            </div>
          </div>
        </div>
      </div><!-- /clientes -->


      <!-- ══════════════════════════════════
           VISTA 3: TERRITORIO
      ══════════════════════════════════ -->
      <div class="view" id="view-territorio">
        <div class="page-header">
          <div><h1 class="page-title">Territorio</h1><p class="page-subtitle">Mapa de sectores y estado de casas</p></div>
          <div class="btn-group">
            <button class="btn btn-ghost btn-sm">+ Nuevo sector</button>
            <button class="btn btn-secondary btn-sm">⚙ Configurar zonas</button>
          </div>
        </div>

        <div class="section-tabs" data-group="terr-tabs">
          <div class="section-tab active" data-panel="terr-mapa" data-group="terr-tabs">🗺 Mapa de sectores</div>
          <div class="section-tab" data-panel="terr-estado" data-group="terr-tabs">🏠 Estado de casas</div>
        </div>

        <div class="tab-panel active" data-panel="terr-mapa" data-group="terr-tabs">
          <div class="grid-2-1">
            <div class="sector-map">
              <div class="card-header" style="padding:14px 18px; border-bottom: 1.5px solid var(--border-subtle); background:var(--blanco);">
                <span class="card-title">Vista general de sectores</span>
                <div class="flex-gap">
                  <span style="display:flex;align-items:center;gap:4px;font-size:.68rem;color:var(--celeste);font-weight:700;">■ Activo</span>
                  <span style="display:flex;align-items:center;gap:4px;font-size:.68rem;color:var(--warning);font-weight:700;">■ Alerta</span>
                  <span style="display:flex;align-items:center;gap:4px;font-size:.68rem;color:var(--text-muted);font-weight:700;">■ Inactivo</span>
                </div>
              </div>
              <div class="sector-map-inner" id="sectorMapInner">
                <div class="map-grid"></div>
                <div class="sector-block active" data-sector="A-1" style="left:8%;top:12%;width:18%;height:22%"><div class="sector-count">28</div><span>A-1</span></div>
                <div class="sector-block active" data-sector="A-2" style="left:28%;top:12%;width:18%;height:22%"><div class="sector-count">31</div><span>A-2</span></div>
                <div class="sector-block active" data-sector="A-3" style="left:48%;top:12%;width:18%;height:22%"><div class="sector-count">24</div><span>A-3</span></div>
                <div class="sector-block inactive" data-sector="A-4" style="left:68%;top:12%;width:18%;height:22%"><div class="sector-count">0</div><span>A-4</span></div>
                <div class="sector-block active" data-sector="B-1" style="left:8%;top:40%;width:18%;height:22%"><div class="sector-count">35</div><span>B-1</span></div>
                <div class="sector-block warning" data-sector="B-2" style="left:28%;top:40%;width:18%;height:22%"><div class="sector-count">29</div><span>B-2</span></div>
                <div class="sector-block active" data-sector="B-3" style="left:48%;top:40%;width:18%;height:22%"><div class="sector-count">33</div><span>B-3</span></div>
                <div class="sector-block active" data-sector="C-1" style="left:8%;top:68%;width:28%;height:22%"><div class="sector-count">42</div><span>C-1</span></div>
                <div class="sector-block warning" data-sector="C-2" style="left:38%;top:68%;width:28%;height:22%"><div class="sector-count">38</div><span>C-2</span></div>
                <div class="sector-block inactive" data-sector="D-1" style="left:68%;top:68%;width:18%;height:22%"><div class="sector-count">0</div><span>D-1</span></div>
              </div>
            </div>
            <div class="card" id="sectorInfo">
              <div style="text-align:center; padding:40px 0; color:var(--text-muted);">
                <div style="font-size:2.5rem; margin-bottom:12px;">🗺</div>
                <div style="font-size:.82rem; font-weight:600;">Haz clic en un sector<br>para ver su información</div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-panel" data-panel="terr-estado" data-group="terr-tabs">
          <div class="flex-between mb-16" style="gap:12px; flex-wrap:wrap;">
            <div class="search-bar"><span class="search-icon">🔍</span><input type="text" placeholder="Buscar por dirección, sector…" /></div>
            <select class="form-control" style="width:auto; padding:8px 12px;"><option>Todos los sectores</option><option>Sector A</option><option>Sector B</option><option>Sector C</option></select>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Casa / Predio</th><th>Dirección</th><th>Sector</th><th>Cliente</th><th>Servicio</th><th>Pago</th><th>Acciones</th></tr></thead>
              <tbody>
                <tr><td class="td-mono">P-0014</td><td>Col. San José #14</td><td>A-3</td><td class="td-primary">Ana Martínez</td><td><span class="badge badge-green">Activo</span></td><td><span class="badge badge-green">Al día</span></td><td><button class="btn btn-ghost btn-sm">Ver detalle</button></td></tr>
                <tr><td class="td-mono">P-0007</td><td>Barrio El Centro #7</td><td>B-1</td><td class="td-primary">Carlos Rivas</td><td><span class="badge badge-green">Activo</span></td><td><span class="badge badge-yellow">Pendiente</span></td><td><button class="btn btn-ghost btn-sm">Ver detalle</button></td></tr>
                <tr><td class="td-mono">P-0022</td><td>Res. Agua Viva #22</td><td>C-2</td><td class="td-primary">María López</td><td><span class="badge badge-yellow">Restringido</span></td><td><span class="badge badge-red">Moroso</span></td><td><button class="btn btn-ghost btn-sm">Ver detalle</button></td></tr>
                <tr><td class="td-mono">P-0003</td><td>Col. Las Flores #3B</td><td>A-1</td><td class="td-primary">José Hernández</td><td><span class="badge badge-red">Cortado</span></td><td><span class="badge badge-red">Moroso</span></td><td><button class="btn btn-ghost btn-sm">Ver detalle</button></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /territorio -->


      <!-- ══════════════════════════════════
           VISTA 4: OPERACIONES
      ══════════════════════════════════ -->
      <div class="view" id="view-operaciones">
        <div class="page-header">
          <div><h1 class="page-title">Operaciones</h1><p class="page-subtitle">Facturación, pagos y captura de lecturas</p></div>
        </div>

        <div class="ops-hero">
          <div style="position:relative;z-index:1;">
            <div class="ops-hero-title">💧 Panel de Operaciones</div>
            <div class="ops-hero-sub">Gestiona facturas, registra pagos y captura lecturas de medidores</div>
          </div>
          <div class="ops-hero-actions">
            <button class="btn-hero btn-hero-primary" onclick="openModal()">📄 Generar Factura</button>
            <button class="btn-hero btn-hero-outline" onclick="showToast('Exportando datos…','info')">↓ Exportar</button>
          </div>
        </div>

        <div class="section-tabs" data-group="ops-tabs">
          <div class="section-tab active" data-panel="ops-facturas" data-group="ops-tabs">📄 Facturas <span class="nav-badge" style="display:inline-flex;margin-left:4px;background:var(--celeste);">12</span></div>
          <div class="section-tab" data-panel="ops-pagos" data-group="ops-tabs">💳 Registro de Pagos</div>
          <div class="section-tab" data-panel="ops-lecturas" data-group="ops-tabs">📊 Captura Lecturas</div>
          <div class="section-tab" data-panel="ops-vencidas" data-group="ops-tabs">⚠ Vencidas</div>
        </div>

        <div class="tab-panel active" data-panel="ops-facturas" data-group="ops-tabs">
          <div class="flex-between mb-16" style="flex-wrap:wrap;gap:12px;">
            <div class="search-bar"><span class="search-icon">🔍</span><input type="text" placeholder="Buscar factura o cliente…" /></div>
            <div class="flex-gap">
              <select class="form-control" style="width:auto;padding:8px 12px;"><option>Todos los meses</option><option selected>Abril 2026</option></select>
              <select class="form-control" style="width:auto;padding:8px 12px;"><option>Todos los estados</option><option>Pendiente</option><option>Pagado</option><option>Vencido</option></select>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th># Factura</th><th>Cliente</th><th>Mes</th><th>Tarifa</th><th>Vencimiento</th><th>Estado</th><th>Acción</th></tr></thead>
              <tbody>
                <tr><td class="td-mono">#2026-0432</td><td class="td-primary">Pedro Aguilar</td><td>Abril 2026</td><td class="td-mono">$12.50</td><td>05/05/2026</td><td><span class="badge badge-yellow">Pendiente</span></td><td><button class="btn btn-agua btn-sm" onclick="showToast('Pago registrado','success')">✓ Pagar factura</button></td></tr>
                <tr><td class="td-mono">#2026-0433</td><td class="td-primary">Laura Vásquez</td><td>Abril 2026</td><td class="td-mono">$15.00</td><td>05/05/2026</td><td><span class="badge badge-yellow">Pendiente</span></td><td><button class="btn btn-agua btn-sm" onclick="showToast('Pago registrado','success')">✓ Pagar factura</button></td></tr>
                <tr><td class="td-mono">#2026-0431</td><td class="td-primary">Ana Martínez</td><td>Abril 2026</td><td class="td-mono">$12.50</td><td>05/05/2026</td><td><span class="badge badge-green">Pagado</span></td><td><button class="btn btn-ghost btn-sm">📄 Ver</button></td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="tab-panel" data-panel="ops-pagos" data-group="ops-tabs">
          <div class="grid-2">
            <div class="card">
              <div class="card-header"><span class="card-title">Registrar Pago Manual</span></div>
              <form onsubmit="event.preventDefault(); showToast('Pago registrado exitosamente','success');">
                <div class="form-group">
                  <label class="form-label">Buscar cliente / factura</label>
                  <div class="search-bar" style="border-radius:var(--radius-sm);">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Nombre, código CLT o # factura…" style="min-width:0; flex:1;" />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label"># Factura</label><input type="text" class="form-control" value="#2026-0432" readonly /></div>
                  <div class="form-group"><label class="form-label">Monto a pagar</label><input type="text" class="form-control" value="$12.50" /></div>
                </div>
                <div class="form-row">
                  <div class="form-group"><label class="form-label">Forma de pago</label><select class="form-control form-select"><option>Efectivo</option><option>Transferencia</option><option>Cheque</option></select></div>
                  <div class="form-group"><label class="form-label">Fecha de pago</label><input type="date" class="form-control" value="2026-04-28" /></div>
                </div>
                <div class="form-group"><label class="form-label">Observaciones</label><textarea class="form-control" rows="2" placeholder="Nota opcional…" style="resize:vertical;"></textarea></div>
                <div class="flex-gap mt-16">
                  <button type="submit" class="btn btn-primary">💾 Registrar pago</button>
                  <button type="reset" class="btn btn-ghost">Limpiar</button>
                </div>
              </form>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Últimos pagos registrados</span></div>
              <div class="timeline">
                <div class="timeline-item"><div class="timeline-dot paid">💳</div><div class="timeline-body"><div class="timeline-title">Ana Martínez — #2026-0431</div><div class="timeline-meta">Efectivo · 28/04/2026</div><div class="timeline-amount">$12.50</div></div></div>
                <div class="timeline-item"><div class="timeline-dot paid">💳</div><div class="timeline-body"><div class="timeline-title">María López — #2026-0429</div><div class="timeline-meta">Transferencia · 26/04/2026</div><div class="timeline-amount">$15.00</div></div></div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-panel" data-panel="ops-lecturas" data-group="ops-tabs">
          <div class="alert alert-info mb-24">
            <span class="alert-icon">📊</span>
            <div class="alert-body">
              <div class="alert-title">Captura mensual de lecturas — Mayo 2026</div>
              <div class="alert-msg">Ingresa las lecturas actuales de medidor para cada cliente.</div>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Cliente</th><th>Sector</th><th>Lectura Anterior</th><th>Lectura Actual</th><th>Consumo (m³)</th><th>Tarifa</th><th>Acción</th></tr></thead>
              <tbody>
                <tr>
                  <td class="td-primary">Ana Martínez</td><td>A-3</td>
                  <td class="td-mono">1,240</td>
                  <td><div class="lectura-input-group"><span class="lbl">m³</span><input type="number" value="1253" min="1240" /><span class="unit">actual</span></div></td>
                  <td class="td-mono text-accent">13 m³</td>
                  <td class="td-mono">$12.50</td>
                  <td><button class="btn btn-primary btn-sm" onclick="showToast('Lectura guardada','success')">💾 Guardar</button></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="flex-gap mt-16">
            <button class="btn btn-primary" onclick="showToast('Todas las lecturas guardadas y facturas generadas','success')">⚡ Guardar todas y generar facturas</button>
          </div>
        </div>

        <div class="tab-panel" data-panel="ops-vencidas" data-group="ops-tabs">
          <div class="alert alert-danger mb-16">
            <span class="alert-icon">⚠</span>
            <div class="alert-body">
              <div class="alert-title">8 facturas vencidas — Requieren atención</div>
              <div class="alert-msg">Se recomienda iniciar proceso de corte para clientes con más de 30 días de mora.</div>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead><tr><th># Factura</th><th>Cliente</th><th>Sector</th><th>Días vencida</th><th>Monto</th><th>Acciones</th></tr></thead>
              <tbody>
                <tr><td class="td-mono">#2026-0428</td><td class="td-primary">José Hernández</td><td>A-1</td><td style="color:var(--danger);font-weight:800;">45 días</td><td class="td-mono">$12.50</td><td><div class="flex-gap"><button class="btn btn-agua btn-sm">✓ Pagar</button><button class="btn btn-danger btn-sm">✂ Corte</button></div></td></tr>
                <tr><td class="td-mono">#2026-0415</td><td class="td-primary">Roberto Díaz</td><td>B-3</td><td style="color:var(--warning);font-weight:800;">32 días</td><td class="td-mono">$12.50</td><td><div class="flex-gap"><button class="btn btn-agua btn-sm">✓ Pagar</button><button class="btn btn-danger btn-sm">✂ Corte</button></div></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /operaciones -->


      <!-- ══════════════════════════════════
           VISTA 5: REPORTES
      ══════════════════════════════════ -->
      <div class="view" id="view-reportes">
        <div class="page-header">
          <div><h1 class="page-title">Reportes</h1><p class="page-subtitle">Análisis de ingresos, morosos y exportación de datos</p></div>
          <div class="btn-group">
            <button class="btn btn-ghost btn-sm">↓ Exportar Excel</button>
            <button class="btn btn-primary btn-sm" onclick="showToast('Generando PDF…','info')">↓ Exportar PDF</button>
          </div>
        </div>

        <div class="section-tabs" data-group="rep-tabs">
          <div class="section-tab active" data-panel="rep-ingresos" data-group="rep-tabs">💲 Ingresos</div>
          <div class="section-tab" data-panel="rep-morosos" data-group="rep-tabs">⚠ Morosos</div>
          <div class="section-tab" data-panel="rep-sectores" data-group="rep-tabs">🗺 Por sectores</div>
        </div>

        <div class="tab-panel active" data-panel="rep-ingresos" data-group="rep-tabs">
          <div class="kpi-grid mb-24">
            <div class="kpi-card"><div class="kpi-icon green">💲</div><div class="kpi-label">Total facturado</div><div class="kpi-value">$48,210</div><span class="kpi-delta up">↑ 6.5%</span></div>
            <div class="kpi-card"><div class="kpi-icon blue">✓</div><div class="kpi-label">Cobrado</div><div class="kpi-value">$44,190</div><span class="kpi-delta up">↑ 5.8%</span></div>
            <div class="kpi-card"><div class="kpi-icon red">⚠</div><div class="kpi-label">Pendiente</div><div class="kpi-value">$4,020</div><span class="kpi-delta down">↓ 3.1%</span></div>
            <div class="kpi-card glow"><div class="kpi-icon cyan">%</div><div class="kpi-label">% cobranza</div><div class="kpi-value">91.7%</div><span class="kpi-delta up">↑ 0.7%</span></div>
          </div>
        </div>

        <div class="tab-panel" data-panel="rep-morosos" data-group="rep-tabs">
          <div class="table-wrap">
            <table>
              <thead><tr><th>Cliente</th><th>Sector</th><th>Facturas vencidas</th><th>Total adeudado</th><th>Días máximo</th><th>Estado</th><th>Acciones</th></tr></thead>
              <tbody>
                <tr><td class="td-primary">José Hernández</td><td>A-1</td><td style="text-align:center;font-weight:700;">3</td><td class="td-mono text-danger">$37.50</td><td style="color:var(--danger);font-weight:800;">45 días</td><td><span class="badge badge-red">Moroso</span></td><td><div class="flex-gap"><button class="btn btn-agua btn-sm">✓ Cobrar</button><button class="btn btn-danger btn-sm">✂ Corte</button></div></td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="tab-panel" data-panel="rep-sectores" data-group="rep-tabs">
          <div class="table-wrap">
            <table>
              <thead><tr><th>Sector</th><th>Total casas</th><th>Al día</th><th>Morosos</th><th>Facturado</th><th>Cobrado</th><th>% Cobranza</th></tr></thead>
              <tbody>
                <tr><td class="td-primary">A-1</td><td>28</td><td style="color:var(--success);font-weight:700;">26</td><td style="color:var(--danger);font-weight:700;">2</td><td class="td-mono">$350.00</td><td class="td-mono">$325.00</td><td><div class="flex-gap"><span class="td-mono">92.8%</span><div class="progress-bar-wrap" style="flex:1;"><div class="progress-bar-fill" style="width:92.8%"></div></div></div></td></tr>
                <tr><td class="td-primary">A-2</td><td>31</td><td style="color:var(--success);font-weight:700;">31</td><td style="color:var(--danger);font-weight:700;">0</td><td class="td-mono">$387.50</td><td class="td-mono">$387.50</td><td><div class="flex-gap"><span class="td-mono">100%</span><div class="progress-bar-wrap" style="flex:1;"><div class="progress-bar-fill" style="width:100%"></div></div></div></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /reportes -->


      <!-- ══════════════════════════════════
           VISTA 6: CONFIGURACIÓN
      ══════════════════════════════════ -->
      <div class="view" id="view-config">
        <div class="page-header">
          <div><h1 class="page-title">Configuración</h1><p class="page-subtitle">Parámetros del sistema, tarifas y perfiles de usuario</p></div>
        </div>

        <div class="section-tabs" data-group="cfg-tabs">
          <div class="section-tab active" data-panel="cfg-general" data-group="cfg-tabs">⚙ General</div>
          <div class="section-tab" data-panel="cfg-tarifas" data-group="cfg-tabs">💲 Tarifas y Moras</div>
          <div class="section-tab" data-panel="cfg-usuarios" data-group="cfg-tabs">👤 Perfiles de Usuario</div>
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
            <button class="btn btn-primary mt-16" onclick="showToast('Configuración guardada correctamente','success')">💾 Guardar cambios</button>
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
                    <tr><td class="td-primary">Doméstica básica</td><td class="td-mono">$12.50</td><td>Hasta 10 m³</td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Editando tarifa…','info')">✏</button></td></tr>
                    <tr><td class="td-primary">Doméstica plus</td><td class="td-mono">$15.00</td><td>Hasta 20 m³</td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Editando tarifa…','info')">✏</button></td></tr>
                    <tr><td class="td-primary">Comercial</td><td class="td-mono">$25.00</td><td>Uso comercial</td><td><button class="btn btn-ghost btn-sm" onclick="showToast('Editando tarifa…','info')">✏</button></td></tr>
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
              <button class="btn btn-primary btn-sm mt-16" onclick="showToast('Parámetros de mora actualizados','success')">💾 Guardar parámetros</button>
            </div>
          </div>
        </div>

        <div class="tab-panel" data-panel="cfg-usuarios" data-group="cfg-tabs">
          <div class="flex-between mb-16">
            <div></div>
            <button class="btn btn-primary btn-sm">+ Nuevo usuario</button>
          </div>
          <div class="table-wrap" style="max-width:800px;">
            <table>
              <thead><tr><th>Usuario</th><th>Correo</th><th>Perfil / Rol</th><th>Permisos</th><th>Estado</th><th>Acciones</th></tr></thead>
              <tbody>
                <tr>
                  <td><div class="flex-gap"><div class="user-avatar" style="width:30px;height:30px;font-size:.7rem;background:var(--grad-brand);border:none;">AD</div><span class="td-primary">Administrador</span></div></td>
                  <td class="td-mono">admin@hidra.sv</td>
                  <td><span class="badge badge-blue">Administrador</span></td>
                  <td style="font-size:.72rem; color:var(--text-muted);">Acceso total</td>
                  <td><span class="badge badge-green">Activo</span></td>
                  <td><button class="btn btn-ghost btn-sm">✏ Editar</button></td>
                </tr>
                <tr>
                  <td><div class="flex-gap"><div class="user-avatar" style="width:30px;height:30px;font-size:.7rem;background:var(--celeste);border:none;">C1</div><span class="td-primary">Cajero 1</span></div></td>
                  <td class="td-mono">cajero1@hidra.sv</td>
                  <td><span class="badge badge-yellow">Cobrador</span></td>
                  <td style="font-size:.72rem; color:var(--text-muted);">Pagos y facturas</td>
                  <td><span class="badge badge-green">Activo</span></td>
                  <td><button class="btn btn-ghost btn-sm">✏ Editar</button></td>
                </tr>
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
      <span class="modal-title">📄 Registrar nueva factura / cliente</span>
      <button class="modal-close" id="modalClose">✕</button>
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
      <button class="btn btn-primary" onclick="closeModal(); showToast('Registro guardado correctamente','success')">💾 Guardar</button>
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
<script src="../../assets/js/territorio.js"         defer></script>
<script src="../../assets/js/ui.js"                 defer></script>

</body>
</html>
