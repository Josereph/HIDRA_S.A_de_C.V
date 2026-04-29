// ── Territorio ────────────────────────────────────────
let mapaInit = false;
function initMapa() {
  if (mapaInit) return;
  mapaInit = true;
  document.querySelectorAll('.sector-block').forEach(s => {
    s.addEventListener('click', () => { if (s.dataset.sector) showSectorDetail(s.dataset.sector, s); });
  });
}

function showSectorDetail(name, el) {
  // Highlight selected
  document.querySelectorAll('.sector-block').forEach(s => s.style.outline = '');
  if (el) el.style.outline = '3px solid var(--azul-inst)';

  const isInactive = el?.classList.contains('inactive');
  const count = el?.querySelector('.sector-count')?.textContent || '—';

  const info = document.getElementById('sectorInfo');
  if (!info) return;

  if (isInactive) {
    info.innerHTML = `
      <div class="card-header"><span class="card-title">Sector: ${name}</span><span class="badge badge-yellow">Inactivo</span></div>
      <div style="text-align:center; padding:24px 0; color:var(--text-muted);">
        <div style="font-size:2rem;margin-bottom:8px;">🔒</div>
        <div style="font-size:.82rem;">Este sector no está activo actualmente.</div>
        <button class="btn btn-primary btn-sm" style="margin-top:14px;" onclick="showToast('Sector activado','success')">⚡ Activar sector</button>
      </div>`;
    return;
  }

  const morosos = Math.floor(Math.random() * 5);
  const facturado = (parseInt(count) * 12.5).toFixed(2);

  info.innerHTML = `
    <div class="card-header"><span class="card-title">Sector: ${name}</span><span class="badge badge-green">Activo</span></div>
    <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Casas / predios</span><span class="td-mono">${count}</span></div>
    <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Al día</span><span style="color:var(--success);font-weight:700;">${parseInt(count) - morosos}</span></div>
    <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Morosos</span><span class="badge badge-red">${morosos}</span></div>
    <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Facturado (mes)</span><span class="td-mono">$${facturado}</span></div>
    <div style="margin-top:14px;">
      <div style="font-size:.7rem;color:var(--text-muted);margin-bottom:6px;">% cobranza</div>
      <div class="progress-bar-wrap"><div class="progress-bar-fill" style="width:${Math.floor(85+Math.random()*14)}%"></div></div>
    </div>
    <div class="btn-group" style="margin-top:16px;">
      <button class="btn btn-primary btn-sm" onclick="showToast('Factura generada para sector ${name}','success')">📄 Generar facturas</button>
      <button class="btn btn-ghost btn-sm" onclick="showToast('Exportando sector ${name}…','info')">↓ Exportar</button>
    </div>`;
}

