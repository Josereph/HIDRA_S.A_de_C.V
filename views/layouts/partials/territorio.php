<?php
/**
 * views/layouts/partials/territorio.php
 * Vista de Territorio — integrada en pagina_principal.php
 * REGLA: CSS → assets/css/territorio.css | JS → assets/js/territorio.js
 * Datos: $sectores_lista, $clientes_lista (provistos por data_loader.php)
 */

// Paginación de viviendas (servidor para primer render)
// Usar el mismo $pdo que ya está disponible vía extract($GLOBALS)
$pdo_terr = Database::getInstance();
$terr_page    = max(1, (int)($_GET['page'] ?? 1));
$terr_perPage = 10;
$terr_offset  = ($terr_page - 1) * $terr_perPage;

// Proteger contra tabla 'viviendas' inexistente
try {
    $terr_total = (int)$pdo_terr->query("SELECT COUNT(*) FROM viviendas")->fetchColumn();
    $terr_last  = max(1, (int)ceil($terr_total / $terr_perPage));

    $stmt_viv = $pdo_terr->prepare("
        SELECT v.*,
               s.nombre_sector,
               CONCAT(u.nombres, ' ', IFNULL(u.apellidos,'')) AS cliente_nombre
        FROM viviendas v
        INNER JOIN sectores s ON v.sector_id = s.id_sector
        LEFT JOIN usuarios u ON v.cliente_id = u.id_usuario
        ORDER BY v.fecha_creacion DESC
        LIMIT :lim OFFSET :off
    ");
    $stmt_viv->bindValue(':lim', $terr_perPage, PDO::PARAM_INT);
    $stmt_viv->bindValue(':off', $terr_offset,  PDO::PARAM_INT);
    $stmt_viv->execute();
    $viviendas_pag = $stmt_viv->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    // La tabla 'viviendas' aún no existe o hay un error de esquema.
    // El módulo se carga igual, pero sin datos de viviendas.
    error_log('[HIDRA territorio.php] ' . $e->getMessage());
    $terr_total    = 0;
    $terr_last     = 1;
    $viviendas_pag = [];
}
?>

<!-- Inyectar BASE_PATH para el JS del módulo -->
<script>
  if (typeof window.BASE_PATH === 'undefined') {
    // En pagina_principal.php la raíz relativa es dos niveles arriba
    window.BASE_PATH = '';
    window.TERR_API  = '../../api/territorio_api.php';
  }
</script>

<!-- ══════════════════════════════════
     VISTA: TERRITORIO
══════════════════════════════════ -->
<div class="view" id="view-territorio">

    <div class="page-header">
        <div>
            <h1 class="page-title">Territorio</h1>
            <p class="page-subtitle">Sectores, viviendas e inmuebles del servicio</p>
        </div>
    </div>

    <!-- Tabs principales -->
    <div class="terr-tabs" role="tablist">
        <button class="terr-tab active" data-tab="vista" onclick="terrSwitchTab('vista')" role="tab">
            <i class="fas fa-map-marked-alt"></i> Vista por Sector
            <span class="tab-count" id="tabCountVista"><?= count($sectores_lista) ?></span>
        </button>
        <button class="terr-tab" data-tab="sectores" onclick="terrSwitchTab('sectores')" role="tab">
            <i class="fas fa-layer-group"></i> Sectores
        </button>
        <button class="terr-tab" data-tab="casas" onclick="terrSwitchTab('casas')" role="tab">
            <i class="fas fa-home"></i> Viviendas
            <span class="tab-count" id="tabCountCasas"><?= $terr_total ?></span>
        </button>
    </div>

    <!-- ══ TAB 1: VISTA POR SECTOR ══════════════════════ -->
    <div id="terr-panel-vista" class="terr-panel active">

        <div class="terr-toolbar">
            <span class="terr-toolbar-title"><i class="fas fa-map-marked-alt"></i> Sectores del Servicio</span>
        </div>

        <!-- Grid de cards de sectores — JS llena este contenedor -->
        <div id="sectorCardsGrid" class="sector-cards-grid">
            <?php foreach($sectores_lista as $s): ?>
            <div class="sector-card-skeleton skeleton-card">
                <div class="skeleton-line"></div>
                <div class="skeleton-line short"></div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- ══ TAB 2: SECTORES CRUD ══════════════════════════ -->
    <div id="terr-panel-sectores" class="terr-panel">

        <div class="terr-toolbar">
            <span class="terr-toolbar-title"><i class="fas fa-layer-group"></i> Sectores Registrados</span>
            <button class="btn btn-primary btn-sm" onclick="terrOpenNewSector()">
                <i class="fas fa-plus"></i> Nuevo Sector
            </button>
        </div>

        <div class="terr-table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Viviendas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="terrSectoresTableBody">
                    <?php foreach($sectores_lista as $s): ?>
                    <tr data-id="<?= $s['id_sector'] ?>">
                        <td class="td-mono">S-<?= str_pad($s['id_sector'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td class="td-primary"><?= htmlspecialchars($s['nombre_sector']) ?></td>
                        <td style="color:var(--text-muted);font-size:.8rem"><?= htmlspecialchars($s['descripcion'] ?? '—') ?></td>
                        <td><?= (int)($s['total_casas'] ?? $s['total_viviendas'] ?? 0) ?></td>
                        <td><span class="badge <?= $s['estado'] === 'activo' ? 'badge-green' : 'badge-yellow' ?>"><?= ucfirst(htmlspecialchars($s['estado'])) ?></span></td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-secondary btn-sm" onclick="terrOpenEditSector(<?= $s['id_sector'] ?>)"><i class="fas fa-edit"></i> Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="terrConfirmDeleteSector(<?= $s['id_sector'] ?>,'<?= addslashes(htmlspecialchars($s['nombre_sector'])) ?>')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($sectores_lista)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No hay sectores registrados</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══ TAB 3: VIVIENDAS CRUD + PAGINACIÓN ════════════ -->
    <div id="terr-panel-casas" class="terr-panel">

        <div class="terr-toolbar">
            <span class="terr-toolbar-title"><i class="fas fa-home"></i> Viviendas Registradas</span>
            <button class="btn btn-primary btn-sm" onclick="terrOpenNewVivienda()">
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
                <tbody id="terrCasasTableBody">
                    <?php foreach($viviendas_pag as $v):
                        $badge = ['Activa'=>'badge-green','Suspendida'=>'badge-red','En revisión'=>'badge-yellow'][$v['estado']] ?? 'badge-yellow';
                    ?>
                    <tr>
                        <td class="td-primary"><?= htmlspecialchars($v['direccion']) ?></td>
                        <td><?= htmlspecialchars($v['nombre_sector']) ?></td>
                        <td><?= $v['cliente_nombre'] ? htmlspecialchars($v['cliente_nombre']) : '<span style="color:var(--text-muted)">Sin asignar</span>' ?></td>
                        <td><span class="badge <?= $badge ?>"><?= htmlspecialchars($v['estado']) ?></span></td>
                        <td class="td-mono"><?= ($v['lat'] && $v['lng']) ? $v['lat'].','.$v['lng'] : '—' ?></td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-secondary btn-sm" onclick="terrOpenEditVivienda(<?= $v['id'] ?>)"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" onclick="terrConfirmDeleteVivienda(<?= $v['id'] ?>,'<?= addslashes(htmlspecialchars($v['direccion'])) ?>')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($viviendas_pag)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">No hay viviendas registradas</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación real -->
        <div class="pagination">
            <span class="pagination-info" id="terrVivPagInfo">
                <?php if($terr_total > 0): ?>
                Mostrando <?= $terr_offset+1 ?>–<?= min($terr_offset+$terr_perPage, $terr_total) ?> de <?= $terr_total ?> viviendas
                <?php else: ?>
                Sin registros
                <?php endif; ?>
            </span>
            <div class="pagination-btns" id="terrVivPagBtns">
                <button class="page-btn" onclick="terrLoadCasasTable(<?= $terr_page-1 ?>)" <?= $terr_page<=1?'disabled':'' ?>>‹ Ant.</button>
                <?php for($p=1; $p<=$terr_last; $p++): ?>
                <button class="page-btn <?= $p==$terr_page?'active':'' ?>" onclick="terrLoadCasasTable(<?= $p ?>)"><?= $p ?></button>
                <?php endfor; ?>
                <button class="page-btn" onclick="terrLoadCasasTable(<?= $terr_page+1 ?>)" <?= $terr_page>=$terr_last?'disabled':'' ?>>Sig. ›</button>
            </div>
        </div>
    </div>

</div><!-- /view-territorio -->


<!-- ══════════════════════════════════════════════════
     MODAL: VIVIENDAS DEL SECTOR
     Amplío (terr-modal-xl) para mostrar la grilla de mini-cards.
     Controlado íntegramente por territorio.js.
══════════════════════════════════════════════════ -->
<div class="modal-overlay terr-modal-xl" id="terrModalSectorCasas"
     style="display:none" role="dialog" aria-modal="true"
     aria-labelledby="terrSectorCasasNombre">
    <div class="modal modal-sector-casas">

        <div class="modal-header">
            <div class="sector-modal-title-group">
                <span class="sector-modal-icon"><i class="fas fa-map-marked-alt"></i></span>
                <div>
                    <span class="modal-title" id="terrSectorCasasNombre">Sector</span>
                    <span class="sector-modal-conteo" id="terrSectorCasasConteo"></span>
                </div>
            </div>
            <button class="modal-close"
                    onclick="terrCloseSectorModal()"
                    aria-label="Cerrar">
                ✕
            </button>
        </div>

        <div class="modal-body modal-body-casas">
            <!-- JS inyecta aquí las mini-cards de viviendas -->
            <div id="terrModalCasasGrid" class="mini-cards-grid">
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Cargando viviendas...</p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <span class="sector-modal-hint">
                <i class="fas fa-info-circle"></i>
                Haz clic en una vivienda para ver su detalle completo
            </span>
            <button class="btn btn-ghost btn-sm"
                    onclick="terrCloseSectorModal()">
                Cerrar
            </button>
        </div>

    </div>
</div>


<!-- ══════════════════════════════════════════════════
     MODALES DEL MÓDULO TERRITORIO (CRUD)
══════════════════════════════════════════════════ -->

<!-- Modal: Crear / Editar Sector -->
<div class="modal-overlay" id="terrModalSector" style="display:none" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="terrSectorModalTitle">Nuevo Sector</span>
            <button class="modal-close" onclick="terrCloseModal('terrModalSector')" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-body">
            <form id="terrSectorForm" onsubmit="event.preventDefault(); terrSaveSector()">
                <input type="hidden" id="terrSectorId">
                <div class="form-modal-grid">
                    <div class="form-group full-col">
                        <label class="form-label" for="terrSectorNombre">Nombre del Sector *</label>
                        <input type="text" id="terrSectorNombre" class="form-control" placeholder="Ej. Sector Norte" required>
                    </div>
                    <div class="form-group full-col">
                        <label class="form-label" for="terrSectorDesc">Descripción</label>
                        <textarea id="terrSectorDesc" class="form-control" rows="2" placeholder="Descripción opcional..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="terrSectorEstado">Estado</label>
                        <select id="terrSectorEstado" class="form-control form-select">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost btn-sm" onclick="terrCloseModal('terrModalSector')">Cancelar</button>
            <button class="btn btn-primary btn-sm" onclick="terrSaveSector()"><i class="fas fa-save"></i> Guardar Sector</button>
        </div>
    </div>
</div>

<!-- Modal: Crear / Editar Vivienda -->
<div class="modal-overlay" id="terrModalVivienda" style="display:none" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="terrViviendaModalTitle">Nueva Vivienda</span>
            <button class="modal-close" onclick="terrCloseModal('terrModalVivienda')" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-body">
            <form id="terrViviendaForm" onsubmit="event.preventDefault(); terrSaveVivienda()">
                <input type="hidden" id="terrViviendaId">
                <div class="form-modal-grid">
                    <div class="form-group full-col">
                        <label class="form-label" for="terrViviendaDir">Dirección *</label>
                        <input type="text" id="terrViviendaDir" class="form-control" placeholder="Ej. Casa #12, Calle Principal" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="terrViviendaSector">Sector *</label>
                        <select id="terrViviendaSector" class="form-control form-select" required>
                            <option value="">— Seleccionar —</option>
                            <?php foreach($sectores_lista as $s): ?>
                            <option value="<?= $s['id_sector'] ?>"><?= htmlspecialchars($s['nombre_sector']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="terrViviendaEstado">Estado</label>
                        <select id="terrViviendaEstado" class="form-control form-select">
                            <option value="En revisión">En revisión</option>
                            <option value="Activa">Activa</option>
                            <option value="Suspendida">Suspendida</option>
                        </select>
                    </div>
                    <div class="form-group full-col">
                        <label class="form-label" for="terrViviendaCliente">Cliente Asignado</label>
                        <select id="terrViviendaCliente" class="form-control form-select">
                            <option value="">— Sin asignar —</option>
                            <?php foreach($clientes_lista as $c): ?>
                            <option value="<?= $c['id_usuario'] ?>"><?= htmlspecialchars($c['cliente']) ?> (<?= htmlspecialchars($c['codigo_usuario']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="terrViviendaLat">Latitud</label>
                        <input type="number" step="0.000001" id="terrViviendaLat" class="form-control" placeholder="13.794185">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="terrViviendaLng">Longitud</label>
                        <input type="number" step="0.000001" id="terrViviendaLng" class="form-control" placeholder="-88.896530">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost btn-sm" onclick="terrCloseModal('terrModalVivienda')">Cancelar</button>
            <button class="btn btn-primary btn-sm" onclick="terrSaveVivienda()"><i class="fas fa-save"></i> Guardar Vivienda</button>
        </div>
    </div>
</div>

<!-- Modal: Detalle Vivienda (elaborado) -->
<div class="modal-overlay terr-modal-lg" id="terrModalViviendaDetail" style="display:none" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title"><i class="fas fa-home"></i> Detalle de Vivienda</span>
            <button class="modal-close" onclick="terrCloseModal('terrModalViviendaDetail')" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-body" id="terrViviendaDetailBody">
            <div class="empty-state"><i class="fas fa-spinner fa-spin"></i><p>Cargando...</p></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger btn-sm" id="terrDetailDeleteBtn"><i class="fas fa-trash"></i> Eliminar</button>
            <button class="btn btn-secondary btn-sm" id="terrDetailEditBtn"><i class="fas fa-edit"></i> Editar</button>
            <button class="btn btn-ghost btn-sm" onclick="terrCloseModal('terrModalViviendaDetail')">Cerrar</button>
        </div>
    </div>
</div>