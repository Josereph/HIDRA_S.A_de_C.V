<?php
function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function badgeCasaClass($estado) {
    if ($estado === 'Activa') return 'badge-active';
    if ($estado === 'Suspendida') return 'badge-suspended';
    return 'badge-review';
}

function badgeSectorClass($estado) {
    return $estado === 'activo' ? 'badge-active' : 'badge-suspended';
}

$primerSectorId = !empty($sectores) ? (int)$sectores[0]['id'] : 0;
$territorioPayload = [
    'sectores' => $sectores,
    'casas' => $todasLasCasas,
    'primerSectorId' => $primerSectorId,
    'basePath' => BASE_PATH
];
?>

<style>
.territorio-page {
    display: flex;
    flex-direction: column;
    gap: 22px;
}

.territorio-hero {
    display: grid;
    grid-template-columns: 1.4fr 0.9fr;
    gap: 18px;
    align-items: stretch;
}

.territorio-title {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
}

.territorio-title h2 {
    margin: 0 0 8px 0;
    color: var(--color-primary-dark);
    font-size: 1.45rem;
}

.territorio-title p {
    color: var(--color-text-light);
    line-height: 1.5;
    margin: 0;
}

.territorio-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-end;
}

.territorio-kpis {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 20px;
}

.territorio-kpi {
    border: 1px solid var(--color-border);
    background: #f8fbff;
    border-radius: 10px;
    padding: 14px;
}

.territorio-kpi span {
    display: block;
    color: var(--color-text-light);
    font-size: .78rem;
    margin-bottom: 5px;
}

.territorio-kpi strong {
    color: var(--color-primary-dark);
    font-size: 1.35rem;
}

.assign-panel {
    background: linear-gradient(135deg, #ffffff, #f4f8fc);
    border: 1px solid var(--color-border);
    border-radius: 10px;
    padding: 18px;
}

.assign-panel h3 {
    color: var(--color-primary-dark);
    margin: 0 0 8px 0;
    font-size: 1.05rem;
}

.assign-panel p {
    color: var(--color-text-light);
    font-size: .88rem;
    line-height: 1.45;
    margin: 0 0 14px 0;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.form-actions-right {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 12px;
}

.territorio-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(245px, 1fr));
    gap: 16px;
}

.territorio-card {
    width: 100%;
    text-align: left;
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: 12px;
    padding: 18px;
    box-shadow: var(--shadow-sm);
    transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.territorio-card:hover,
.territorio-card.is-selected {
    border-color: var(--color-primary-light);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.territorio-card.is-inactive {
    opacity: .72;
}

.territorio-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 12px;
}

.territorio-card h3 {
    margin: 0;
    color: var(--color-primary-dark);
    font-size: 1.02rem;
}

.territorio-card p {
    color: var(--color-text-light);
    font-size: .84rem;
    line-height: 1.45;
    min-height: 38px;
    margin: 0 0 12px 0;
}

.territorio-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    border-top: 1px solid var(--color-border);
    padding-top: 12px;
}

.territorio-card-count {
    color: var(--color-primary-dark);
    font-weight: 700;
}

.detail-empty {
    border: 1px dashed var(--color-border);
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    color: var(--color-text-light);
    background: #f8fbff;
}

.house-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(255px, 1fr));
    gap: 16px;
}

.house-card {
    background: var(--color-white);
    border: 1px solid var(--color-border);
    border-radius: 12px;
    padding: 16px;
    box-shadow: var(--shadow-sm);
}

.house-card h4 {
    margin: 0 0 8px 0;
    color: var(--color-primary-dark);
    font-size: .98rem;
}

.house-card p {
    margin: 0 0 7px 0;
    color: var(--color-text-light);
    font-size: .85rem;
    line-height: 1.35;
}

.house-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 12px;
}

.table-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.btn-sm {
    padding: 7px 10px;
    font-size: .78rem;
}

.btn-danger {
    background: #f8e8e8;
    color: #9f2626;
    border: 1px solid #f0b8b8;
}

.btn-danger:hover {
    background: #f3d3d3;
}

.btn-plain {
    background: transparent;
    color: var(--color-primary);
    border: 1px solid var(--color-border);
}

.table-responsive {
    overflow-x: auto;
}

.territorio-modal {
    display: none;
    position: fixed;
    z-index: 2000;
    inset: 0;
    background: rgba(0, 23, 44, .48);
    align-items: center;
    justify-content: center;
    padding: 18px;
}

.territorio-modal.is-open {
    display: flex;
}

.territorio-modal-content {
    width: min(620px, 100%);
    max-height: 92vh;
    overflow-y: auto;
    background: var(--color-white);
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .22);
    border: 1px solid var(--color-border);
    padding: 22px;
}

.territorio-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}

.territorio-modal-header h3 {
    margin: 0;
    color: var(--color-primary-dark);
}

.close-modal {
    border: none;
    background: var(--color-bg);
    color: var(--color-primary-dark);
    border-radius: 8px;
    font-size: 1.35rem;
    line-height: 1;
    width: 36px;
    height: 36px;
    cursor: pointer;
}

#mapTerritorio {
    width: 100%;
    height: 340px;
    border: 1px solid var(--color-border);
    border-radius: 12px;
    overflow: hidden;
}

.map-help {
    color: var(--color-text-light);
    font-size: .85rem;
    margin-bottom: 12px;
}

@media (max-width: 980px) {
    .territorio-hero,
    .form-grid-2 {
        grid-template-columns: 1fr;
    }

    .territorio-kpis {
        grid-template-columns: 1fr;
    }

    .territorio-title {
        flex-direction: column;
    }

    .territorio-actions {
        justify-content: flex-start;
    }
}
</style>

<div class="territorio-page">
    <section class="territorio-hero">
        <div class="card">
            <div class="territorio-title">
                <div>
                    <h2><i class="fas fa-map-marked-alt"></i> Gestión de Territorio</h2>
                    <p>
                        Aquí administras los territorios/sectores, registras casas y asignas cada casa al territorio correcto.
                        Al presionar una tarjeta de territorio se muestran sus casas ordenadas por dirección.
                    </p>
                </div>
                <div class="territorio-actions">
                    <button type="button" class="btn btn-primary" data-open-modal="modalTerritorioCrear">
                        <i class="fas fa-plus"></i> Nuevo territorio
                    </button>
                    <button type="button" class="btn btn-secondary" data-open-modal="modalCasaCrear">
                        <i class="fas fa-home"></i> Nueva casa
                    </button>
                </div>
            </div>

            <div class="territorio-kpis">
                <div class="territorio-kpi">
                    <span>Territorios</span>
                    <strong><?= h($totalTerritorios) ?></strong>
                </div>
                <div class="territorio-kpi">
                    <span>Territorios activos</span>
                    <strong><?= h($totalTerritoriosActivos) ?></strong>
                </div>
                <div class="territorio-kpi">
                    <span>Casas registradas</span>
                    <strong><?= h($totalCasas) ?></strong>
                </div>
            </div>
        </div>

        <div class="assign-panel" id="vista-territorios">
            <h3><i class="fas fa-link"></i> Asignar casa a territorio</h3>
            <p>Selecciona una casa existente y muévela al territorio correcto. Esto actualiza el campo <strong>sector_id</strong> de la tabla <strong>viviendas</strong>.</p>

            <form action="<?= BASE_PATH ?>/territorio/assignCasa" method="POST">
                <div class="form-group">
                    <label for="id_sector_assign">Territorio destino</label>
                    <select name="id_sector" id="id_sector_assign" class="form-control" required>
                        <option value="">Seleccione territorio...</option>
                        <?php foreach ($sectoresActivos as $sector): ?>
                            <option value="<?= h($sector['id']) ?>"><?= h($sector['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_casa_assign">Casa a asignar</label>
                    <select name="id_casa" id="id_casa_assign" class="form-control" required>
                        <option value="">Seleccione casa...</option>
                        <?php foreach ($todasLasCasas as $casa): ?>
                            <option value="<?= h($casa['id']) ?>">
                                #<?= h($casa['id']) ?> - <?= h($casa['direccion']) ?> | Actual: <?= h($casa['nombre_sector']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-check"></i> Asignar casa
                </button>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2><i class="fas fa-th-large"></i> Vista por territorio</h2>
            <span class="badge badge-review">Presiona una tarjeta</span>
        </div>

        <?php if (empty($sectores)): ?>
            <div class="detail-empty">No hay territorios registrados todavía.</div>
        <?php else: ?>
            <div class="territorio-card-grid" id="territorioCardGrid">
                <?php foreach ($sectores as $index => $sector): ?>
                    <?php $isSelected = $index === 0 ? 'is-selected' : ''; ?>
                    <?php $isInactive = $sector['estado'] === 'inactivo' ? 'is-inactive' : ''; ?>
                    <button type="button"
                            class="territorio-card <?= $isSelected ?> <?= $isInactive ?>"
                            data-sector-card="<?= h($sector['id']) ?>">
                        <div class="territorio-card-top">
                            <h3><?= h($sector['nombre']) ?></h3>
                            <span class="badge <?= badgeSectorClass($sector['estado']) ?>"><?= h($sector['estado']) ?></span>
                        </div>
                        <p><?= h($sector['descripcion'] ?: 'Sin descripción registrada.') ?></p>
                        <div class="territorio-card-meta">
                            <span class="territorio-card-count"><i class="fas fa-home"></i> <?= h($sector['total_casas']) ?> casas</span>
                            <span style="color: var(--color-primary); font-size:.82rem;">Ver casas</span>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="card" id="casasPorTerritorioCard">
        <div class="card-header">
            <h2 id="casasSectorTitle"><i class="fas fa-home"></i> Casas del territorio</h2>
            <select id="sectorSelectorDetalle" class="form-control" style="max-width: 280px;">
                <?php foreach ($sectores as $sector): ?>
                    <option value="<?= h($sector['id']) ?>"><?= h($sector['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="casasSectorContainer" class="house-grid"></div>
        <div id="casasSectorEmpty" class="detail-empty" style="display:none;">Este territorio todavía no tiene casas asignadas.</div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> CRUD de territorios</h2>
            <button type="button" class="btn btn-primary" data-open-modal="modalTerritorioCrear">+ Agregar</button>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Territorio</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Casas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sectores)): ?>
                        <tr><td colspan="6" style="text-align:center;">No hay territorios registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($sectores as $sector): ?>
                            <tr>
                                <td><?= h($sector['id']) ?></td>
                                <td><strong><?= h($sector['nombre']) ?></strong></td>
                                <td><?= h($sector['descripcion'] ?: '—') ?></td>
                                <td><span class="badge <?= badgeSectorClass($sector['estado']) ?>"><?= h($sector['estado']) ?></span></td>
                                <td><?= h($sector['total_casas']) ?></td>
                                <td>
                                    <div class="table-actions">
                                        <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                data-edit-territorio
                                                data-id="<?= h($sector['id']) ?>"
                                                data-nombre="<?= h($sector['nombre']) ?>"
                                                data-descripcion="<?= h($sector['descripcion']) ?>"
                                                data-estado="<?= h($sector['estado']) ?>">
                                            Editar
                                        </button>
                                        <form action="<?= BASE_PATH ?>/territorio/deleteTerritorio" method="POST" onsubmit="return confirm('¿Desactivar este territorio? No se borran sus datos, solo queda inactivo.');">
                                            <input type="hidden" name="id" value="<?= h($sector['id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Desactivar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2><i class="fas fa-home"></i> CRUD de casas</h2>
            <button type="button" class="btn btn-primary" data-open-modal="modalCasaCrear">+ Agregar</button>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dirección</th>
                        <th>Territorio</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Coordenadas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($todasLasCasas)): ?>
                        <tr><td colspan="7" style="text-align:center;">No hay casas registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($todasLasCasas as $casa): ?>
                            <tr>
                                <td><?= h($casa['id']) ?></td>
                                <td><strong><?= h($casa['direccion']) ?></strong></td>
                                <td><?= h($casa['nombre_sector']) ?></td>
                                <td><?= h($casa['cliente_nombre'] ?: 'Sin asignar') ?></td>
                                <td><span class="badge <?= badgeCasaClass($casa['estado']) ?>"><?= h($casa['estado']) ?></span></td>
                                <td><?= ($casa['lat'] && $casa['lng']) ? h($casa['lat'] . ', ' . $casa['lng']) : '—' ?></td>
                                <td>
                                    <div class="table-actions">
                                        <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                data-edit-casa
                                                data-id="<?= h($casa['id']) ?>"
                                                data-sector-id="<?= h($casa['sector_id']) ?>"
                                                data-cliente-id="<?= h($casa['cliente_id']) ?>"
                                                data-direccion="<?= h($casa['direccion']) ?>"
                                                data-lat="<?= h($casa['lat']) ?>"
                                                data-lng="<?= h($casa['lng']) ?>"
                                                data-estado="<?= h($casa['estado']) ?>">
                                            Editar
                                        </button>
                                        <form action="<?= BASE_PATH ?>/territorio/deleteCasa" method="POST" onsubmit="return confirm('¿Eliminar esta casa?');">
                                            <input type="hidden" name="id" value="<?= h($casa['id']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2><i class="fas fa-map"></i> Mapa de referencia</h2>
            <span class="badge badge-review">Opcional</span>
        </div>
        <p class="map-help">Puedes hacer clic en el mapa para llenar automáticamente latitud y longitud en el formulario de nueva casa.</p>
        <div id="mapTerritorio"></div>
    </section>
</div>

<!-- Modal crear territorio -->
<div class="territorio-modal" id="modalTerritorioCrear">
    <div class="territorio-modal-content">
        <div class="territorio-modal-header">
            <h3><i class="fas fa-plus"></i> Nuevo territorio</h3>
            <button type="button" class="close-modal" data-close-modal>&times;</button>
        </div>
        <form action="<?= BASE_PATH ?>/territorio/storeTerritorio" method="POST">
            <div class="form-group">
                <label for="territorio_nombre">Nombre del territorio</label>
                <input type="text" name="nombre" id="territorio_nombre" class="form-control" placeholder="Ej. Sector Centro" required>
            </div>
            <div class="form-group">
                <label for="territorio_descripcion">Descripción</label>
                <textarea name="descripcion" id="territorio_descripcion" class="form-control" rows="3" placeholder="Referencia o descripción del territorio"></textarea>
            </div>
            <div class="form-group">
                <label for="territorio_estado">Estado</label>
                <select name="estado" id="territorio_estado" class="form-control" required>
                    <option value="activo">activo</option>
                    <option value="inactivo">inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Guardar territorio</button>
        </form>
    </div>
</div>

<!-- Modal editar territorio -->
<div class="territorio-modal" id="modalTerritorioEditar">
    <div class="territorio-modal-content">
        <div class="territorio-modal-header">
            <h3><i class="fas fa-edit"></i> Editar territorio</h3>
            <button type="button" class="close-modal" data-close-modal>&times;</button>
        </div>
        <form action="<?= BASE_PATH ?>/territorio/updateTerritorio" method="POST">
            <input type="hidden" name="id" id="edit_territorio_id">
            <div class="form-group">
                <label for="edit_territorio_nombre">Nombre del territorio</label>
                <input type="text" name="nombre" id="edit_territorio_nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edit_territorio_descripcion">Descripción</label>
                <textarea name="descripcion" id="edit_territorio_descripcion" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="edit_territorio_estado">Estado</label>
                <select name="estado" id="edit_territorio_estado" class="form-control" required>
                    <option value="activo">activo</option>
                    <option value="inactivo">inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Actualizar territorio</button>
        </form>
    </div>
</div>

<!-- Modal crear casa -->
<div class="territorio-modal" id="modalCasaCrear">
    <div class="territorio-modal-content">
        <div class="territorio-modal-header">
            <h3><i class="fas fa-home"></i> Nueva casa</h3>
            <button type="button" class="close-modal" data-close-modal>&times;</button>
        </div>
        <form action="<?= BASE_PATH ?>/territorio/storeCasa" method="POST">
            <div class="form-grid-2">
                <div class="form-group">
                    <label for="casa_sector_id">Territorio</label>
                    <select name="sector_id" id="casa_sector_id" class="form-control" required>
                        <option value="">Seleccione territorio...</option>
                        <?php foreach ($sectoresActivos as $sector): ?>
                            <option value="<?= h($sector['id']) ?>"><?= h($sector['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="casa_cliente_id">Cliente</label>
                    <select name="cliente_id" id="casa_cliente_id" class="form-control">
                        <option value="">Sin asignar</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= h($cliente['id']) ?>"><?= h($cliente['nombre']) ?> <?= $cliente['identificador'] ? '(' . h($cliente['identificador']) . ')' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="casa_direccion">Dirección / referencia de la casa</label>
                <input type="text" name="direccion" id="casa_direccion" class="form-control" placeholder="Ej. Barrio El Centro, casa #12" required>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="casa_lat">Latitud</label>
                    <input type="text" name="lat" id="casa_lat" class="form-control" placeholder="Opcional">
                </div>
                <div class="form-group">
                    <label for="casa_lng">Longitud</label>
                    <input type="text" name="lng" id="casa_lng" class="form-control" placeholder="Opcional">
                </div>
            </div>

            <div class="form-group">
                <label for="casa_estado">Estado</label>
                <select name="estado" id="casa_estado" class="form-control" required>
                    <option value="Activa">Activa</option>
                    <option value="Suspendida">Suspendida</option>
                    <option value="En revisión" selected>En revisión</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Guardar casa</button>
        </form>
    </div>
</div>

<!-- Modal editar casa -->
<div class="territorio-modal" id="modalCasaEditar">
    <div class="territorio-modal-content">
        <div class="territorio-modal-header">
            <h3><i class="fas fa-edit"></i> Editar casa</h3>
            <button type="button" class="close-modal" data-close-modal>&times;</button>
        </div>
        <form action="<?= BASE_PATH ?>/territorio/updateCasa" method="POST">
            <input type="hidden" name="id" id="edit_casa_id">

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="edit_casa_sector_id">Territorio</label>
                    <select name="sector_id" id="edit_casa_sector_id" class="form-control" required>
                        <?php foreach ($sectores as $sector): ?>
                            <option value="<?= h($sector['id']) ?>"><?= h($sector['nombre']) ?><?= $sector['estado'] === 'inactivo' ? ' (inactivo)' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_casa_cliente_id">Cliente</label>
                    <select name="cliente_id" id="edit_casa_cliente_id" class="form-control">
                        <option value="">Sin asignar</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= h($cliente['id']) ?>"><?= h($cliente['nombre']) ?> <?= $cliente['identificador'] ? '(' . h($cliente['identificador']) . ')' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="edit_casa_direccion">Dirección / referencia de la casa</label>
                <input type="text" name="direccion" id="edit_casa_direccion" class="form-control" required>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="edit_casa_lat">Latitud</label>
                    <input type="text" name="lat" id="edit_casa_lat" class="form-control">
                </div>
                <div class="form-group">
                    <label for="edit_casa_lng">Longitud</label>
                    <input type="text" name="lng" id="edit_casa_lng" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="edit_casa_estado">Estado</label>
                <select name="estado" id="edit_casa_estado" class="form-control" required>
                    <option value="Activa">Activa</option>
                    <option value="Suspendida">Suspendida</option>
                    <option value="En revisión">En revisión</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Actualizar casa</button>
        </form>
    </div>
</div>

<script>
window.HIDRA_TERRITORIO = <?= json_encode($territorioPayload, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= BASE_PATH ?>/assets/js/territorio.js"></script>
