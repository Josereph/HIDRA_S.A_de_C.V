<!-- ══════════════════════════════════
     VISTA: TERRITORIO
══════════════════════════════════ -->
<div class="view" id="view-territorio">

  <div class="page-header">
    <div>
      <h1 class="page-title">Territorio</h1>
      <p class="page-subtitle">Sectores, casas e inmuebles del servicio</p>
    </div>
    <div class="btn-group">
      <button class="btn btn-ghost btn-sm" onclick="showToast('Exportando…','info')">↓ Exportar</button>
      <button class="btn btn-primary btn-sm" onclick="showToast('Abrir formulario de nuevo sector/casa','info')">+ Nuevo</button>
    </div>
  </div>

  <div class="section-tabs" data-group="terr-tabs">
    <div class="section-tab active" data-panel="terr-sectores" data-group="terr-tabs">🌐 Sectores</div>
    <div class="section-tab"        data-panel="terr-casas"    data-group="terr-tabs">🏠 Casas</div>
    <div class="section-tab"        data-panel="terr-vista"    data-group="terr-tabs">🗺 Vista por sector</div>
  </div>

  <!-- ── TAB 1: SECTORES ─────────────────────────── -->
  <div class="tab-panel active" data-panel="terr-sectores" data-group="terr-tabs">

    <div class="flex-between mb-16" style="flex-wrap:wrap;gap:12px;">
      <div class="search-bar">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Buscar sector o descripción…" />
      </div>
      <div class="flex-gap">
        <select class="form-control" style="width:auto;padding:8px 12px;">
          <option>Todos los estados</option>
          <option>Activo</option>
          <option>Inactivo</option>
        </select>
        <button class="btn btn-agua btn-sm">+ Nuevo sector</button>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Cód.</th>
            <th class="sortable">Sector <span class="sort-icon">↕</span></th>
            <th>Casas activas</th>
            <th>En mora</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td-mono">S-001</td>
            <td class="td-primary">Colonia Centro</td>
            <td>245</td>
            <td style="color:var(--danger);font-weight:700;">35</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm" onclick="showToast('Viendo Colonia Centro','info')">👁 Ver</button>
              <button class="btn btn-ghost btn-sm" onclick="showToast('Editando sector','info')">✏ Editar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">S-002</td>
            <td class="td-primary">Comunidad Norte</td>
            <td>188</td>
            <td style="color:var(--warning);font-weight:700;">12</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm" onclick="showToast('Viendo Comunidad Norte','info')">👁 Ver</button>
              <button class="btn btn-ghost btn-sm">✏ Editar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">S-003</td>
            <td class="td-primary">Residencial Las Margaritas</td>
            <td>134</td>
            <td style="color:var(--danger);font-weight:700;">22</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">👁 Ver</button>
              <button class="btn btn-ghost btn-sm">✏ Editar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">S-004</td>
            <td class="td-primary">Barrio El Calvario</td>
            <td>87</td>
            <td style="color:var(--warning);font-weight:700;">8</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">👁 Ver</button>
              <button class="btn btn-ghost btn-sm">✏ Editar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">S-005</td>
            <td class="td-primary">Zona Industrial Poniente</td>
            <td>0</td>
            <td>—</td>
            <td><span class="badge badge-yellow">Inactivo</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">✏ Editar</button>
              <button class="btn btn-agua btn-sm" onclick="showToast('Sector activado','success')">Activar</button>
            </div></td>
          </tr>
        </tbody>
      </table>
    </div>

  </div><!-- /terr-sectores -->

  <!-- ── TAB 2: CASAS ────────────────────────────── -->
  <div class="tab-panel" data-panel="terr-casas" data-group="terr-tabs">

    <div class="flex-between mb-16" style="flex-wrap:wrap;gap:12px;">
      <div class="search-bar">
        <span class="search-icon">🔍</span>
        <input type="text" id="casaSearch" placeholder="Buscar código, cliente, medidor…" />
      </div>
      <div class="flex-gap">
        <select class="form-control" style="width:auto;padding:8px 12px;">
          <option>Todos los sectores</option>
          <option>Colonia Centro</option>
          <option>Comunidad Norte</option>
          <option>Las Margaritas</option>
          <option>El Calvario</option>
        </select>
        <select class="form-control" style="width:auto;padding:8px 12px;">
          <option>Todos los estados</option>
          <option>Al día</option>
          <option>Pendiente</option>
          <option>Moroso</option>
          <option>Cortado</option>
        </select>
        <button class="btn btn-agua btn-sm">+ Nueva casa</button>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Casa</th>
            <th>Cliente</th>
            <th>Sector</th>
            <th>Medidor</th>
            <th>Servicio</th>
            <th>Pago</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td-mono">H-001</td>
            <td class="td-primary">Juan Pérez</td>
            <td>Centro</td>
            <td class="td-mono">M-10023</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><span class="badge badge-green">Al día</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">Ver</button>
              <button class="btn btn-ghost btn-sm">Editar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">H-002</td>
            <td class="td-primary">Ana López</td>
            <td>Norte</td>
            <td class="td-mono">M-10024</td>
            <td><span class="badge badge-yellow">Restringido</span></td>
            <td><span class="badge badge-red">Moroso</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">Ver</button>
              <button class="btn btn-danger btn-sm" onclick="showToast('Corte programado','warning')">✂ Corte</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">H-003</td>
            <td class="td-primary">Carlos Ramírez</td>
            <td>Margaritas</td>
            <td class="td-mono">M-10025</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><span class="badge badge-yellow">Pendiente</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">Ver</button>
              <button class="btn btn-ghost btn-sm">Editar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">H-004</td>
            <td class="td-primary">María García</td>
            <td>El Calvario</td>
            <td class="td-mono">M-10026</td>
            <td><span class="badge badge-red">Cortado</span></td>
            <td><span class="badge badge-red">Moroso</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">Ver</button>
              <button class="btn btn-agua btn-sm" onclick="showToast('Reconexión registrada','success')">Reconectar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">H-005</td>
            <td class="td-primary">Luis Morales</td>
            <td>Centro</td>
            <td class="td-mono">M-10027</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><span class="badge badge-green">Al día</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">Ver</button>
              <button class="btn btn-ghost btn-sm">Editar</button>
            </div></td>
          </tr>
          <tr>
            <td class="td-mono">H-006</td>
            <td class="td-primary">Rosa Herrera</td>
            <td>Norte</td>
            <td class="td-mono">M-10028</td>
            <td><span class="badge badge-green">Activo</span></td>
            <td><span class="badge badge-green">Al día</span></td>
            <td><div class="flex-gap">
              <button class="btn btn-ghost btn-sm">Ver</button>
              <button class="btn btn-ghost btn-sm">Editar</button>
            </div></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="flex-between mt-16" style="color:var(--text-muted);font-size:.75rem;">
      <span>Mostrando 1–6 de 654 casas</span>
      <div class="flex-gap">
        <button class="btn btn-ghost btn-sm">← Anterior</button>
        <span style="color:var(--negro);font-weight:800;background:var(--celeste-xlt);padding:4px 10px;border-radius:6px;">1</span>
        <button class="btn btn-ghost btn-sm">2</button>
        <button class="btn btn-ghost btn-sm">3</button>
        <button class="btn btn-ghost btn-sm">Siguiente →</button>
      </div>
    </div>

  </div><!-- /terr-casas -->

  <!-- ── TAB 3: VISTA POR SECTOR ─────────────────── -->
  <div class="tab-panel" data-panel="terr-vista" data-group="terr-tabs">

    <!-- Selector de sector -->
    <div class="card mb-24">
      <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
          <label class="form-label" style="display:block;margin-bottom:6px;">Sector a visualizar</label>
          <select class="form-control form-select" id="sectorVista" onchange="renderSectorVista(this.value)">
            <option value="centro">S-001 — Colonia Centro (245 casas)</option>
            <option value="norte">S-002 — Comunidad Norte (188 casas)</option>
            <option value="margaritas">S-003 — Las Margaritas (134 casas)</option>
            <option value="calvario">S-004 — Barrio El Calvario (87 casas)</option>
          </select>
        </div>
        <div class="flex-gap" style="margin-top:24px;">
          <span style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--success);">■ Al día</span>
          <span style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--warning);">■ Pendiente</span>
          <span style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--danger);">■ Moroso</span>
          <span style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--text-muted);">■ Inactiva</span>
        </div>
      </div>
    </div>

    <!-- KPI resumen del sector -->
    <div class="kpi-grid mb-24" style="grid-template-columns:repeat(5,1fr);" id="sectorKpis">
      <div class="kpi-card"><div class="kpi-icon blue">🏠</div><div class="kpi-label">Total casas</div><div class="kpi-value" id="sv-total">245</div></div>
      <div class="kpi-card"><div class="kpi-icon green">✓</div><div class="kpi-label">Al día</div><div class="kpi-value" id="sv-aldia">201</div></div>
      <div class="kpi-card"><div class="kpi-icon red">⚠</div><div class="kpi-label">En mora</div><div class="kpi-value" id="sv-mora">35</div></div>
      <div class="kpi-card"><div class="kpi-icon yellow">⏸</div><div class="kpi-label">Inactivas</div><div class="kpi-value" id="sv-inact">9</div></div>
      <div class="kpi-card"><div class="kpi-icon cyan">📊</div><div class="kpi-label">Sin lectura</div><div class="kpi-value" id="sv-sinlect">12</div></div>
    </div>

    <!-- Grid de casas -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Casas del sector</span>
        <span style="font-size:.72rem;color:var(--text-muted);">Click en una casa para ver detalle</span>
      </div>
      <div id="sectorHousesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(74px,1fr));gap:8px;margin-top:12px;"></div>
    </div>

  </div><!-- /terr-vista -->

</div><!-- /view-territorio -->

<style>
/* ── House mini-card en vista por sector ── */
.house-mini {
  border-radius: 8px;
  border: 1.5px solid;
  padding: 10px 6px;
  text-align: center;
  cursor: pointer;
  transition: transform 0.15s, box-shadow 0.15s;
  font-size: 0.65rem;
  font-weight: 800;
}
.house-mini:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 2; }
.house-mini .house-code { font-size: 0.7rem; font-family: var(--font-mono); }
.house-mini.h-aldia   { background: rgba(0,137,123,0.08); border-color: var(--success); color: var(--success); }
.house-mini.h-pending { background: rgba(245,124,0,0.08);  border-color: var(--warning); color: var(--warning); }
.house-mini.h-mora    { background: rgba(198,40,40,0.08);  border-color: var(--danger);  color: var(--danger); }
.house-mini.h-inact   { background: rgba(0,0,0,0.04);      border-color: var(--gris-borde); color: var(--text-muted); }
</style>

<script>
// Datos mock por sector
const sectorData = {
  centro:     { total:245, aldia:201, mora:35, inact:9, sinlect:12 },
  norte:      { total:188, aldia:164, mora:12, inact:8, sinlect:7  },
  margaritas: { total:134, aldia:108, mora:22, inact:4, sinlect:15 },
  calvario:   { total:87,  aldia:71,  mora:8,  inact:5, sinlect:3  }
};

function renderSectorVista(sector) {
  const d = sectorData[sector] || sectorData.centro;
  document.getElementById('sv-total').textContent   = d.total;
  document.getElementById('sv-aldia').textContent   = d.aldia;
  document.getElementById('sv-mora').textContent    = d.mora;
  document.getElementById('sv-inact').textContent   = d.inact;
  document.getElementById('sv-sinlect').textContent = d.sinlect;

  // Generar mini-cards de casas
  const grid   = document.getElementById('sectorHousesGrid');
  const states = ['h-aldia','h-pending','h-mora','h-inact'];
  const labels  = { 'h-aldia':'Al día','h-pending':'Pend.','h-mora':'Mora','h-inact':'Inactiva' };
  const probs   = [0.78, 0.08, 0.10, 0.04]; // distribución aproximada
  grid.innerHTML = '';

  for (let i = 1; i <= Math.min(d.total, 80); i++) {
    const r = Math.random();
    let acc = 0, st = 'h-aldia';
    for (let j = 0; j < probs.length; j++) {
      acc += probs[j];
      if (r < acc) { st = states[j]; break; }
    }
    const card = document.createElement('div');
    card.className = 'house-mini ' + st;
    const num = String(i).padStart(3,'0');
    card.innerHTML = `<div class="house-code">H-${num}</div><div style="margin-top:3px;">${labels[st]}</div>`;
    card.title = `Casa H-${num} — ${labels[st]}`;
    card.onclick = () => showToast(`Casa H-${num}: ${labels[st]}`, st === 'h-aldia' ? 'success' : (st === 'h-mora' ? 'danger' : 'info'));
    grid.appendChild(card);
  }

  // Indicar si hay más casas
  if (d.total > 80) {
    const more = document.createElement('div');
    more.style.cssText = 'grid-column:1/-1;text-align:center;color:var(--text-muted);font-size:.75rem;padding:8px;';
    more.textContent = `… y ${d.total - 80} casas más`;
    grid.appendChild(more);
  }
}

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', () => renderSectorVista('centro'));

// Re-renderizar cuando se activa la pestaña
document.querySelectorAll('[data-panel="terr-vista"]').forEach(tab => {
  tab.addEventListener('click', () => {
    const sel = document.getElementById('sectorVista');
    if (sel) renderSectorVista(sel.value);
  });
});
</script>
