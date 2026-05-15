<!-- ══════════════════════════════════
     VISTA: ESTADÍSTICAS
══════════════════════════════════ -->
<div class="view" id="view-estadisticas">

  <div class="page-header">
    <div>
      <h1 class="page-title">Estadísticas</h1>
      <p class="page-subtitle">Indicadores visuales de operación y finanzas</p>
    </div>
    <button class="btn btn-ghost btn-sm" onclick="showView('reportes')">→ Ir a Reportes</button>
  </div>

  <!-- Filtros -->
  <div class="card mb-24">
    <div style="display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;">
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
        <select class="form-control form-select">
          <option>Todos los sectores</option>
          <option>Colonia Centro</option>
          <option>Comunidad Norte</option>
          <option>Las Margaritas</option>
          <option>El Calvario</option>
        </select>
      </div>
      <button class="btn btn-primary" onclick="refreshEstadisticas()">Aplicar filtros</button>
    </div>
  </div>

  <!-- KPIs -->
  <div class="kpi-grid mb-24" style="grid-template-columns:repeat(5,1fr);">
    <div class="kpi-card">
      <div class="kpi-icon green">💰</div>
      <div class="kpi-label">Ingresos (periodo)</div>
      <div class="kpi-value" id="est-ingresos">$7,850</div>
      <span class="kpi-delta up">+12% vs anterior</span>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon blue">💧</div>
      <div class="kpi-label">Consumo total</div>
      <div class="kpi-value" id="est-consumo">18,240 m³</div>
      <span class="kpi-delta neutral">Periodo actual</span>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon red">⚠</div>
      <div class="kpi-label">Mora acumulada</div>
      <div class="kpi-value" id="est-mora" style="color:var(--danger);">$1,248</div>
      <span class="kpi-delta down">96 clientes</span>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon cyan">✓</div>
      <div class="kpi-label">Facturas pagadas</div>
      <div class="kpi-value" id="est-pagadas">486</div>
      <span class="kpi-delta up">82% del total</span>
    </div>
    <div class="kpi-card">
      <div class="kpi-icon yellow">⏳</div>
      <div class="kpi-label">Pendientes</div>
      <div class="kpi-value" id="est-pendientes">108</div>
      <span class="kpi-delta down">18% del total</span>
    </div>
  </div>

  <!-- Gráficas -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">

    <!-- Gráfica: Ingresos por mes -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Ingresos por mes</h2>
        <span style="font-size:.72rem;color:var(--text-muted);">Enero — Abril 2026</span>
      </div>
      <div id="chart-ingresos" style="display:flex;align-items:flex-end;gap:10px;height:160px;padding:8px 0;"></div>
      <div style="display:flex;gap:10px;margin-top:8px;" id="chart-ingresos-labels"></div>
    </div>

    <!-- Gráfica: Consumo por sector -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Consumo por sector</h2>
        <span style="font-size:.72rem;color:var(--text-muted);">m³ — periodo actual</span>
      </div>
      <div id="chart-sectores" style="display:flex;flex-direction:column;gap:10px;margin-top:8px;"></div>
    </div>

  </div>

  <!-- Tabla resumen por mes -->
  <div class="card">
    <div class="card-header"><h2 class="card-title">Resumen mensual</h2></div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Mes</th><th>Facturas emitidas</th><th>Pagadas</th><th>Pendientes</th><th>Ingresos</th><th>Mora generada</th><th>Consumo (m³)</th></tr>
        </thead>
        <tbody>
          <tr><td>Enero 2026</td><td>598</td><td>541</td><td>57</td><td class="td-primary">$1,892</td><td style="color:var(--danger);">$285</td><td>4,410</td></tr>
          <tr><td>Febrero 2026</td><td>601</td><td>555</td><td>46</td><td class="td-primary">$1,943</td><td style="color:var(--danger);">$241</td><td>4,520</td></tr>
          <tr><td>Marzo 2026</td><td>595</td><td>548</td><td>47</td><td class="td-primary">$1,927</td><td style="color:var(--warning);">$198</td><td>4,380</td></tr>
          <tr><td>Abril 2026</td><td>602</td><td>486</td><td>116</td><td class="td-primary">$2,088</td><td style="color:var(--danger);">$524</td><td>4,930</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div><!-- /view-estadisticas -->

<style>
/* ── Bar chart bars ── */
.chart-bar-col { display:flex; flex-direction:column; align-items:center; flex:1; gap:4px; }
.chart-bar {
  width:100%;
  max-width:48px;
  border-radius:6px 6px 0 0;
  background: linear-gradient(180deg, #66B3FF, #3E96F0);
  transition: height 0.7s cubic-bezier(0.34,1.56,0.64,1);
  cursor:pointer;
  position:relative;
}
.chart-bar:hover { filter:brightness(1.1); }
.chart-bar-val { font-size:.65rem; font-weight:800; color:var(--negro); }
.chart-bar-lbl { font-size:.65rem; color:var(--text-muted); font-weight:600; }

/* ── Horizontal bars (sectores) ── */
.sector-bar-row { display:flex; align-items:center; gap:10px; font-size:.75rem; }
.sector-bar-name { width:140px; text-align:right; font-weight:600; color:var(--text-primary); flex-shrink:0; }
.sector-bar-track { flex:1; height:20px; background:var(--celeste-xlt); border-radius:10px; overflow:hidden; }
.sector-bar-fill {
  height:100%;
  border-radius:10px;
  background: linear-gradient(90deg, #66B3FF, #A8D4FF);
  transition: width 0.8s cubic-bezier(0.34,1.56,0.64,1);
}
.sector-bar-val { width:60px; font-weight:700; color:var(--negro); font-size:.72rem; }
</style>

<script>
(function initEstadisticas() {
  const ingresosData = [
    { mes:'Ene', val:1892, max:2200 },
    { mes:'Feb', val:1943, max:2200 },
    { mes:'Mar', val:1927, max:2200 },
    { mes:'Abr', val:2088, max:2200 },
  ];
  const sectorData = [
    { name:'Colonia Centro',    m3:6840, pct:88 },
    { name:'Comunidad Norte',   m3:5280, pct:68 },
    { name:'Las Margaritas',    m3:3760, pct:48 },
    { name:'El Calvario',       m3:2360, pct:30 },
  ];

  function renderCharts() {
    // Barras verticales — ingresos
    const cI = document.getElementById('chart-ingresos');
    const cL = document.getElementById('chart-ingresos-labels');
    if (!cI) return;
    cI.innerHTML = ''; cL.innerHTML = '';
    const maxVal = Math.max(...ingresosData.map(d => d.max));
    ingresosData.forEach(d => {
      const pct = (d.val / maxVal) * 100;
      const col = document.createElement('div');
      col.className = 'chart-bar-col';
      col.innerHTML = `
        <div class="chart-bar-val">$${(d.val/1000).toFixed(1)}k</div>
        <div class="chart-bar" style="height:${pct}%;min-height:8px;" title="${d.mes}: $${d.val}"></div>`;
      cI.appendChild(col);
      const lbl = document.createElement('div');
      lbl.className = 'chart-bar-col';
      lbl.innerHTML = `<div class="chart-bar-lbl">${d.mes}</div>`;
      cL.appendChild(lbl);
    });

    // Barras horizontales — sectores
    const cS = document.getElementById('chart-sectores');
    if (!cS) return;
    cS.innerHTML = '';
    sectorData.forEach(d => {
      const row = document.createElement('div');
      row.className = 'sector-bar-row';
      row.innerHTML = `
        <div class="sector-bar-name">${d.name}</div>
        <div class="sector-bar-track"><div class="sector-bar-fill" style="width:${d.pct}%;"></div></div>
        <div class="sector-bar-val">${d.m3.toLocaleString()} m³</div>`;
      cS.appendChild(row);
    });
  }

  // Render when view becomes active
  document.querySelectorAll('[data-view="estadisticas"]').forEach(el => {
    el.addEventListener('click', () => setTimeout(renderCharts, 50));
  });

  // Also try on DOMContentLoaded (if already active)
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderCharts);
  } else {
    renderCharts();
  }

  window.refreshEstadisticas = function() {
    showToast('Filtros aplicados — datos actualizados', 'success');
    renderCharts();
  };
})();
</script>
