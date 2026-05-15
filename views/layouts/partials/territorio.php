<!-- ══════════════════════════════════
     VISTA: TERRITORIO
══════════════════════════════════ -->
<?php
/** @var array $sectores_lista */
/** @var array $clientes_lista */
?>

<!-- Panel de detalle de cliente (slide-over) -->
<div class="detalle-overlay" id="detalleOverlay">
    <div class="detalle-panel" id="detallePanel">

        <div class="detalle-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="detalle-avatar" id="det-avatar">--</div>
                <div>
                    <div class="detalle-nombre" id="det-nombre">Cargando...</div>
                    <div class="detalle-meta" id="det-meta"></div>
                </div>
            </div>
            <button class="detalle-cerrar" id="detalleCerrar" title="Cerrar">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="detalle-body" id="detalleBody">
            <div class="detalle-cargando" id="detCargando">
                <div class="detalle-spinner"></div>
                <span>Cargando datos del cliente...</span>
            </div>

            <!-- Contenido dinámico -->
            <div id="detContenido" style="display:none;">

                <!-- KPIs del cliente -->
                <div class="det-kpis" id="detKpis"></div>

                <!-- Información general -->
                <div class="det-seccion">
                    <div class="det-seccion-titulo"><i class="fas fa-user"></i> Información</div>
                    <div class="det-info-grid" id="detInfo"></div>
                </div>

                <!-- Facturas -->
                <div class="det-seccion">
                    <div class="det-seccion-titulo"><i class="fas fa-file-invoice-dollar"></i> Facturas</div>
                    <div class="det-tabla-wrap" id="detFacturas"></div>
                </div>

                <!-- Pagos realizados -->
                <div class="det-seccion">
                    <div class="det-seccion-titulo"><i class="fas fa-money-bill-wave"></i> Pagos realizados</div>
                    <div id="detPagos"></div>
                </div>

                <!-- Mora -->
                <div class="det-seccion" id="detMoraSeccion" style="display:none;">
                    <div class="det-seccion-titulo"><i class="fas fa-exclamation-circle"></i> Mora</div>
                    <div id="detMora"></div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="view" id="view-territorio">

    <div class="page-header">
        <div>
            <h1 class="page-title">Territorio</h1>
            <p class="page-subtitle">Sectores, casas e inmuebles del servicio</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-ghost btn-sm" onclick="showToast('Exportando…','info')">↓ Exportar</button>
            <button class="btn btn-primary btn-sm" onclick="showToast('Abrir formulario de nuevo sector/casa','info')">+
                Nuevo</button>
        </div>
    </div>

    <div class="section-tabs" data-group="terr-tabs">
        <div class="section-tab active" data-panel="terr-sectores" data-group="terr-tabs"><i class="fas fa-globe"></i>
            Sectores</div>
        <div class="section-tab" data-panel="terr-casas" data-group="terr-tabs"><i class="fas fa-home"></i> Casas</div>
        <div class="section-tab" data-panel="terr-vista" data-group="terr-tabs"><i class="fas fa-map"></i> Vista por
            sector</div>
    </div>

    <!-- ── TAB 1: SECTORES ─────────────────────────── -->
    <div class="tab-panel active" data-panel="terr-sectores" data-group="terr-tabs">

        <div class="flex-between mb-16" style="flex-wrap:wrap;gap:12px;">
            <div class="search-bar">
                <span class="search-icon"><i class="fas fa-search"></i></span>
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
                        <td style="color:var(--<?= $s['en_mora'] > 0 ? 'danger' : 'text-muted' ?>);font-weight:700;">
                            <?= htmlspecialchars($s['en_mora']) ?: '—' ?></td>
                        <td>
                            <span class="badge badge-<?= $s['estado'] === 'activo' ? 'green' : 'yellow' ?>">
                                <?= ucfirst(htmlspecialchars($s['estado'])) ?>
                            </span>
                        </td>
                        <td>
                            <div class="flex-gap">
                                <button class="btn btn-ghost btn-sm"><i class="fas fa-eye"></i> Ver</button>
                                <button class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i> Editar</button>
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
                <span class="search-icon"><i class="fas fa-search"></i></span>
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
                            <span
                                class="badge badge-<?= $c['estado_medidor'] === 'activo' ? 'green' : ($c['estado_medidor'] ? 'yellow' : 'default') ?>">
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
                                <button class="btn btn-ghost btn-sm"
                                    onclick="abrirDetalleCliente(<?= $c['id_usuario'] ?>)"><i class="fas fa-eye"></i>
                                    Ver</button>
                                <button class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i> Editar</button>
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
                <span
                    style="color:var(--negro);font-weight:800;background:var(--celeste-xlt);padding:4px 10px;border-radius:6px;">1</span>
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
                    <span
                        style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--success);">■
                        Al día</span>
                    <span
                        style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--warning);">■
                        Pendiente</span>
                    <span
                        style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--danger);">■
                        Moroso</span>
                    <span
                        style="display:flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:var(--text-muted);">■
                        Inactiva</span>
                </div>
            </div>
        </div>

        <!-- KPI resumen del sector -->
        <div class="kpi-grid mb-24" style="grid-template-columns:repeat(5,1fr);" id="sectorKpis">
            <div class="kpi-card">
                <div class="kpi-icon blue"><i class="fas fa-home"></i></div>
                <div class="kpi-label">Total casas</div>
                <div class="kpi-value" id="sv-total">245</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="kpi-label">Al día</div>
                <div class="kpi-value" id="sv-aldia">201</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="kpi-label">En mora</div>
                <div class="kpi-value" id="sv-mora">35</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon yellow"><i class="fas fa-pause-circle"></i></div>
                <div class="kpi-label">Inactivas</div>
                <div class="kpi-value" id="sv-inact">9</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon cyan"><i class="fas fa-tachometer-alt"></i></div>
                <div class="kpi-label">Sin lectura</div>
                <div class="kpi-value" id="sv-sinlect">12</div>
            </div>
        </div>

        <!-- Grid de casas -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Casas del sector</span>
                <span style="font-size:.72rem;color:var(--text-muted);">Click en una casa para ver detalle</span>
            </div>
            <div id="sectorHousesGrid"
                style="display:grid;grid-template-columns:repeat(auto-fill,minmax(74px,1fr));gap:8px;margin-top:12px;">
            </div>
        </div>

    </div><!-- /terr-vista -->

</div><!-- /view-territorio -->

<style>
.house-mini { border-radius: 8px; border: 1.5px solid; padding: 10px 6px; text-align: center; cursor: pointer; font-size: .65rem; font-weight: 800; transition: transform .15s, box-shadow .15s; }
.house-mini:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(0,0,0,.15); z-index: 2; }
.house-mini .house-code { font-size: .7rem; font-family: var(--font-mono); }
.house-mini.h-aldia { background: rgba(0,137,123,.08); border-color: var(--success); color: var(--success); }
.house-mini.h-pending { background: rgba(245,124,0,.08); border-color: var(--warning); color: var(--warning); }
.house-mini.h-mora { background: rgba(198,40,40,.08); border-color: var(--danger); color: var(--danger); }
.house-mini.h-inact { background: rgba(0,0,0,.04); border-color: var(--gris-borde); color: var(--text-muted); }

/* ═══ PANEL DETALLE — MODAL CENTRADO ═══ */
.detalle-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    z-index: 250; align-items: center; justify-content: center;
}
.detalle-overlay.visible { display: flex; animation: fadeIn .2s ease both; }
@keyframes fadeIn { from { opacity: 0 } to { opacity: 1 } }

.detalle-panel {
    width: 580px; max-width: 94vw; max-height: 90vh;
    background: #fff; border-radius: 14px;
    display: flex; flex-direction: column;
    transform: scale(.95); opacity: 0;
    transition: transform .3s cubic-bezier(.16,1,.3,1), opacity .2s ease;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    overflow: hidden;
}
.detalle-overlay.visible .detalle-panel { transform: scale(1); opacity: 1; }

/* Header limpio */
.detalle-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 28px; background: #fff; border-bottom: 2px solid #66B3FF;
}
.detalle-avatar {
    width: 46px; height: 46px; border-radius: 50%; background: #66B3FF;
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: .9rem; font-family: var(--font-mono);
}
.detalle-nombre { font-size: 1.05rem; font-weight: 800; color: #000; }
.detalle-meta { font-size: .7rem; color: #8fa3b8; margin-top: 2px; }
.detalle-cerrar {
    width: 34px; height: 34px; border-radius: 50%; border: 1.5px solid #e8eef4;
    cursor: pointer; background: #f7fafd; color: #8fa3b8;
    font-size: .85rem; display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}
.detalle-cerrar:hover { background: #e8eef4; color: #000; }

.detalle-body { flex: 1; overflow-y: auto; padding: 0; }

/* Spinner de carga */
.detalle-cargando {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 14px; padding: 80px 0; color: #999; font-size: .82rem; font-weight: 600;
}
.detalle-spinner {
    width: 36px; height: 36px;
    border: 3px solid rgba(102,179,255,.15); border-top-color: #66B3FF;
    border-radius: 50%; animation: spin .65s linear infinite;
}

/* ── KPIs del cliente ── */
.det-kpis {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 0;
    background: #f7fafd; border-bottom: 1.5px solid #e8eef4;
}
.det-kpi {
    padding: 20px 16px; text-align: center;
    border-right: 1.5px solid #e8eef4;
    transition: background .2s;
}
.det-kpi:last-child { border-right: none; }
.det-kpi:hover { background: #eef5fc; }
.det-kpi-valor {
    font-size: 1.6rem; font-weight: 900; color: #000;
    font-family: var(--font-mono); line-height: 1;
}
.det-kpi-label {
    font-size: .6rem; font-weight: 800; color: #8fa3b8;
    text-transform: uppercase; letter-spacing: 1.2px; margin-top: 6px;
}
.det-kpi.alerta { background: #fef5f5; }
.det-kpi.alerta .det-kpi-valor { color: #c62828; }
.det-kpi.alerta .det-kpi-label { color: #e57373; }

/* ── Secciones ── */
.det-seccion { padding: 20px 28px; border-bottom: 1.5px solid #f0f3f7; }
.det-seccion:last-of-type { border-bottom: none; }
.det-seccion-titulo {
    font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px;
    color: #000; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
}
.det-seccion-titulo i { color: #66B3FF; font-size: .75rem; }

/* ── Info grid ── */
.det-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 20px; }
.det-info-item {
    display: flex; flex-direction: column; gap: 3px;
    padding: 10px 12px; background: #f8fafc; border-radius: 8px;
    border: 1px solid #eef2f6;
}
.det-info-label { font-size: .58rem; font-weight: 800; color: #8fa3b8; text-transform: uppercase; letter-spacing: .8px; }
.det-info-valor { font-size: .82rem; font-weight: 700; color: #000; }

/* ── Tabla de facturas ── */
.det-tabla-wrap {
    border-radius: 10px; border: 1.5px solid #e8eef4;
    overflow: hidden;
}
.det-tabla-wrap table { margin: 0; font-size: .78rem; }
.det-tabla-wrap thead { background: #000; }
.det-tabla-wrap th { font-size: .6rem; padding: 10px 12px; color: #fff; }
.det-tabla-wrap td { padding: 10px 12px; border-color: #f0f3f7; }
.det-tabla-wrap .td-mono { color: #66B3FF; font-size: .72rem; }

/* ── Timeline de pagos ── */
.det-pagos-timeline { position: relative; padding-left: 22px; }
.det-pagos-timeline::before {
    content: ''; position: absolute; left: 9px; top: 8px; bottom: 8px;
    width: 2px; background: linear-gradient(to bottom, #66B3FF, #e8eef4);
    border-radius: 2px;
}
.det-pago-item {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 14px 0; position: relative;
}
.det-pago-item + .det-pago-item { border-top: none; }
.det-pago-dot {
    width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .55rem; position: relative; z-index: 1;
    margin-left: -22px;
}
.det-pago-dot.ok { background: #fff; border: 2.5px solid #66B3FF; color: #66B3FF; }
.det-pago-dot.anulado { background: #fff; border: 2.5px solid #c62828; color: #c62828; }
.det-pago-info { flex: 1; }
.det-pago-titulo { font-size: .78rem; font-weight: 700; color: #000; }
.det-pago-meta { font-size: .68rem; color: #8fa3b8; margin-top: 2px; }
.det-pago-monto {
    font-size: .85rem; font-weight: 900; font-family: var(--font-mono);
    color: #000; background: #f0f7ff; padding: 4px 10px; border-radius: 6px;
    border: 1px solid rgba(102,179,255,.2);
}

/* ── Mora ── */
.det-mora-card {
    background: linear-gradient(135deg, #fff5f5, #ffeaea);
    border: 1.5px solid #f5c6c6; border-radius: 10px;
    padding: 18px; display: flex; align-items: center; gap: 16px;
}
.det-mora-icono {
    width: 46px; height: 46px; border-radius: 50%;
    background: linear-gradient(135deg, #c62828, #e53935);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    color: #fff; font-size: 1rem;
    box-shadow: 0 4px 14px rgba(198,40,40,.25);
}
.det-mora-texto { flex: 1; }
.det-mora-valor { font-size: 1.4rem; font-weight: 900; color: #c62828; font-family: var(--font-mono); }
.det-mora-desc { font-size: .7rem; color: #999; margin-top: 3px; font-weight: 600; }

.det-vacio {
    text-align: center; padding: 30px; color: #b0bec5; font-size: .8rem; font-weight: 600;
}
.det-vacio i { display: block; font-size: 1.4rem; margin-bottom: 8px; color: #dce4ea; }

/* ── Acciones ── */
.det-acciones {
    display: flex; gap: 10px; padding: 20px 28px;
    background: #f7fafd; border-top: 1.5px solid #e8eef4;
}
.det-acciones .btn {
    flex: 1; padding: 12px 16px; font-size: .78rem; font-weight: 800;
    border-radius: 8px; letter-spacing: .3px;
}
.det-acciones .btn-agua {
    background: linear-gradient(135deg, #66B3FF, #3d8fdb);
    color: #fff; border: none;
    box-shadow: 0 4px 14px rgba(102,179,255,.3);
}
.det-acciones .btn-agua:hover { box-shadow: 0 6px 20px rgba(102,179,255,.45); transform: translateY(-1px); }
.det-acciones .btn-primary {
    background: #000; color: #fff; border: none;
}
.det-acciones .btn-primary:hover { background: #1a1a1a; transform: translateY(-1px); }
</style>

<script>
// ── Datos mock por sector ──
const sectorData = {
    centro: {
        total: 245,
        aldia: 201,
        mora: 35,
        inact: 9,
        sinlect: 12
    },
    norte: {
        total: 188,
        aldia: 164,
        mora: 12,
        inact: 8,
        sinlect: 7
    },
    margaritas: {
        total: 134,
        aldia: 108,
        mora: 22,
        inact: 4,
        sinlect: 15
    },
    calvario: {
        total: 87,
        aldia: 71,
        mora: 8,
        inact: 5,
        sinlect: 3
    }
};

// IDs reales de clientes para mapear casas
const clienteIds = <?= json_encode(array_column($clientes_lista, 'id_usuario')) ?>;

function renderSectorVista(sector) {
    const d = sectorData[sector] || sectorData.centro;
    document.getElementById('sv-total').textContent = d.total;
    document.getElementById('sv-aldia').textContent = d.aldia;
    document.getElementById('sv-mora').textContent = d.mora;
    document.getElementById('sv-inact').textContent = d.inact;
    document.getElementById('sv-sinlect').textContent = d.sinlect;

    const grid = document.getElementById('sectorHousesGrid');
    const states = ['h-aldia', 'h-pending', 'h-mora', 'h-inact'];
    const labels = {
        'h-aldia': 'Al día',
        'h-pending': 'Pend.',
        'h-mora': 'Mora',
        'h-inact': 'Inactiva'
    };
    const probs = [0.78, 0.08, 0.10, 0.04];
    grid.innerHTML = '';

    for (let i = 1; i <= Math.min(d.total, 80); i++) {
        const r = Math.random();
        let acc = 0,
            st = 'h-aldia';
        for (let j = 0; j < probs.length; j++) {
            acc += probs[j];
            if (r < acc) {
                st = states[j];
                break;
            }
        }
        const card = document.createElement('div');
        card.className = 'house-mini ' + st;
        const num = String(i).padStart(3, '0');
        const idCliente = clienteIds[(i - 1) % clienteIds.length] || null;
        card.innerHTML = `<div class="house-code">H-${num}</div><div style="margin-top:3px;">${labels[st]}</div>`;
        card.title = `Casa H-${num} — ${labels[st]}`;
        card.onclick = () => abrirDetalleDemo(i - 1);
        grid.appendChild(card);
    }

    if (d.total > 80) {
        const more = document.createElement('div');
        more.style.cssText = 'grid-column:1/-1;text-align:center;color:var(--text-muted);font-size:.75rem;padding:8px;';
        more.textContent = `… y ${d.total - 80} casas más`;
        grid.appendChild(more);
    }
}

document.addEventListener('DOMContentLoaded', () => renderSectorVista('centro'));
document.querySelectorAll('[data-panel="terr-vista"]').forEach(tab => {
    tab.addEventListener('click', () => {
        const sel = document.getElementById('sectorVista');
        if (sel) renderSectorVista(sel.value);
    });
});
// ── Panel de detalle de cliente ──
const detalleOverlay = document.getElementById('detalleOverlay');
const detalleCerrar = document.getElementById('detalleCerrar');

function cerrarDetalle() {
    const panel = document.getElementById('detallePanel');
    panel.style.transform = 'scale(.95)';
    panel.style.opacity = '0';
    setTimeout(() => {
        detalleOverlay.classList.remove('visible');
        panel.style.transform = '';
        panel.style.opacity = '';
    }, 250);
}
detalleCerrar?.addEventListener('click', cerrarDetalle);
detalleOverlay?.addEventListener('click', e => {
    if (e.target === detalleOverlay) cerrarDetalle();
});

// Datos demo para cuando no hay clientes en BD
const datosDemo = [{
        cliente: {
            id_usuario: 1,
            nombres: 'Carlos',
            apellidos: 'Martínez López',
            nombre_completo: 'Carlos Martínez López',
            codigo_usuario: 'USR-001',
            dui: '04523678-9',
            sector: 'Sector Centro',
            tipo_usuario: 'Residencial',
            numero_medidor: 'MED-2024-001',
            estado: 'activo',
            direccion: 'Col. Centro, Calle Principal #12, Cabañas'
        },
        facturas: [{
                id_factura: 1,
                numero_factura: 'FAC-2026-0001',
                mes: 1,
                anio: 2026,
                total: '18.50',
                saldo_pendiente: '0.00',
                estado: 'pagada'
            },
            {
                id_factura: 2,
                numero_factura: 'FAC-2026-0002',
                mes: 2,
                anio: 2026,
                total: '22.30',
                saldo_pendiente: '0.00',
                estado: 'pagada'
            },
            {
                id_factura: 3,
                numero_factura: 'FAC-2026-0003',
                mes: 3,
                anio: 2026,
                total: '19.75',
                saldo_pendiente: '19.75',
                estado: 'pendiente'
            },
            {
                id_factura: 4,
                numero_factura: 'FAC-2026-0004',
                mes: 4,
                anio: 2026,
                total: '25.10',
                saldo_pendiente: '25.10',
                estado: 'vencida'
            }
        ],
        pagos: [{
                numero_factura: 'FAC-2026-0001',
                fecha_pago: '2026-01-28',
                monto_pagado: '18.50',
                metodo_pago: 'efectivo',
                referencia: null,
                estado_pago: 'registrado'
            },
            {
                numero_factura: 'FAC-2026-0002',
                fecha_pago: '2026-02-25',
                monto_pagado: '22.30',
                metodo_pago: 'transferencia',
                referencia: 'TRF-8842',
                estado_pago: 'registrado'
            }
        ],
        mora: {
            facturas_vencidas: 1,
            deuda_total: '44.85'
        }
    },
    {
        cliente: {
            id_usuario: 2,
            nombres: 'María',
            apellidos: 'González Rivas',
            nombre_completo: 'María González Rivas',
            codigo_usuario: 'USR-002',
            dui: '05134782-3',
            sector: 'Sector Norte',
            tipo_usuario: 'Residencial',
            numero_medidor: 'MED-2024-002',
            estado: 'activo',
            direccion: 'Com. Norte, Pasaje Los Almendros #5, Cabañas'
        },
        facturas: [{
                id_factura: 5,
                numero_factura: 'FAC-2026-0005',
                mes: 1,
                anio: 2026,
                total: '15.20',
                saldo_pendiente: '0.00',
                estado: 'pagada'
            },
            {
                id_factura: 6,
                numero_factura: 'FAC-2026-0006',
                mes: 2,
                anio: 2026,
                total: '16.80',
                saldo_pendiente: '0.00',
                estado: 'pagada'
            },
            {
                id_factura: 7,
                numero_factura: 'FAC-2026-0007',
                mes: 3,
                anio: 2026,
                total: '14.50',
                saldo_pendiente: '0.00',
                estado: 'pagada'
            }
        ],
        pagos: [{
                numero_factura: 'FAC-2026-0005',
                fecha_pago: '2026-01-20',
                monto_pagado: '15.20',
                metodo_pago: 'efectivo',
                referencia: null,
                estado_pago: 'registrado'
            },
            {
                numero_factura: 'FAC-2026-0006',
                fecha_pago: '2026-02-18',
                monto_pagado: '16.80',
                metodo_pago: 'efectivo',
                referencia: null,
                estado_pago: 'registrado'
            },
            {
                numero_factura: 'FAC-2026-0007',
                fecha_pago: '2026-03-22',
                monto_pagado: '14.50',
                metodo_pago: 'transferencia',
                referencia: 'TRF-9015',
                estado_pago: 'registrado'
            }
        ],
        mora: {
            facturas_vencidas: 0,
            deuda_total: '0.00'
        }
    },
    {
        cliente: {
            id_usuario: 3,
            nombres: 'Roberto',
            apellidos: 'Hernández',
            nombre_completo: 'Roberto Hernández',
            codigo_usuario: 'USR-003',
            dui: '06987321-5',
            sector: 'Sector Sur',
            tipo_usuario: 'Comercial',
            numero_medidor: 'MED-2024-003',
            estado: 'activo',
            direccion: 'Bo. El Calvario, 3a Av. Sur #8, Cabañas'
        },
        facturas: [{
                id_factura: 8,
                numero_factura: 'FAC-2026-0008',
                mes: 1,
                anio: 2026,
                total: '45.00',
                saldo_pendiente: '45.00',
                estado: 'vencida'
            },
            {
                id_factura: 9,
                numero_factura: 'FAC-2026-0009',
                mes: 2,
                anio: 2026,
                total: '52.30',
                saldo_pendiente: '52.30',
                estado: 'vencida'
            },
            {
                id_factura: 10,
                numero_factura: 'FAC-2026-0010',
                mes: 3,
                anio: 2026,
                total: '48.75',
                saldo_pendiente: '48.75',
                estado: 'pendiente'
            }
        ],
        pagos: [],
        mora: {
            facturas_vencidas: 3,
            deuda_total: '146.05'
        }
    }
];

function abrirDetalleCliente(idCliente) {
    detalleOverlay.classList.add('visible');
    document.getElementById('detCargando').style.display = 'flex';
    document.getElementById('detContenido').style.display = 'none';
    document.getElementById('det-nombre').textContent = 'Cargando...';
    document.getElementById('det-meta').textContent = '';
    document.getElementById('det-avatar').textContent = '--';

    // Intentar API real, si falla usar datos demo
    fetch(`../../api/detalle_cliente.php?id=${idCliente}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                renderDetalleCliente(data);
            } else {
                renderDetalleCliente(datosDemo[idCliente % datosDemo.length]);
            }
        })
        .catch(() => {
            renderDetalleCliente(datosDemo[idCliente % datosDemo.length]);
        });
}

// Abrir con datos demo directamente (desde mini-cards)
function abrirDetalleDemo(indice) {
    detalleOverlay.classList.add('visible');
    document.getElementById('detCargando').style.display = 'flex';
    document.getElementById('detContenido').style.display = 'none';
    document.getElementById('det-nombre').textContent = 'Cargando...';
    document.getElementById('det-meta').textContent = '';
    document.getElementById('det-avatar').textContent = '--';
    setTimeout(() => {
        try {
            renderDetalleCliente(datosDemo[indice % datosDemo.length]);
        } catch (e) {
            console.error('Error al renderizar detalle:', e);
            cerrarDetalle();
        }
    }, 400);
}

window.abrirDetalleCliente = abrirDetalleCliente;
window.abrirDetalleDemo = abrirDetalleDemo;

function renderDetalleCliente(data) {
    const c = data.cliente;
    const iniciales = (c.nombres || '').charAt(0) + (c.apellidos || '').charAt(0);
    document.getElementById('det-avatar').textContent = iniciales.toUpperCase();
    document.getElementById('det-nombre').textContent = c.nombre_completo;
    document.getElementById('det-meta').textContent =
        `${c.codigo_usuario} · ${c.sector || 'Sin sector'} · ${c.tipo_usuario || ''}`;

    // KPIs
    const totalFacturas = data.facturas.length;
    const facPagadas = data.facturas.filter(f => f.estado === 'pagada').length;
    const deuda = parseFloat(data.mora.deuda_total) || 0;
    document.getElementById('detKpis').innerHTML = `
    <div class="det-kpi"><div class="det-kpi-valor">${totalFacturas}</div><div class="det-kpi-label">Facturas</div></div>
    <div class="det-kpi"><div class="det-kpi-valor">${data.pagos.length}</div><div class="det-kpi-label">Pagos</div></div>
    <div class="det-kpi ${deuda > 0 ? 'alerta' : ''}"><div class="det-kpi-valor">$${deuda.toFixed(2)}</div><div class="det-kpi-label">Deuda</div></div>
  `;

    // Info general
    document.getElementById('detInfo').innerHTML = `
    <div class="det-info-item"><span class="det-info-label">Código</span><span class="det-info-valor">${esc(c.codigo_usuario)}</span></div>
    <div class="det-info-item"><span class="det-info-label">Sector</span><span class="det-info-valor">${esc(c.sector || '—')}</span></div>
    <div class="det-info-item"><span class="det-info-label">DUI</span><span class="det-info-valor">${esc(c.dui || '—')}</span></div>
    <div class="det-info-item"><span class="det-info-label">Medidor</span><span class="det-info-valor">${esc(c.numero_medidor || 'Sin asignar')}</span></div>
    <div class="det-info-item"><span class="det-info-label">Estado</span><span class="det-info-valor">${esc(c.estado)}</span></div>
    <div class="det-info-item"><span class="det-info-label">Dirección</span><span class="det-info-valor">${esc(c.direccion || '—')}</span></div>
  `;

    // Facturas
    if (data.facturas.length) {
        const meses = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        let filas = data.facturas.map(f => {
            const badgeCls = f.estado === 'pagada' ? 'green' : (f.estado === 'vencida' ? 'red' : 'yellow');
            return `<tr>
        <td class="td-mono">${esc(f.numero_factura)}</td>
        <td>${meses[f.mes]||f.mes} ${f.anio}</td>
        <td class="td-mono">$${parseFloat(f.total).toFixed(2)}</td>
        <td class="td-mono">$${parseFloat(f.saldo_pendiente).toFixed(2)}</td>
        <td><span class="badge badge-${badgeCls}">${f.estado}</span></td>
        <td><button class="btn btn-ghost btn-sm" onclick="imprimirFactura(${f.id_factura})"><i class="fas fa-print"></i></button></td>
      </tr>`;
        }).join('');
        document.getElementById('detFacturas').innerHTML =
            `<table><thead><tr><th>Factura</th><th>Periodo</th><th>Total</th><th>Saldo</th><th>Estado</th><th></th></tr></thead><tbody>${filas}</tbody></table>`;
    } else {
        document.getElementById('detFacturas').innerHTML =
            '<div class="det-vacio"><i class="fas fa-file-invoice"></i> Sin facturas registradas</div>';
    }

    // Pagos
    if (data.pagos.length) {
        const metodos = {
            efectivo: '<i class="fas fa-coins"></i>',
            transferencia: '<i class="fas fa-university"></i>',
            cheque: '<i class="fas fa-money-check"></i>'
        };
        document.getElementById('detPagos').innerHTML = '<div class="det-pagos-timeline">' + data.pagos.map(p => {
            const dotCls = p.estado_pago === 'anulado' ? 'anulado' : 'ok';
            const ico = dotCls === 'ok' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
            const fecha = new Date(p.fecha_pago).toLocaleDateString('es-SV', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            return `<div class="det-pago-item">
        <div class="det-pago-dot ${dotCls}">${ico}</div>
        <div class="det-pago-info">
          <div class="det-pago-titulo">${esc(p.numero_factura)} ${metodos[p.metodo_pago]||''}</div>
          <div class="det-pago-meta">${fecha}${p.referencia ? ' · Ref: ' + esc(p.referencia) : ''}</div>
        </div>
        <div class="det-pago-monto">$${parseFloat(p.monto_pagado).toFixed(2)}</div>
      </div>`;
        }).join('') + '</div>';
    } else {
        document.getElementById('detPagos').innerHTML =
            '<div class="det-vacio"><i class="fas fa-receipt"></i> Sin pagos registrados</div>';
    }

    // Mora
    const moraSeccion = document.getElementById('detMoraSeccion');
    if (deuda > 0) {
        moraSeccion.style.display = 'block';
        document.getElementById('detMora').innerHTML = `
      <div class="det-mora-card">
        <div class="det-mora-icono"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="det-mora-texto">
          <div class="det-mora-valor">$${deuda.toFixed(2)}</div>
          <div class="det-mora-desc">${data.mora.facturas_vencidas} factura(s) con saldo pendiente</div>
        </div>
      </div>`;
    } else {
        moraSeccion.style.display = 'none';
    }

    // Acciones
    const existente = document.getElementById('detAcciones');
    if (existente) existente.remove();
    const acciones = document.createElement('div');
    acciones.className = 'det-acciones';
    acciones.id = 'detAcciones';

    const facturaPendiente = data.facturas.find(f => f.estado !== 'pagada' && f.estado !== 'anulada');
    acciones.innerHTML = `
    <button class="btn btn-agua btn-sm" onclick="registrarPago(${c.id_usuario})" ${!facturaPendiente ? 'disabled title="Sin facturas pendientes"' : ''}>
      <i class="fas fa-hand-holding-usd"></i> Registrar pago
    </button>
    <button class="btn btn-primary btn-sm" onclick="imprimirUltimaFactura(${c.id_usuario})">
      <i class="fas fa-print"></i> Imprimir factura
    </button>
  `;
    document.getElementById('detContenido').appendChild(acciones);

    document.getElementById('detCargando').style.display = 'none';
    document.getElementById('detContenido').style.display = 'block';
}

function esc(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
}

function imprimirFactura(idFactura) {
    showToast('Preparando impresión de factura #' + idFactura + '...', 'info');
}

function imprimirUltimaFactura(idUsuario) {
    showToast('Preparando última factura del cliente...', 'info');
}

function registrarPago(idUsuario) {
    showToast('Módulo de pago — próximamente', 'info');
}
window.imprimirFactura = imprimirFactura;
window.imprimirUltimaFactura = imprimirUltimaFactura;
window.registrarPago = registrarPago;
</script>