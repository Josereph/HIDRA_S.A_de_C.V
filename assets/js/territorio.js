/**
 * assets/js/territorio.js
 * Módulo de Territorio — funciona tanto en pagina_principal.php (SPA)
 * como en la ruta /territorio del router MVC.
 * Todas las funciones tienen prefijo "terr" para evitar conflictos globales.
 */

// ── Detectar ruta del API ────────────────────────────
// En pagina_principal.php se define window.TERR_API antes de este script
// En el router MVC se define window.BASE_PATH
const TERR_API = window.TERR_API
    || (window.BASE_PATH + '/api/territorio_api.php');

// ── Estado global del módulo ─────────────────────────
const TERR = {
    sectores: [],
    clientes: [],
    vivPage:  1,
    vivTotal: 0,
    vivLast:  1,
};

// ── Toast (reutiliza la función global si existe) ─────
function terrToast(msg, type = 'info') {
    if (typeof showToast === 'function') { showToast(msg, type); return; }
    const c = document.getElementById('toastContainer');
    if (!c) return;
    const el = document.createElement('div');
    el.className = `toast toast-${type} show`;
    el.innerHTML = `<span>${msg}</span><button onclick="this.parentElement.remove()">✕</button>`;
    c.appendChild(el);
    setTimeout(() => el.remove(), 4000);
}

// ── AJAX helper ───────────────────────────────────────
async function terrAPI(action, method = 'GET', body = null, params = {}) {
    // Construir URL con query string de forma segura
    const qs = new URLSearchParams({ action, ...params }).toString();
    const url = TERR_API + '?' + qs;
    const opts = { method, headers: { 'Content-Type': 'application/json' } };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch(url, opts);
    if (!res.ok) throw new Error('HTTP ' + res.status);
    return res.json();
}

// ── Escape HTML ───────────────────────────────────────
function terrEsc(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ══════════════════════════════════════════════════════
// TABS
// ══════════════════════════════════════════════════════
function terrSwitchTab(tab) {
    document.querySelectorAll('.terr-tab').forEach(b =>
        b.classList.toggle('active', b.dataset.tab === tab));
    document.querySelectorAll('.terr-panel').forEach(p =>
        p.classList.toggle('active', p.id === 'terr-panel-' + tab));

    if (tab === 'vista')    terrLoadSectoresVista();
    if (tab === 'sectores') terrLoadSectoresTable();
    if (tab === 'casas')    terrLoadCasasTable(1);
}

// ══════════════════════════════════════════════════════
// VISTA POR SECTOR — TARJETAS GRANDES
// ══════════════════════════════════════════════════════
async function terrLoadSectoresVista() {
    const grid = document.getElementById('sectorCardsGrid');
    if (!grid) return;
    try {
        const r = await terrAPI('get_sectores');
        TERR.sectores = r.data || [];
        terrRenderSectorCards(TERR.sectores);
    } catch(e) {
        grid.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error al cargar sectores</p></div>';
    }
}

function terrRenderSectorCards(sectores) {
    const grid = document.getElementById('sectorCardsGrid');
    if (!sectores.length) {
        grid.innerHTML = '<div class="empty-state"><i class="fas fa-map-marker-alt"></i><p>No hay sectores registrados.</p></div>';
        return;
    }
    grid.innerHTML = sectores.map(s => {
        const inactivo   = s.estado === 'inactivo';
        const bannerCls  = inactivo ? 'estado-inactivo' : '';
        const iconCls    = inactivo ? 'inactivo' : '';
        const total      = parseInt(s.total_viviendas) || 0;
        const activas    = parseInt(s.viviendas_activas) || 0;
        const susp       = parseInt(s.viviendas_suspendidas) || 0;
        return `
        <div class="sector-main-card" data-id="${s.id_sector}"
             onclick="terrOpenSectorSubview(${s.id_sector},'${terrEsc(s.nombre_sector)}')">
          <div class="sector-card-banner ${bannerCls}"></div>
          <div class="sector-card-body">
            <div class="sector-card-icon ${iconCls}"><i class="fas fa-map-marked-alt"></i></div>
            <div class="sector-card-name">${terrEsc(s.nombre_sector)}</div>
            <div class="sector-card-desc">${terrEsc(s.descripcion || 'Sin descripción')}</div>
            <div class="sector-card-stats">
              <div class="sector-stat-item">
                <div class="sector-stat-num">${total}</div>
                <div class="sector-stat-label">Total</div>
              </div>
              <div class="sector-stat-item">
                <div class="sector-stat-num activas">${activas}</div>
                <div class="sector-stat-label">Activas</div>
              </div>
              <div class="sector-stat-item">
                <div class="sector-stat-num suspendidas">${susp}</div>
                <div class="sector-stat-label">Susp.</div>
              </div>
            </div>
            <div class="sector-card-footer">
              <span class="badge ${inactivo ? 'badge-yellow' : 'badge-green'}">${terrEsc(s.estado)}</span>
              <span class="sector-card-cta">Ver casas <i class="fas fa-arrow-right"></i></span>
            </div>
          </div>
        </div>`;
    }).join('');
}

// ══════════════════════════════════════════════════════
// MODAL DE CASAS POR SECTOR
// ══════════════════════════════════════════════════════

/**
 * Abre el modal amplio con las mini-cards de viviendas del sector seleccionado.
 * Hace un fetch a get_viviendas_sector y renderiza el resultado dentro del modal.
 * El subview inline queda desactivado — la interacción es 100% en el modal.
 */
async function terrOpenSectorSubview(sectorId, nombre) {
    // Marcar card activa visualmente
    document.querySelectorAll('.sector-main-card').forEach(c => c.classList.remove('active-card'));
    const card = document.querySelector(`.sector-main-card[data-id="${sectorId}"]`);
    if (card) card.classList.add('active-card');

    // Abrir modal y mostrar spinner
    terrOpenModal('terrModalSectorCasas');
    document.getElementById('terrSectorCasasNombre').textContent = nombre;
    document.getElementById('terrSectorCasasConteo').textContent = '';
    document.getElementById('terrModalCasasGrid').innerHTML =
        '<div class="empty-state"><i class="fas fa-spinner fa-spin"></i><p>Cargando viviendas...</p></div>';

    try {
        const r = await terrAPI('get_viviendas_sector', 'GET', null, { sector_id: sectorId });
        const casas = r.data || [];
        document.getElementById('terrSectorCasasConteo').textContent =
            casas.length === 1 ? '1 vivienda' : `${casas.length} viviendas`;
        terrRenderMiniCasas(casas);
    } catch (e) {
        document.getElementById('terrModalCasasGrid').innerHTML =
            '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error al cargar las viviendas</p></div>';
    }
}

/** Cierra el modal de sector y limpia la selección de cards. */
function terrCloseSectorModal() {
    terrCloseModal('terrModalSectorCasas');
    document.querySelectorAll('.sector-main-card').forEach(c => c.classList.remove('active-card'));
}

/** Alias retrocompatible — por si algún botón existente llama a terrCloseSectorSubview */
function terrCloseSectorSubview() { terrCloseSectorModal(); }

/**
 * Renderiza las mini-cards de viviendas DENTRO del modal.
 * Al clicar una mini-card se abre el modal de detalle de vivienda
 * (terrOpenViviendaDetail) sin cerrar el modal padre — JS maneja
 * la pila de modales de forma independiente.
 */
function terrRenderMiniCasas(casas) {
    const grid = document.getElementById('terrModalCasasGrid');
    if (!grid) return;

    if (!casas.length) {
        grid.innerHTML = '<div class="empty-state"><i class="fas fa-home"></i><p>No hay viviendas registradas en este sector.</p></div>';
        return;
    }

    const iconCls  = { 'Activa': 'activa', 'Suspendida': 'suspendida', 'En revisión': 'revision' };
    const badgeCls = { 'Activa': 'badge-green', 'Suspendida': 'badge-red', 'En revisión': 'badge-yellow' };

    grid.innerHTML = casas.map(v => `
        <div class="mini-casa-card"
             role="button"
             tabindex="0"
             onclick="terrOpenViviendaDetail(${v.id})"
             onkeydown="if(event.key==='Enter')terrOpenViviendaDetail(${v.id})">
          <div class="mini-casa-icon ${iconCls[v.estado] || 'revision'}">
            <i class="fas fa-home"></i>
          </div>
          <div class="mini-casa-dir">${terrEsc(v.direccion)}</div>
          <div class="mini-casa-cliente">
            <i class="fas fa-user"></i>
            ${v.cliente_nombre ? terrEsc(v.cliente_nombre) : '<span style="color:var(--text-muted)">Sin asignar</span>'}
          </div>
          <span class="badge ${badgeCls[v.estado] || 'badge-yellow'} mini-casa-estado">
            ${terrEsc(v.estado)}
          </span>
        </div>`
    ).join('');
}

// ══════════════════════════════════════════════════════
// MODAL DETALLE VIVIENDA
// ══════════════════════════════════════════════════════
async function terrOpenViviendaDetail(id) {
    terrOpenModal('terrModalViviendaDetail');
    document.getElementById('terrViviendaDetailBody').innerHTML =
        '<div class="empty-state"><i class="fas fa-spinner fa-spin"></i><p>Cargando...</p></div>';
    try {
        const r = await terrAPI('get_vivienda', 'GET', null, { id });
        if (!r.success) { terrToast(r.message, 'danger'); return; }
        const v = r.data;
        const badgeCls = { 'Activa':'badge-green','Suspendida':'badge-red','En revisión':'badge-yellow' };
        const coords = (v.lat && v.lng)
            ? `<span class="coord-pill"><i class="fas fa-map-pin"></i> ${v.lat}, ${v.lng}</span>`
            : '<span style="color:var(--text-muted)">Sin coordenadas</span>';

        document.getElementById('terrViviendaDetailBody').innerHTML = `
          <div class="vivienda-detail-layout">
            <div class="vivienda-hero">
              <div class="vivienda-hero-icon"><i class="fas fa-home"></i></div>
              <div>
                <div class="vivienda-hero-dir">${terrEsc(v.direccion)}</div>
                <div class="vivienda-hero-sector"><i class="fas fa-map-marker-alt"></i> ${terrEsc(v.nombre_sector)}</div>
                <div style="margin-top:8px"><span class="badge ${badgeCls[v.estado]||'badge-yellow'}">${terrEsc(v.estado)}</span></div>
              </div>
            </div>

            <div class="detail-block">
              <div class="detail-block-title"><i class="fas fa-user"></i> Cliente</div>
              ${v.cliente_nombre ? `
                <div class="detail-row"><span class="detail-label">Nombre</span><span class="detail-val">${terrEsc(v.cliente_nombre)}</span></div>
                <div class="detail-row"><span class="detail-label">Código</span><span class="detail-val mono">${terrEsc(v.codigo_usuario||'—')}</span></div>
                <div class="detail-row"><span class="detail-label">Teléfono</span><span class="detail-val">${terrEsc(v.cliente_tel||'—')}</span></div>
                <div class="detail-row"><span class="detail-label">Correo</span><span class="detail-val">${terrEsc(v.cliente_correo||'—')}</span></div>
              ` : '<div class="detail-row"><span class="detail-val" style="color:var(--text-muted)">Sin cliente asignado</span></div>'}
            </div>

            <div class="detail-block">
              <div class="detail-block-title"><i class="fas fa-tachometer-alt"></i> Medidor</div>
              ${v.numero_medidor ? `
                <div class="detail-row"><span class="detail-label">N° Medidor</span><span class="detail-val mono">${terrEsc(v.numero_medidor)}</span></div>
                <div class="detail-row"><span class="detail-label">Marca</span><span class="detail-val">${terrEsc(v.medidor_marca||'—')}</span></div>
                <div class="detail-row"><span class="detail-label">Estado</span><span class="detail-val">${terrEsc(v.medidor_estado||'—')}</span></div>
              ` : '<div class="detail-row"><span class="detail-val" style="color:var(--text-muted)">Sin medidor</span></div>'}
            </div>

            <div class="detail-block" style="grid-column:1/-1">
              <div class="detail-block-title"><i class="fas fa-map"></i> Ubicación</div>
              <div class="detail-row"><span class="detail-label">Coordenadas</span>${coords}</div>
              <div class="detail-row"><span class="detail-label">Registrada</span>
                <span class="detail-val">${v.fecha_creacion ? v.fecha_creacion.substring(0,10) : '—'}</span></div>
            </div>
          </div>`;

        document.getElementById('terrDetailEditBtn').onclick   = () => { terrCloseModal('terrModalViviendaDetail'); terrOpenEditVivienda(v.id); };
        document.getElementById('terrDetailDeleteBtn').onclick = () => { terrCloseModal('terrModalViviendaDetail'); terrConfirmDeleteVivienda(v.id, v.direccion); };
    } catch(e) {
        document.getElementById('terrViviendaDetailBody').innerHTML =
            '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error al cargar datos</p></div>';
    }
}

// ══════════════════════════════════════════════════════
// SECTORES CRUD
// ══════════════════════════════════════════════════════
async function terrLoadSectoresTable() {
    const tbody = document.getElementById('terrSectoresTableBody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>';
    try {
        const r = await terrAPI('get_sectores');
        TERR.sectores = r.data || [];
        tbody.innerHTML = TERR.sectores.length ? TERR.sectores.map(s => `
            <tr data-id="${s.id_sector}">
              <td class="td-mono">S-${String(s.id_sector).padStart(3,'0')}</td>
              <td class="td-primary">${terrEsc(s.nombre_sector)}</td>
              <td style="color:var(--text-muted);font-size:.8rem">${terrEsc(s.descripcion||'—')}</td>
              <td>${s.total_viviendas||0}</td>
              <td><span class="badge ${s.estado==='activo'?'badge-green':'badge-yellow'}">${terrEsc(s.estado)}</span></td>
              <td>
                <div class="table-actions">
                  <button class="btn btn-secondary btn-sm" onclick="terrOpenEditSector(${s.id_sector})"><i class="fas fa-edit"></i> Editar</button>
                  <button class="btn btn-danger btn-sm" onclick="terrConfirmDeleteSector(${s.id_sector},'${terrEsc(s.nombre_sector)}')"><i class="fas fa-trash"></i></button>
                </div>
              </td>
            </tr>`).join('') :
            '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No hay sectores</td></tr>';
    } catch(e) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--danger)">Error al cargar</td></tr>';
    }
}

function terrOpenNewSector() {
    document.getElementById('terrSectorModalTitle').textContent = 'Nuevo Sector';
    document.getElementById('terrSectorForm').reset();
    document.getElementById('terrSectorId').value = '';
    terrOpenModal('terrModalSector');
}

async function terrOpenEditSector(id) {
    const s = TERR.sectores.find(x => x.id_sector == id);
    if (!s) { terrToast('Recargando datos...','info'); await terrLoadSectoresTable(); return; }
    document.getElementById('terrSectorModalTitle').textContent = 'Editar Sector';
    document.getElementById('terrSectorId').value      = s.id_sector;
    document.getElementById('terrSectorNombre').value  = s.nombre_sector;
    document.getElementById('terrSectorDesc').value    = s.descripcion || '';
    document.getElementById('terrSectorEstado').value  = s.estado;
    terrOpenModal('terrModalSector');
}

async function terrSaveSector() {
    const id     = document.getElementById('terrSectorId').value;
    const nombre = document.getElementById('terrSectorNombre').value.trim();
    if (!nombre) { terrToast('El nombre es obligatorio', 'warning'); return; }
    const body = {
        id_sector:     id || undefined,
        nombre_sector: nombre,
        descripcion:   document.getElementById('terrSectorDesc').value.trim(),
        estado:        document.getElementById('terrSectorEstado').value,
    };
    try {
        const r = await terrAPI(id ? 'update_sector' : 'create_sector', 'POST', body);
        if (r.success) {
            terrToast(r.message, 'success');
            terrCloseModal('terrModalSector');
            terrLoadSectoresTable();
            TERR.sectores = []; // invalidar caché
        } else {
            terrToast(r.message, 'danger');
        }
    } catch(e) { terrToast('Error de conexión', 'danger'); }
}

function terrConfirmDeleteSector(id, nombre) {
    if (!confirm(`¿Eliminar el sector "${nombre}"?\nEsta acción no se puede deshacer.`)) return;
    terrAPI('delete_sector', 'POST', { id })
        .then(r => {
            terrToast(r.message, r.success ? 'success' : 'danger');
            if (r.success) { terrLoadSectoresTable(); TERR.sectores = []; }
        })
        .catch(() => terrToast('Error de conexión', 'danger'));
}

// ══════════════════════════════════════════════════════
// VIVIENDAS CRUD + PAGINACIÓN
// ══════════════════════════════════════════════════════
async function terrLoadCasasTable(page = 1) {
    TERR.vivPage = page;
    const tbody = document.getElementById('terrCasasTableBody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>';
    try {
        const r = await terrAPI('get_viviendas', 'GET', null, { page });
        TERR.vivTotal = r.total || 0;
        TERR.vivLast  = r.last_page || 1;

        const badgeCls = { 'Activa':'badge-green','Suspendida':'badge-red','En revisión':'badge-yellow' };
        tbody.innerHTML = (r.data||[]).length ? (r.data).map(v => `
            <tr>
              <td class="td-primary">${terrEsc(v.direccion)}</td>
              <td>${terrEsc(v.nombre_sector)}</td>
              <td>${v.cliente_nombre ? terrEsc(v.cliente_nombre) : '<span style="color:var(--text-muted)">Sin asignar</span>'}</td>
              <td><span class="badge ${badgeCls[v.estado]||'badge-yellow'}">${terrEsc(v.estado)}</span></td>
              <td class="td-mono">${v.lat ? v.lat+','+v.lng : '—'}</td>
              <td>
                <div class="table-actions">
                  <button class="btn btn-secondary btn-sm" onclick="terrOpenEditVivienda(${v.id})"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-danger btn-sm" onclick="terrConfirmDeleteVivienda(${v.id},'${terrEsc(v.direccion)}')"><i class="fas fa-trash"></i></button>
                </div>
              </td>
            </tr>`).join('') :
            '<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No hay viviendas</td></tr>';

        terrRenderPaginacion();
    } catch(e) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--danger)">Error al cargar</td></tr>';
    }
}

function terrRenderPaginacion() {
    const info = document.getElementById('terrVivPagInfo');
    const btns = document.getElementById('terrVivPagBtns');
    if (!info || !btns) return;
    const from = TERR.vivTotal ? ((TERR.vivPage - 1) * 10) + 1 : 0;
    const to   = Math.min(TERR.vivPage * 10, TERR.vivTotal);
    info.textContent = TERR.vivTotal ? `Mostrando ${from}–${to} de ${TERR.vivTotal} viviendas` : 'Sin registros';

    let html = `<button class="page-btn" onclick="terrLoadCasasTable(${TERR.vivPage-1})" ${TERR.vivPage<=1?'disabled':''}>‹ Ant.</button>`;
    for (let p = 1; p <= TERR.vivLast; p++) {
        if (TERR.vivLast > 7 && Math.abs(p - TERR.vivPage) > 2 && p !== 1 && p !== TERR.vivLast) {
            if (p === TERR.vivPage - 3 || p === TERR.vivPage + 3)
                html += '<span class="page-btn" style="cursor:default">…</span>';
            continue;
        }
        html += `<button class="page-btn ${p===TERR.vivPage?'active':''}" onclick="terrLoadCasasTable(${p})">${p}</button>`;
    }
    html += `<button class="page-btn" onclick="terrLoadCasasTable(${TERR.vivPage+1})" ${TERR.vivPage>=TERR.vivLast?'disabled':''}>Sig. ›</button>`;
    btns.innerHTML = html;
}

/**
 * Consulta los sectores activos en tiempo real y reconstruye
 * el <select id="terrViviendaSector"> justo antes de abrir el modal.
 * Nunca usa caché: garantiza que eliminaciones y altas se reflejen al instante.
 *
 * @param {string|number} [selectedId] — ID a preseleccionar (edición)
 * @returns {Promise<boolean>} true si se cargaron sectores, false si hubo error
 */
async function terrRefreshSectoresSelect(selectedId = null) {
    const sel = document.getElementById('terrViviendaSector');
    if (!sel) return false;

    // Estado de carga visual
    sel.disabled = true;
    sel.innerHTML = '<option value="">⟳ Cargando sectores...</option>';

    try {
        const r = await terrAPI('get_sectores_select');
        const sectores = r.data || [];

        if (!sectores.length) {
            sel.innerHTML = '<option value="">— Sin sectores activos —</option>';
            sel.disabled = false;
            return false;
        }

        // Reconstruir opciones con datos frescos de la BD
        sel.innerHTML =
            '<option value="">— Seleccionar sector —</option>' +
            sectores.map(s =>
                `<option value="${s.id_sector}"${String(s.id_sector) === String(selectedId) ? ' selected' : ''}>${terrEsc(s.nombre_sector)}</option>`
            ).join('');

        sel.disabled = false;
        return true;
    } catch (e) {
        sel.innerHTML = '<option value="">Error al cargar sectores</option>';
        sel.disabled = false;
        terrToast('No se pudieron cargar los sectores', 'danger');
        return false;
    }
}

async function terrOpenNewVivienda() {
    // Primero refrescar el select de sectores con datos actuales de BD
    await terrRefreshSectoresSelect();

    document.getElementById('terrViviendaModalTitle').textContent = 'Nueva Vivienda';
    // Reset manual para no sobreescribir el select que acabamos de llenar
    document.getElementById('terrViviendaId').value      = '';
    document.getElementById('terrViviendaDir').value     = '';
    document.getElementById('terrViviendaCliente').value = '';
    document.getElementById('terrViviendaEstado').value  = 'En revisión';
    document.getElementById('terrViviendaLat').value     = '';
    document.getElementById('terrViviendaLng').value     = '';
    terrOpenModal('terrModalVivienda');
}

async function terrOpenEditVivienda(id) {
    try {
        // Cargar vivienda y sectores frescos en paralelo para mayor rapidez
        const [rViv] = await Promise.all([
            terrAPI('get_vivienda', 'GET', null, { id }),
            terrRefreshSectoresSelect(null), // carga sin preselección
        ]);

        if (!rViv.success) { terrToast(rViv.message, 'danger'); return; }
        const v = rViv.data;

        // Una vez cargado el select, preseleccionar el sector de esta vivienda
        await terrRefreshSectoresSelect(v.sector_id);

        document.getElementById('terrViviendaModalTitle').textContent = 'Editar Vivienda';
        document.getElementById('terrViviendaId').value      = v.id;
        document.getElementById('terrViviendaDir').value     = v.direccion;
        document.getElementById('terrViviendaCliente').value = v.cliente_id || '';
        document.getElementById('terrViviendaEstado').value  = v.estado;
        document.getElementById('terrViviendaLat').value     = v.lat || '';
        document.getElementById('terrViviendaLng').value     = v.lng || '';
        terrOpenModal('terrModalVivienda');
    } catch(e) { terrToast('Error al cargar la vivienda', 'danger'); }
}

async function terrSaveVivienda() {
    const id  = document.getElementById('terrViviendaId').value;
    const dir = document.getElementById('terrViviendaDir').value.trim();
    const sec = document.getElementById('terrViviendaSector').value;
    if (!dir || !sec) { terrToast('Dirección y sector son obligatorios', 'warning'); return; }
    const body = {
        id:         id || undefined,
        sector_id:  sec,
        direccion:  dir,
        cliente_id: document.getElementById('terrViviendaCliente').value || null,
        estado:     document.getElementById('terrViviendaEstado').value,
        lat:        document.getElementById('terrViviendaLat').value || null,
        lng:        document.getElementById('terrViviendaLng').value || null,
    };
    try {
        const r = await terrAPI(id ? 'update_vivienda' : 'create_vivienda', 'POST', body);
        if (r.success) {
            terrToast(r.message, 'success');
            terrCloseModal('terrModalVivienda');
            terrLoadCasasTable(TERR.vivPage);
        } else {
            terrToast(r.message, 'danger');
        }
    } catch(e) { terrToast('Error de conexión', 'danger'); }
}

function terrConfirmDeleteVivienda(id, dir) {
    if (!confirm(`¿Eliminar la vivienda "${dir}"?`)) return;
    terrAPI('delete_vivienda', 'POST', { id })
        .then(r => {
            terrToast(r.message, r.success ? 'success' : 'danger');
            if (r.success) terrLoadCasasTable(TERR.vivPage);
        })
        .catch(() => terrToast('Error de conexión', 'danger'));
}

// ══════════════════════════════════════════════════════
// MODAL HELPERS
// ══════════════════════════════════════════════════════
function terrOpenModal(id) {
    const ov = document.getElementById(id);
    if (!ov) return;
    ov.style.display = 'flex';
    setTimeout(() => ov.classList.add('visible'), 10);
}
function terrCloseModal(id) {
    const ov = document.getElementById(id);
    if (!ov) return;
    ov.classList.remove('visible');
    setTimeout(() => { ov.style.display = 'none'; }, 300);
}

// Cerrar modales al hacer clic en el backdrop (overlay)
document.addEventListener('click', e => {
    if (!e.target.classList.contains('modal-overlay')) return;
    // Solo modales del módulo Territorio
    if (e.target.id.startsWith('terr')) {
        // El modal de casas también limpia la selección de cards
        if (e.target.id === 'terrModalSectorCasas') {
            terrCloseSectorModal();
        } else {
            terrCloseModal(e.target.id);
        }
    }
});

// ── Inicialización ────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Cargar vista por sector si el tab está activo
    const activeTab = document.querySelector('.terr-tab.active');
    if (activeTab) terrSwitchTab(activeTab.dataset.tab || 'vista');
});
