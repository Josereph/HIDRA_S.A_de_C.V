<?php
// views/territorio.php — Vista consolidada del Módulo de Territorio
// REGLA: Cero CSS ni JS inline. Todo en assets/css/territorio.css y assets/js/territorio.js
?>
<!-- Inyectar BASE_PATH para el JS -->
<script>window.BASE_PATH = '<?= BASE_PATH ?>';</script>

<!-- Tabs de navegación -->
<div class="terr-tabs" role="tablist">
    <button class="terr-tab active" data-tab="vista" onclick="switchTab('vista')" role="tab">
        <i class="fas fa-map-marked-alt"></i> Vista por Sector
        <span class="tab-count" id="tabCountVista"><?= count($sectores) ?></span>
    </button>
    <button class="terr-tab" data-tab="sectores" onclick="switchTab('sectores')" role="tab">
        <i class="fas fa-layer-group"></i> Gestión de Sectores
    </button>
    <button class="terr-tab" data-tab="casas" onclick="switchTab('casas')" role="tab">
        <i class="fas fa-home"></i> Gestión de Viviendas
        <span class="tab-count" id="tabCountCasas"><?= $totalViviendas ?></span>
    </button>
</div>

<!-- ══════════════════════════════════════════════════
     PANEL 1: VISTA POR SECTOR
══════════════════════════════════════════════════ -->
<div id="panel-vista" class="terr-panel active">

    <div class="terr-toolbar">
        <span class="terr-toolbar-title"><i class="fas fa-map-marked-alt"></i> Sectores del Servicio</span>
    </div>

    <!-- Tarjetas principales de sectores (cargadas por JS) -->
    <div id="sectorCardsGrid" class="sector-cards-grid">
        <!-- Skeleton mientras carga -->
        <?php for($i=0;$i<4;$i++): ?>
        <div class="skeleton-card">
            <div class="skeleton-line"></div>
            <div class="skeleton-line short"></div>
            <div class="skeleton-line medium"></div>
        </div>
        <?php endfor; ?>
    </div>

    <!-- Sub-vista: Casas del Sector Seleccionado -->
    <div id="sectorSubview" class="sector-subview">
        <div class="subview-header">
            <div class="subview-breadcrumb">
                <i class="fas fa-map-marker-alt"></i>
                <span>Sectores</span>
                <span class="sep">/</span>
                <strong id="subviewSectorName">—</strong>
            </div>
            <button class="btn btn-ghost btn-sm" onclick="document.getElementById('sectorSubview').classList.remove('visible');document.querySelectorAll('.sector-main-card').forEach(c=>c.classList.remove('active-card'))">
                <i class="fas fa-times"></i> Cerrar vista
            </button>
        </div>
        <div id="miniCasasGrid" class="mini-cards-grid"></div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════
     PANEL 2: GESTIÓN DE SECTORES
══════════════════════════════════════════════════ -->
<div id="panel-sectores" class="terr-panel">

    <div class="terr-toolbar">
        <span class="terr-toolbar-title"><i class="fas fa-layer-group"></i> Sectores Registrados</span>
        <button class="btn btn-primary btn-sm" id="btnNewSector" onclick="openNewSector()">
            <i class="fas fa-plus"></i> Nuevo Sector
        </button>
    </div>

    <div class="terr-table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Viviendas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="sectoresTableBody">
                <tr><td colspan="5" style="text-align:center;padding:20px"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════════
     PANEL 3: GESTIÓN DE VIVIENDAS
══════════════════════════════════════════════════ -->
<div id="panel-casas" class="terr-panel">

    <div class="terr-toolbar">
        <span class="terr-toolbar-title"><i class="fas fa-home"></i> Viviendas Registradas</span>
        <button class="btn btn-primary btn-sm" id="btnNewVivienda" onclick="openNewVivienda()">
            <i class="fas fa-plus"></i> Nueva Vivienda
        </button>
    </div>

    <div class="terr-table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Dirección</th>
                    <th>Sector</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Coordenadas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="casasTableBody">
                <?php foreach($viviendas as $v): ?>
                <tr>
                    <td class="td-primary"><?= htmlspecialchars($v['direccion']) ?></td>
                    <td><?= htmlspecialchars($v['nombre_sector']) ?></td>
                    <td><?= $v['cliente_nombre'] ? htmlspecialchars($v['cliente_nombre']) : '<span style="color:var(--text-muted)">Sin asignar</span>' ?></td>
                    <td>
                        <?php
                        $b = ['Activa'=>'badge-green','Suspendida'=>'badge-red','En revisión'=>'badge-yellow'];
                        $badge = $b[$v['estado']] ?? 'badge-yellow';
                        ?>
                        <span class="badge <?= $badge ?>"><?= htmlspecialchars($v['estado']) ?></span>
                    </td>
                    <td class="td-mono"><?= ($v['lat'] && $v['lng']) ? htmlspecialchars($v['lat'].','.$v['lng']) : '—' ?></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-secondary btn-sm" onclick="openEditVivienda(<?= $v['id'] ?>)"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteVivienda(<?= $v['id'] ?>,'<?= addslashes(htmlspecialchars($v['direccion'])) ?>')"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="pagination">
        <span class="pagination-info" id="vivPagInfo">
            Mostrando <?= min(10, $totalViviendas) ?> de <?= $totalViviendas ?> viviendas
        </span>
        <div class="pagination-btns" id="vivPagBtns">
            <!-- Renderizado por JS en loadCasasTable() -->
            <?php if ($lastPage > 1): ?>
            <?php for($p = 1; $p <= $lastPage; $p++): ?>
            <button class="page-btn <?= $p == $page ? 'active' : '' ?>" onclick="loadCasasTable(<?= $p ?>)"><?= $p ?></button>
            <?php endfor; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════
     MODALES
══════════════════════════════════════════════════ -->

<!-- Modal: Crear / Editar Sector -->
<div class="modal-overlay" id="modalSector" style="display:none" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="sectorModalTitle">Nuevo Sector</span>
            <button class="modal-close" onclick="closeModal('modalSector')" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-body">
            <form id="sectorForm" onsubmit="event.preventDefault(); saveSector()">
                <input type="hidden" id="sectorId">
                <div class="form-modal-grid">
                    <div class="form-group full-col">
                        <label class="form-label" for="sectorNombre">Nombre del Sector *</label>
                        <input type="text" id="sectorNombre" class="form-control" placeholder="Ej. Sector Norte" required>
                    </div>
                    <div class="form-group full-col">
                        <label class="form-label" for="sectorDesc">Descripción</label>
                        <textarea id="sectorDesc" class="form-control" rows="2" placeholder="Descripción opcional..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sectorEstado">Estado</label>
                        <select id="sectorEstado" class="form-control form-select">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost btn-sm" onclick="closeModal('modalSector')">Cancelar</button>
            <button class="btn btn-primary btn-sm" onclick="saveSector()"><i class="fas fa-save"></i> Guardar Sector</button>
        </div>
    </div>
</div>

<!-- Modal: Crear / Editar Vivienda -->
<div class="modal-overlay" id="modalVivienda" style="display:none" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="viviendaModalTitle">Nueva Vivienda</span>
            <button class="modal-close" onclick="closeModal('modalVivienda')" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-body">
            <form id="viviendaForm" onsubmit="event.preventDefault(); saveVivienda()">
                <input type="hidden" id="viviendaId">
                <div class="form-modal-grid">
                    <div class="form-group full-col">
                        <label class="form-label" for="viviendaDir">Dirección *</label>
                        <input type="text" id="viviendaDir" class="form-control" placeholder="Ej. Casa #12, Calle Principal" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="viviendaSector">Sector *</label>
                        <select id="viviendaSector" class="form-control form-select" required>
                            <option value="">— Seleccionar —</option>
                            <?php foreach($sectores as $s): ?>
                            <option value="<?= $s['id_sector'] ?>"><?= htmlspecialchars($s['nombre_sector']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="viviendaEstado">Estado</label>
                        <select id="viviendaEstado" class="form-control form-select">
                            <option value="En revisión">En revisión</option>
                            <option value="Activa">Activa</option>
                            <option value="Suspendida">Suspendida</option>
                        </select>
                    </div>
                    <div class="form-group full-col">
                        <label class="form-label" for="viviendaCliente">Cliente Asignado</label>
                        <select id="viviendaCliente" class="form-control form-select">
                            <option value="">— Sin asignar —</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="viviendaLat">Latitud</label>
                        <input type="number" step="0.000001" id="viviendaLat" class="form-control" placeholder="13.794185">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="viviendaLng">Longitud</label>
                        <input type="number" step="0.000001" id="viviendaLng" class="form-control" placeholder="-88.896530">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost btn-sm" onclick="closeModal('modalVivienda')">Cancelar</button>
            <button class="btn btn-primary btn-sm" onclick="saveVivienda()"><i class="fas fa-save"></i> Guardar Vivienda</button>
        </div>
    </div>
</div>

<!-- Modal: Detalle de Vivienda (elaborado) -->
<div class="modal-overlay terr-modal-lg" id="modalViviendaDetail" style="display:none" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-home"></i> Detalle de Vivienda</span>
            <button class="modal-close" onclick="closeModal('modalViviendaDetail')" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-body" id="viviendaDetailBody">
            <!-- Cargado dinámicamente por JS -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger btn-sm" id="detailDeleteBtn"><i class="fas fa-trash"></i> Eliminar</button>
            <button class="btn btn-secondary btn-sm" id="detailEditBtn"><i class="fas fa-edit"></i> Editar</button>
            <button class="btn btn-ghost btn-sm" onclick="closeModal('modalViviendaDetail')">Cerrar</button>
        </div>
    </div>
</div>

<!-- Toast container -->
<div id="toastContainer"></div>
