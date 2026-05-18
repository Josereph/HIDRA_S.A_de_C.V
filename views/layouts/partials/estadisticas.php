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
    <button class="btn btn-ghost btn-sm" onclick="showView('reportes')"><i class="fas fa-arrow-right"></i> Ir a Reportes</button>
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

    <div class="kpi-card estadisticas-kpi est-good">
      <div class="kpi-icon"><i class="bi bi-cash-coin"></i></div>
      <div class="kpi-label">Ingresos (periodo)</div>
      <div class="kpi-value" id="est-ingresos">$7,850</div>
      <span class="kpi-delta est-delta-good">+12% vs anterior</span>
    </div>

    <div class="kpi-card estadisticas-kpi est-normal">
      <div class="kpi-icon"><i class="bi bi-droplet-half"></i></div>
      <div class="kpi-label">Consumo total</div>
      <div class="kpi-value" id="est-consumo">18,240 m³</div>
      <span class="kpi-delta est-delta-normal">Periodo actual</span>
    </div>

    <div class="kpi-card estadisticas-kpi est-attention">
      <div class="kpi-icon"><i class="bi bi-exclamation-triangle"></i></div>
      <div class="kpi-label">Mora acumulada</div>
      <div class="kpi-value" id="est-mora">$1,248</div>
      <span class="kpi-delta est-delta-attention">96 clientes</span>
    </div>

    <div class="kpi-card estadisticas-kpi est-good">
      <div class="kpi-icon"><i class="bi bi-check-circle"></i></div>
      <div class="kpi-label">Facturas pagadas</div>
      <div class="kpi-value" id="est-pagadas">486</div>
      <span class="kpi-delta est-delta-good">82% del total</span>
    </div>

    <div class="kpi-card estadisticas-kpi est-attention">
      <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
      <div class="kpi-label">Pendientes</div>
      <div class="kpi-value" id="est-pendientes">108</div>
      <span class="kpi-delta est-delta-attention">18% del total</span>
    </div>

  </div>

  <!-- Gráficas -->
  <div class="estadisticas-charts-grid mb-24">

    <!-- Gráfica: Ingresos por mes (ApexCharts) -->
    <section class="card estadisticas-panel">
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
      <div id="chart-ingresos-apex" style="min-height:230px;" aria-label="Gráfica de ingresos por mes"></div>
    </section>

    <!-- Gráfica: Consumo por sector -->
    <section class="card estadisticas-panel">
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
   ESTADÍSTICAS — Paleta clara (igual que apartado Alertas)
   Tokens: var(--bg-card), var(--border-subtle), var(--negro),
           var(--celeste), var(--celeste-dk), var(--pending),
           var(--celeste-xlt), var(--text-muted), etc.
   Scope: solo afecta #view-estadisticas.
═══════════════════════════════════════════════════════ */

/* ── Filtros ─────────────────────────────────────────── */
#view-estadisticas .estadisticas-filter-grid {
  display: flex;
  align-items: flex-end;
  gap: 16px;
  flex-wrap: wrap;
}

#view-estadisticas .estadisticas-filter-btn {
  min-height: 42px;
}

/* ── KPI grid ────────────────────────────────────────── */
#view-estadisticas .estadisticas-kpi-grid {
  gap: 18px !important;
}

/* Sobreescribe el fondo oscuro: hereda el fondo blanco de .kpi-card */
#view-estadisticas .estadisticas-kpi {
  min-height: 164px;
  padding: 20px 20px 16px;
}

/* Barra superior del KPI */
#view-estadisticas .estadisticas-kpi::before {
  content: "";
  position: absolute;
  top: 0;
  left: 14px;
  right: 14px;
  height: 3px;
  border-radius: 999px;
  background: var(--celeste-lt);
}

/* Iconos: hereda el override flat de components.css (fondo gris, texto negro) */
#view-estadisticas .estadisticas-kpi .kpi-icon {
  width: 44px;
  height: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
  border-radius: 8px;
  font-size: 1.1rem;
}

#view-estadisticas .estadisticas-kpi .kpi-label {
  color: var(--text-muted);
  font-size: .72rem;
  letter-spacing: .09em;
  text-transform: uppercase;
  font-weight: 800;
  margin-bottom: 4px;
}

#view-estadisticas .estadisticas-kpi .kpi-value {
  color: var(--negro) !important;
  font-size: 1.6rem;
  line-height: 1.1;
  font-weight: 800;
  margin-bottom: 10px;
}

/* Delta badge — estilo igual que .kpi-delta.up en components.css */
#view-estadisticas .estadisticas-kpi .kpi-delta {
  display: inline-flex;
  align-items: center;
  gap: 3px;
  font-size: 0.68rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 20px;
}

/* Status: good (celeste claro) */
#view-estadisticas .estadisticas-kpi.est-good::before {
  background: var(--celeste);
}
#view-estadisticas .estadisticas-kpi.est-good .kpi-icon {
  background: var(--celeste-xlt) !important;
  color: var(--celeste-dk) !important;
  border: 1.5px solid var(--celeste-lt);
}
#view-estadisticas .est-delta-good {
  background: var(--celeste-xlt);
  color: var(--celeste-dk);
  border: 1px solid var(--celeste-lt);
}

/* Status: normal (celeste muy suave) */
#view-estadisticas .estadisticas-kpi.est-normal::before {
  background: var(--celeste-lt);
}
#view-estadisticas .estadisticas-kpi.est-normal .kpi-icon {
  background: var(--gris-bg) !important;
  color: var(--gris-texto) !important;
  border: 1.5px solid var(--border-subtle);
}
#view-estadisticas .est-delta-normal {
  background: var(--gris-bg);
  color: var(--gris-texto);
  border: 1px solid var(--border-subtle);
}

/* Status: attention (azul pending) */
#view-estadisticas .estadisticas-kpi.est-attention::before {
  background: var(--pending);
}
#view-estadisticas .estadisticas-kpi.est-attention {
  border-color: rgba(21,101,192,0.35);
}
#view-estadisticas .estadisticas-kpi.est-attention .kpi-icon {
  background: rgba(21,101,192,0.10) !important;
  color: var(--pending) !important;
  border: 1.5px solid rgba(21,101,192,0.30);
}
#view-estadisticas .est-delta-attention {
  background: rgba(21,101,192,0.10);
  color: var(--pending);
  border: 1px solid rgba(21,101,192,0.30);
}

/* ── Paneles de gráficas ─────────────────────────────── */
#view-estadisticas .estadisticas-charts-grid {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap: 24px;
}

#view-estadisticas .estadisticas-panel {
  min-height: 318px;
  padding: 26px 28px 28px;
  border-radius: 16px;
}

#view-estadisticas .estadisticas-panel-header,
#view-estadisticas .estadisticas-summary-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 16px;
}

#view-estadisticas .estadisticas-panel-title {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--negro);
  font-size: .9rem;
  line-height: 1.2;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .065em;
}

#view-estadisticas .estadisticas-title-accent {
  width: 4px;
  height: 20px;
  display: inline-block;
  border-radius: 999px;
  background: var(--celeste);
  flex-shrink: 0;
}

#view-estadisticas .estadisticas-panel-note {
  margin: 10px 0 0;
  color: var(--celeste-dk);
  font-size: .78rem;
  font-weight: 600;
}

#view-estadisticas .estadisticas-panel-period {
  color: var(--gris-muted);
  font-size: .78rem;
  font-weight: 700;
  white-space: nowrap;
}

/* ── Consumo por sector ──────────────────────────────── */
#view-estadisticas .estadisticas-sector-list {
  display: flex;
  flex-direction: column;
  gap: 0;
  margin-top: 4px;
}

#view-estadisticas .sector-bar-row {
  display: grid;
  grid-template-columns: 40px minmax(130px, 180px) minmax(140px, 1fr) 86px;
  align-items: center;
  gap: 14px;
  padding: 13px 0;
  border-bottom: 1px solid var(--border-subtle);
}

#view-estadisticas .sector-bar-row:last-child {
  border-bottom: 0;
}

#view-estadisticas .sector-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 1.5px solid var(--border-subtle);
  color: var(--celeste-dk);
  background: var(--celeste-xlt);
  font-size: .95rem;
}

#view-estadisticas .sector-bar-name {
  color: var(--gris-texto);
  font-size: .84rem;
  font-weight: 700;
}

#view-estadisticas .sector-bar-track {
  width: 100%;
  height: 10px;
  border-radius: 99px;
  background: var(--celeste-xlt);
  overflow: hidden;
  border: 1px solid var(--celeste-lt);
}

#view-estadisticas .sector-bar-fill {
  height: 100%;
  border-radius: 99px;
  background: var(--celeste);
}

#view-estadisticas .sector-bar-val {
  color: var(--negro);
  font-size: .82rem;
  font-weight: 800;
  text-align: right;
}

/* ── Tabla resumen ───────────────────────────────────── */
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

#view-estadisticas .estadisticas-table .td-primary {
  color: var(--celeste-dk) !important;
  font-weight: 800;
}

#view-estadisticas .estadisticas-table .td-attention {
  color: var(--pending) !important;
  font-weight: 800;
}

#view-estadisticas .estadisticas-table .td-normal {
  color: var(--gris-muted) !important;
  font-weight: 700;
}

/* ── Responsive ──────────────────────────────────────── */
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
    padding: 18px 16px 22px;
  }
  #view-estadisticas .estadisticas-panel-header,
  #view-estadisticas .estadisticas-summary-header {
    flex-direction: column;
    align-items: flex-start;
  }
  #view-estadisticas .sector-bar-row {
    grid-template-columns: 36px 1fr;
    gap: 8px 10px;
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

  // ── ApexCharts: gráfica de ingresos ─────────────────
  let apexChart = null;

  function renderIngresosApex() {
    const el = document.getElementById('chart-ingresos-apex');
    if (!el) return;

    // Destruir instancia previa si existe
    if (apexChart) {
      apexChart.destroy();
      apexChart = null;
    }

    const options = {
      chart: {
        type: 'bar',
        height: 220,
        toolbar: { show: false },
        fontFamily: "'Outfit', sans-serif",
        background: 'transparent',
        animations: {
          enabled: true,
          easing: 'easeinout',
          speed: 600,
          animateGradually: { enabled: true, delay: 80 },
        },
      },
      series: [{
        name: 'Ingresos',
        data: ingresosData.map(d => d.val),
      }],
      xaxis: {
        categories: ingresosData.map(d => d.mes),
        labels: {
          style: { colors: '#6A8098', fontSize: '12px', fontWeight: 700 }
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
      },
      yaxis: {
        labels: {
          formatter: val => '$' + (val / 1000).toFixed(1) + 'k',
          style: { colors: '#6A8098', fontSize: '11px' }
        }
      },
      plotOptions: {
        bar: {
          borderRadius: 6,
          columnWidth: '52%',
          dataLabels: { position: 'top' },
        }
      },
      dataLabels: {
        enabled: true,
        formatter: val => '$' + (val / 1000).toFixed(1) + 'k',
        offsetY: -22,
        style: { fontSize: '11px', fontWeight: 800, colors: ['#000000'] },
      },
      colors: ['#66B3FF'],
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: 'vertical',
          shadeIntensity: 0.25,
          gradientToColors: ['#3E96F0'],
          stops: [0, 100],
        }
      },
      grid: {
        borderColor: '#C8DFF7',
        strokeDashArray: 4,
        xaxis: { lines: { show: false } },
        yaxis: { lines: { show: true } },
        padding: { top: 0, right: 0, bottom: 0, left: 4 },
      },
      tooltip: {
        y: { formatter: val => '$' + val.toLocaleString() },
        theme: 'light',
      },
      legend: { show: false },
    };

    apexChart = new ApexCharts(el, options);
    apexChart.render();
  }

  // ── Consumo por sector (barras horizontales CSS) ─────
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
    renderIngresosApex();
    renderSectoresChart();
  }

  // Ejecutar al hacer clic en el nav-item de Estadísticas
  document.querySelectorAll('[data-view="estadisticas"]').forEach(function(el) {
    el.addEventListener('click', function() {
      setTimeout(renderCharts, 100);
    });
  });

  // Ejecutar al cargar si ya es la vista activa
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      if (document.getElementById('view-estadisticas')?.classList.contains('active')) {
        renderCharts();
      }
    });
  } else {
    if (document.getElementById('view-estadisticas')?.classList.contains('active')) {
      renderCharts();
    }
  }

  window.refreshEstadisticas = function() {
    renderCharts();
    if (typeof showToast === 'function') {
      showToast('Filtros aplicados — datos actualizados', 'success');
    }
  };

})();
</script>
