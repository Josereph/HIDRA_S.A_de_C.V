<!-- Bootstrap Icons: solo iconos, compatible con Bootstrap 4 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- ══════════════════════════════════
     VISTA: ESTADÍSTICAS
══════════════════════════════════ -->
<div class="view" id="view-estadisticas">

  <div class="page-header estadisticas-page-header">
    <div>
      <h1 class="page-title">Estadísticas</h1>
      <p class="page-subtitle">Indicadores visuales de operación y finanzas</p>
    </div>
    <button class="btn btn-ghost btn-sm" onclick="showView('reportes')">→ Ir a Reportes</button>
  </div>

  <!-- Filtros -->
  <div class="card mb-24 estadisticas-filter-card">
    <div class="estadisticas-filter-grid">
      <div class="form-group mb-0">
        <label class="form-label">Desde</label>
        <input type="date" class="form-control" id="est-desde" value="2026-01-01" />
      </div>

      <div class="form-group mb-0">
        <label class="form-label">Hasta</label>
        <input type="date" class="form-control" id="est-hasta" value="2026-04-30" />
      </div>

      <div class="form-group mb-0">
        <label class="form-label">Sector</label>
        <select class="form-control form-select" id="est-sector">
          <option>Todos los sectores</option>
          <option>Colonia Centro</option>
          <option>Comunidad Norte</option>
          <option>Las Margaritas</option>
          <option>El Calvario</option>
        </select>
      </div>

      <button class="btn btn-primary estadisticas-filter-btn" onclick="refreshEstadisticas()">
        Aplicar filtros
      </button>
    </div>
  </div>

  <!-- KPIs -->
  <div class="kpi-grid mb-24 estadisticas-kpi-grid" style="grid-template-columns:repeat(5,1fr);">

    <div class="kpi-card estadisticas-kpi status-good">
      <div class="kpi-icon"><i class="bi bi-cash-coin"></i></div>
      <div class="kpi-label">Ingresos (periodo)</div>
      <div class="kpi-value" id="est-ingresos">$7,850</div>
      <span class="kpi-delta">+12% vs anterior</span>
    </div>

    <div class="kpi-card estadisticas-kpi status-normal">
      <div class="kpi-icon"><i class="bi bi-droplet-half"></i></div>
      <div class="kpi-label">Consumo total</div>
      <div class="kpi-value" id="est-consumo">18,240 m³</div>
      <span class="kpi-delta">Periodo actual</span>
    </div>

    <div class="kpi-card estadisticas-kpi status-attention">
      <div class="kpi-icon"><i class="bi bi-exclamation-triangle"></i></div>
      <div class="kpi-label">Mora acumulada</div>
      <div class="kpi-value" id="est-mora">$1,248</div>
      <span class="kpi-delta">96 clientes</span>
    </div>

    <div class="kpi-card estadisticas-kpi status-good">
      <div class="kpi-icon"><i class="bi bi-check-circle"></i></div>
      <div class="kpi-label">Facturas pagadas</div>
      <div class="kpi-value" id="est-pagadas">486</div>
      <span class="kpi-delta">82% del total</span>
    </div>

    <div class="kpi-card estadisticas-kpi status-attention">
      <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
      <div class="kpi-label">Pendientes</div>
      <div class="kpi-value" id="est-pendientes">108</div>
      <span class="kpi-delta">18% del total</span>
    </div>

  </div>

  <!-- Gráficas -->
  <div class="estadisticas-charts-grid mb-24">

    <!-- Gráfica: Ingresos por mes -->
    <section class="card estadisticas-panel estadisticas-panel-chart">
      <div class="estadisticas-panel-header">
        <div>
          <h2 class="estadisticas-panel-title">
            <span class="estadisticas-title-accent"></span>
            Ingresos por mes
          </h2>
          <p class="estadisticas-panel-note">Miles de dólares (USD)</p>
        </div>
        <span class="estadisticas-panel-period">Enero — Abril 2026</span>
      </div>

      <div id="chart-ingresos" class="estadisticas-bar-chart" aria-label="Gráfica de ingresos por mes"></div>
      <div id="chart-ingresos-labels" class="estadisticas-chart-labels" aria-hidden="true" style="display:none;"></div>
    </section>

    <!-- Gráfica: Consumo por sector -->
    <section class="card estadisticas-panel estadisticas-panel-sector">
      <div class="estadisticas-panel-header">
        <div>
          <h2 class="estadisticas-panel-title">
            <span class="estadisticas-title-accent"></span>
            Consumo por sector
          </h2>
        </div>
        <span class="estadisticas-panel-period">m³ — periodo actual</span>
      </div>

      <div id="chart-sectores" class="estadisticas-sector-list" aria-label="Consumo por sector"></div>
    </section>

  </div>

  <!-- Tabla resumen por mes -->
  <div class="card estadisticas-summary-card">
    <div class="card-header estadisticas-summary-header">
      <h2 class="card-title">Resumen mensual</h2>
      <span class="estadisticas-panel-period">Datos consolidados del periodo</span>
    </div>

    <div class="table-wrap estadisticas-table-wrap">
      <table class="estadisticas-table">
        <thead>
          <tr>
            <th>Mes</th>
            <th>Facturas emitidas</th>
            <th>Pagadas</th>
            <th>Pendientes</th>
            <th>Ingresos</th>
            <th>Mora generada</th>
            <th>Consumo (m³)</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Enero 2026</td><td>598</td><td>541</td><td>57</td><td class="td-primary">$1,892</td><td class="td-attention">$285</td><td>4,410</td></tr>
          <tr><td>Febrero 2026</td><td>601</td><td>555</td><td>46</td><td class="td-primary">$1,943</td><td class="td-attention">$241</td><td>4,520</td></tr>
          <tr><td>Marzo 2026</td><td>595</td><td>548</td><td>47</td><td class="td-primary">$1,927</td><td class="td-normal">$198</td><td>4,380</td></tr>
          <tr><td>Abril 2026</td><td>602</td><td>486</td><td>116</td><td class="td-primary">$2,088</td><td class="td-attention">$524</td><td>4,930</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div><!-- /view-estadisticas -->

<style>
/* ═══════════════════════════════════════════════════════
   ESTADÍSTICAS — DISEÑO FORMAL / COMPACTO / SIN ROJO
   Paleta usada: negro, blanco, celeste #66B3FF y azul profundo derivado.
   Scope: solo afecta #view-estadisticas.
═══════════════════════════════════════════════════════ */
#view-estadisticas {
  --hidra-black: #000000;
  --hidra-white: #FFFFFF;
  --hidra-celeste: #66B3FF;
  --hidra-celeste-soft: rgba(102, 179, 255, .78);
  --hidra-celeste-muted: rgba(102, 179, 255, .42);
  --hidra-blue-strong: #1F86FF;
  --hidra-panel: rgba(0, 0, 0, .18);
  --hidra-panel-strong: rgba(0, 0, 0, .28);
  --hidra-line: rgba(102, 179, 255, .34);
  --hidra-line-soft: rgba(255, 255, 255, .10);
  --hidra-text: rgba(255, 255, 255, .94);
  --hidra-text-soft: rgba(255, 255, 255, .72);
  --hidra-text-muted: rgba(255, 255, 255, .50);
}

#view-estadisticas .estadisticas-page-header {
  margin-bottom: 24px;
}

#view-estadisticas .estadisticas-filter-card,
#view-estadisticas .estadisticas-panel,
#view-estadisticas .estadisticas-summary-card {
  border: 1px solid var(--hidra-line);
  background: var(--hidra-panel);
  box-shadow: none;
}

#view-estadisticas .estadisticas-filter-grid {
  display: flex;
  align-items: flex-end;
  gap: 16px;
  flex-wrap: wrap;
}

#view-estadisticas .estadisticas-filter-btn {
  min-height: 42px;
}

/* ── KPI cards ───────────────────────────────────── */
#view-estadisticas .estadisticas-kpi-grid {
  gap: 18px !important;
}

#view-estadisticas .estadisticas-kpi {
  position: relative;
  overflow: hidden;
  min-height: 172px;
  padding: 22px 22px 18px;
  border: 1px solid var(--hidra-line);
  background: var(--hidra-panel);
  box-shadow: none;
}

#view-estadisticas .estadisticas-kpi::before {
  content: "";
  position: absolute;
  top: 0;
  left: 18px;
  right: 18px;
  height: 2px;
  border-radius: 999px;
  background: var(--hidra-celeste-muted);
}

#view-estadisticas .estadisticas-kpi .kpi-icon {
  width: 46px;
  height: 46px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 18px;
  border-radius: 9px;
  border: 1px solid var(--hidra-line);
  color: var(--hidra-celeste-soft);
  background: rgba(0, 0, 0, .20);
  font-size: 1.1rem;
}

#view-estadisticas .estadisticas-kpi .kpi-label {
  color: var(--hidra-text-soft);
  font-size: .76rem;
  letter-spacing: .095em;
  text-transform: uppercase;
  font-weight: 800;
  margin-bottom: 6px;
}

#view-estadisticas .estadisticas-kpi .kpi-value {
  color: var(--hidra-text) !important;
  font-size: 1.75rem;
  line-height: 1.1;
  font-weight: 850;
  margin-bottom: 12px;
}

#view-estadisticas .estadisticas-kpi .kpi-delta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: fit-content;
  min-height: 24px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: .7rem;
  font-weight: 800;
  letter-spacing: .01em;
  background: rgba(0, 0, 0, .24);
  border: 1px solid var(--hidra-celeste-muted);
  color: var(--hidra-celeste-soft);
}

/* Bueno / positivo: celeste claro */
#view-estadisticas .estadisticas-kpi.status-good::before {
  background: var(--hidra-celeste);
}
#view-estadisticas .estadisticas-kpi.status-good .kpi-icon,
#view-estadisticas .estadisticas-kpi.status-good .kpi-delta {
  border-color: rgba(102, 179, 255, .82);
  color: var(--hidra-celeste);
}

/* Normal: sobrio */
#view-estadisticas .estadisticas-kpi.status-normal::before {
  background: rgba(102, 179, 255, .38);
}
#view-estadisticas .estadisticas-kpi.status-normal .kpi-icon,
#view-estadisticas .estadisticas-kpi.status-normal .kpi-delta {
  border-color: rgba(102, 179, 255, .30);
  color: rgba(102, 179, 255, .70);
}

/* Atención: azul más remarcado, sin rojo */
#view-estadisticas .estadisticas-kpi.status-attention::before {
  background: var(--hidra-blue-strong);
}
#view-estadisticas .estadisticas-kpi.status-attention {
  border-color: rgba(31, 134, 255, .55);
}
#view-estadisticas .estadisticas-kpi.status-attention .kpi-icon,
#view-estadisticas .estadisticas-kpi.status-attention .kpi-delta {
  border-color: rgba(31, 134, 255, .88);
  color: var(--hidra-blue-strong);
}

/* ── Paneles de gráficas ─────────────────────────── */
#view-estadisticas .estadisticas-charts-grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: 24px;
}

#view-estadisticas .estadisticas-panel {
  min-height: 318px;
  padding: 26px 30px 28px;
  border-radius: 16px;
}

#view-estadisticas .estadisticas-panel-header,
#view-estadisticas .estadisticas-summary-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 20px;
}

#view-estadisticas .estadisticas-panel-title {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  color: var(--hidra-text);
  font-size: .98rem;
  line-height: 1.2;
  font-weight: 850;
  text-transform: uppercase;
  letter-spacing: .065em;
}

#view-estadisticas .estadisticas-title-accent {
  width: 4px;
  height: 22px;
  display: inline-block;
  border-radius: 999px;
  background: var(--hidra-celeste);
}

#view-estadisticas .estadisticas-panel-note {
  margin: 14px 0 0;
  color: var(--hidra-celeste);
  font-size: .82rem;
  font-weight: 600;
}

#view-estadisticas .estadisticas-panel-period {
  color: var(--hidra-celeste-soft);
  font-size: .8rem;
  font-weight: 700;
  white-space: nowrap;
}

/* ── Gráfica de ingresos por mes ─────────────────── */
#view-estadisticas #chart-ingresos-labels {
  display: none !important;
}

#view-estadisticas .estadisticas-bar-chart {
  display: grid;
  grid-template-columns: 46px minmax(0, 1fr);
  gap: 14px;
  height: 230px;
  margin-top: 4px;
}

#view-estadisticas .stats-y-axis {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: flex-end;
  padding: 0 0 22px 0;
  color: var(--hidra-text-soft);
  font-size: .78rem;
  font-weight: 600;
}

#view-estadisticas .stats-plot {
  position: relative;
  height: 100%;
  border-left: 1px solid rgba(255, 255, 255, .42);
  border-bottom: 1px solid rgba(255, 255, 255, .42);
  padding: 0 16px 0;
}

#view-estadisticas .stats-grid-line {
  position: absolute;
  left: 0;
  right: 0;
  height: 1px;
  background: rgba(255, 255, 255, .12);
}

#view-estadisticas .stats-bars {
  position: relative;
  z-index: 2;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  align-items: end;
  gap: 28px;
  height: calc(100% - 22px);
}

#view-estadisticas .stats-bar-item {
  height: 100%;
  min-width: 54px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: center;
  gap: 8px;
}

#view-estadisticas .stats-bar-value {
  color: var(--hidra-white);
  font-size: .82rem;
  font-weight: 850;
  line-height: 1;
}

#view-estadisticas .stats-bar {
  width: 70%;
  min-width: 42px;
  max-width: 70px;
  border-radius: 3px 3px 0 0;
  background: var(--hidra-celeste);
  border: 1px solid rgba(255, 255, 255, .08);
}

#view-estadisticas .stats-bar-labels {
  position: absolute;
  left: 16px;
  right: 16px;
  bottom: -24px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 28px;
  color: var(--hidra-text-soft);
  font-size: .78rem;
  font-weight: 650;
  text-align: center;
}

/* ── Consumo por sector ──────────────────────────── */
#view-estadisticas .estadisticas-sector-list {
  display: flex;
  flex-direction: column;
  gap: 0;
  margin-top: 12px;
}

#view-estadisticas .sector-bar-row {
  display: grid;
  grid-template-columns: 48px minmax(150px, 190px) minmax(170px, 1fr) 94px;
  align-items: center;
  gap: 16px;
  padding: 15px 0;
  border-bottom: 1px solid var(--hidra-line-soft);
}

#view-estadisticas .sector-bar-row:last-child {
  border-bottom: 0;
}

#view-estadisticas .sector-icon {
  width: 38px;
  height: 38px;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 1px solid var(--hidra-celeste-muted);
  color: var(--hidra-white);
  background: rgba(0, 0, 0, .18);
  font-size: 1rem;
}

#view-estadisticas .sector-bar-name {
  width: auto;
  text-align: left;
  color: var(--hidra-text);
  font-size: .86rem;
  font-weight: 750;
  flex-shrink: 1;
}

#view-estadisticas .sector-bar-track {
  width: 100%;
  height: 12px;
  border-radius: 4px;
  background: rgba(255, 255, 255, .10);
  overflow: hidden;
}

#view-estadisticas .sector-bar-fill {
  height: 100%;
  border-radius: 4px;
  background: var(--hidra-celeste);
}

#view-estadisticas .sector-bar-val {
  width: auto;
  color: var(--hidra-white);
  font-size: .86rem;
  font-weight: 850;
  text-align: right;
}

/* ── Tabla resumen ───────────────────────────────── */
#view-estadisticas .estadisticas-summary-card {
  border-radius: 16px;
}

#view-estadisticas .estadisticas-table-wrap {
  overflow-x: auto;
}

#view-estadisticas .estadisticas-table {
  width: 100%;
  border-collapse: collapse;
}

#view-estadisticas .estadisticas-table th,
#view-estadisticas .estadisticas-table td {
  border-color: rgba(102, 179, 255, .16);
}

#view-estadisticas .estadisticas-table .td-primary {
  color: var(--hidra-celeste) !important;
  font-weight: 850;
}

#view-estadisticas .estadisticas-table .td-attention {
  color: var(--hidra-blue-strong) !important;
  font-weight: 850;
}

#view-estadisticas .estadisticas-table .td-normal {
  color: var(--hidra-text-soft) !important;
  font-weight: 750;
}

/* ── Responsive ─────────────────────────────────── */
@media (max-width: 1180px) {
  #view-estadisticas .estadisticas-kpi-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  }

  #view-estadisticas .estadisticas-charts-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 720px) {
  #view-estadisticas .estadisticas-kpi-grid {
    grid-template-columns: 1fr !important;
  }

  #view-estadisticas .estadisticas-panel {
    padding: 20px 18px 24px;
  }

  #view-estadisticas .estadisticas-panel-header,
  #view-estadisticas .estadisticas-summary-header {
    flex-direction: column;
    align-items: flex-start;
  }

  #view-estadisticas #chart-ingresos-labels {
  display: none !important;
}

#view-estadisticas .estadisticas-bar-chart {
    grid-template-columns: 34px minmax(0, 1fr);
  }

  #view-estadisticas .stats-bars {
    gap: 14px;
  }

  #view-estadisticas .stats-bar-labels {
    gap: 14px;
  }

  #view-estadisticas .sector-bar-row {
    grid-template-columns: 38px 1fr;
    gap: 10px 12px;
  }

  #view-estadisticas .sector-bar-track,
  #view-estadisticas .sector-bar-val {
    grid-column: 2 / 3;
  }

  #view-estadisticas .sector-bar-val {
    text-align: left;
  }
}
</style>

<script>
(function initEstadisticas() {
  const ingresosData = [
    { mes: 'Ene', val: 1892 },
    { mes: 'Feb', val: 1943 },
    { mes: 'Mar', val: 1927 },
    { mes: 'Abr', val: 2088 },
  ];

  const sectorData = [
    { name: 'Colonia Centro',  m3: 6840, pct: 88, icon: 'bi-building' },
    { name: 'Comunidad Norte', m3: 5280, pct: 68, icon: 'bi-house-door' },
    { name: 'Las Margaritas',  m3: 3760, pct: 48, icon: 'bi-droplet' },
    { name: 'El Calvario',     m3: 2360, pct: 30, icon: 'bi-water' },
  ];

  function renderIngresosChart() {
    const chart = document.getElementById('chart-ingresos');
    const labels = document.getElementById('chart-ingresos-labels');
    if (!chart) return;

    const maxScale = 2500;
    const yLabels = ['2.5', '2.0', '1.5', '1.0', '0.5', '0'];
    const gridLines = [0, 20, 40, 60, 80, 100];

    const barsHtml = ingresosData.map(item => {
      const height = Math.max(3, (item.val / maxScale) * 100);
      const value = `$${(item.val / 1000).toFixed(1)}k`;

      return `
        <div class="stats-bar-item" title="${item.mes}: $${item.val.toLocaleString()}">
          <div class="stats-bar-value">${value}</div>
          <div class="stats-bar" style="height:${height}%;"></div>
        </div>`;
    }).join('');

    const monthLabelsHtml = ingresosData.map(item => `<span>${item.mes}</span>`).join('');

    chart.innerHTML = `
      <div class="stats-y-axis">
        ${yLabels.map(label => `<span>${label}</span>`).join('')}
      </div>
      <div class="stats-plot">
        ${gridLines.map(line => `<span class="stats-grid-line" style="bottom:${line}%;"></span>`).join('')}
        <div class="stats-bars">${barsHtml}</div>
        <div class="stats-bar-labels">${monthLabelsHtml}</div>
      </div>`;

    if (labels) {
      labels.innerHTML = '';
    }
  }

  function renderSectoresChart() {
    const container = document.getElementById('chart-sectores');
    if (!container) return;

    container.innerHTML = sectorData.map(item => `
      <div class="sector-bar-row" title="${item.name}: ${item.m3.toLocaleString()} m³">
        <div class="sector-icon"><i class="bi ${item.icon}"></i></div>
        <div class="sector-bar-name">${item.name}</div>
        <div class="sector-bar-track">
          <div class="sector-bar-fill" style="width:${item.pct}%;"></div>
        </div>
        <div class="sector-bar-val">${item.m3.toLocaleString()} m³</div>
      </div>`).join('');
  }

  function renderCharts() {
    renderIngresosChart();
    renderSectoresChart();
  }

  document.querySelectorAll('[data-view="estadisticas"]').forEach(function(el) {
    el.addEventListener('click', function() {
      setTimeout(renderCharts, 80);
    });
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderCharts);
  } else {
    renderCharts();
  }

  window.refreshEstadisticas = function() {
    renderCharts();

    if (typeof showToast === 'function') {
      showToast('Filtros aplicados — datos actualizados', 'success');
    } else {
      console.log('Filtros aplicados — datos actualizados');
    }
  };
})();
</script>
