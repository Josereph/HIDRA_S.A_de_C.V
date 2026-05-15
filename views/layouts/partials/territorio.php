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
    <div class="section-tab active" data-panel="terr-sectores" data-group="terr-tabs"><i class="fas fa-globe"></i> Sectores</div>
    <div class="section-tab"        data-panel="terr-casas"    data-group="terr-tabs"><i class="fas fa-home"></i> Casas</div>
    <div class="section-tab"        data-panel="terr-vista"    data-group="terr-tabs"><i class="fas fa-map"></i> Vista por sector</div>
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
          <?php foreach($sectores_lista as $s): ?>
          <tr>
            <td class="td-mono">S-<?= str_pad($s['id_sector'], 3, '0', STR_PAD_LEFT) ?></td>
            <td class="td-primary"><?= htmlspecialchars($s['nombre_sector']) ?></td>
            <td><?= htmlspecialchars($s['total_casas']) ?></td>
            <td style="color:var(--<?= $s['en_mora'] > 0 ? 'danger' : 'text-muted' ?>);font-weight:700;"><?= htmlspecialchars($s['en_mora']) ?: '—' ?></td>
            <td>
              <span class="badge badge-<?= $s['estado'] === 'activo' ? 'green' : 'yellow' ?>">
                <?= ucfirst(htmlspecialchars($s['estado'])) ?>
              </span>
            </td>
            <td>
              <div class="flex-gap">
                <button class="btn btn-ghost btn-sm">👁 Ver</button>
                <button class="btn btn-ghost btn-sm">✏ Editar</button>
                <?php if($s['estado'] !== 'activo'): ?>
                <button class="btn btn-agua btn-sm">Activar</button>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
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
          <?php foreach($clientes_lista as $c): ?>
          <tr>
            <td class="td-mono">H-<?= str_pad($c['id_usuario'], 3, '0', STR_PAD_LEFT) ?></td>
            <td class="td-primary"><?= htmlspecialchars($c['cliente']) ?></td>
            <td><?= htmlspecialchars($c['sector']) ?></td>
            <td class="td-mono"><?= htmlspecialchars($c['numero_medidor'] ?? '—') ?></td>
            <td>
              <span class="badge badge-<?= $c['estado_medidor'] === 'activo' ? 'green' : ($c['estado_medidor'] ? 'yellow' : 'default') ?>">
                <?= ucfirst(htmlspecialchars($c['estado_medidor'] ?? 'Sin medidor')) ?>
              </span>
            </td>
            <td>
              <span class="badge badge-<?= $c['estado_usuario'] === 'activo' ? 'green' : 'red' ?>">
                <?= ucfirst(htmlspecialchars($c['estado_usuario'])) ?>
              </span>
            </td>
            <td>
              <div class="flex-gap">
                <button class="btn btn-ghost btn-sm">Ver</button>
                <button class="btn btn-ghost btn-sm">Editar</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
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
