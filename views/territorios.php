<?php
// Lógica directa para la vista de territorio incluida en la SPA
require_once __DIR__ . '/../../models/Territorio.php';
require_once __DIR__ . '/../../models/Cliente.php';

if (!defined('BASE_PATH')) {
    define('BASE_PATH', '/HIDRA_S.A_de_C.V');
}

$tModel = new Territorio();
$cModel = new Cliente();

// Manejo de POST directamente aquí para evitar error 404 por falta de .htaccess
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'store_sector') {
        $tModel->createSector($_POST);
    } elseif ($action === 'update_sector') {
        $tModel->updateSector($_POST);
    } elseif ($action === 'delete_sector') {
        $tModel->deleteSector($_POST['id']);
    } elseif ($action === 'store_casa') {
        $tModel->createCasa($_POST);
    } elseif ($action === 'update_casa') {
        $tModel->updateCasa($_POST);
    } elseif ($action === 'delete_casa') {
        $tModel->deleteCasa($_POST['id']);
    }
    echo "<script>window.location.href = '" . BASE_PATH . "/views/layouts/pagina_principal.php';</script>";
    // No usamos exit() directo aquí porque cortaría el HTML y rompería la SPA,
    // el script de JS se encargará de hacer el Post-Redirect-Get instantáneamente.
}

$sectores_db = $tModel->getSectores();
$casas_db = $tModel->getCasas();
$clientes_db = $cModel->getAll();

// Agrupar casas por sector
$casasPorSector = [];
foreach ($sectores_db as $sector) {
    $casasPorSector[$sector['id']] = [];
}
foreach ($casas_db as $casa) {
    if (isset($casasPorSector[$casa['sector_id']])) {
        $casasPorSector[$casa['sector_id']][] = $casa;
    }
}

// Agrupar sectores por jerarquía
$jerarquia = [];
foreach ($sectores_db as $sec) {
    $dep = $sec['departamento'] ?: 'Sin Departamento';
    $mun = $sec['municipio'] ?: 'Sin Municipio';
    $can = $sec['canton'] ?: 'Sin Cantón';
    $vil = $sec['villa'] ?: 'Sin Villa';

    if (!isset($jerarquia[$dep])) $jerarquia[$dep] = [];
    if (!isset($jerarquia[$dep][$mun])) $jerarquia[$dep][$mun] = [];
    if (!isset($jerarquia[$dep][$mun][$can])) $jerarquia[$dep][$mun][$can] = [];
    if (!isset($jerarquia[$dep][$mun][$can][$vil])) $jerarquia[$dep][$mun][$can][$vil] = [];
    
    $jerarquia[$dep][$mun][$can][$vil][] = $sec;
}
?>

<link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/territorio.css">

<div class="view" id="view-territorio">

  <div class="territorio-header" style="margin-bottom: 20px;">
      <div>
          <h2 style="font-size: 1.8rem; margin-bottom: 5px;"><i class="bi bi-geo-alt-fill" style="color: var(--color-primary);"></i> Gestión de Territorios</h2>
          <p style="color: var(--color-text-light);">Organización jerárquica, sectores, casas e inmuebles</p>
      </div>
      <div class="btn-group">
          <button class="btn btn-primary" onclick="openNewSectorModal()">
              <i class="bi bi-diagram-3"></i> + Sector
          </button>
          <button class="btn btn-secondary" onclick="openNewHouseModal()">
              <i class="bi bi-house-add"></i> + Casa
          </button>
      </div>
  </div>

  <div class="section-tabs" data-group="terr-tabs">
    <div class="section-tab active" data-panel="terr-jerarquia" data-group="terr-tabs"><i class="bi bi-diagram-3-fill"></i> Jerarquía de Sectores</div>
    <div class="section-tab"        data-panel="terr-casas"     data-group="terr-tabs"><i class="bi bi-houses-fill"></i> Listado de Casas</div>
    <div class="section-tab"        data-panel="terr-mapa"      data-group="terr-tabs"><i class="bi bi-map-fill"></i> Mapa General</div>
  </div>

  <!-- ── TAB 1: JERARQUÍA DE SECTORES ─────────────────────────── -->
  <div class="tab-panel active" data-panel="terr-jerarquia" data-group="terr-tabs">
      <div class="card">
          <div style="padding: 20px;">
              <?php if (empty($jerarquia)): ?>
                  <p>No hay sectores registrados. Registra uno nuevo.</p>
              <?php else: ?>
                  <?php foreach ($jerarquia as $dep => $municipios): ?>
                      <div class="hierarchy-group">
                          <div class="hierarchy-level-dept">
                              <div><i class="bi bi-map"></i> <strong>Departamento:</strong> <?= htmlspecialchars($dep) ?></div>
                          </div>
                          <?php foreach ($municipios as $mun => $cantones): ?>
                              <div class="hierarchy-level-muni">
                                  <i class="bi bi-geo"></i> <strong>Municipio:</strong> <?= htmlspecialchars($mun) ?>
                              </div>
                              <?php foreach ($cantones as $can => $villas): ?>
                                  <div class="hierarchy-level-canton">
                                      <i class="bi bi-signpost-split"></i> <strong>Cantón:</strong> <?= htmlspecialchars($can) ?>
                                  </div>
                                  <?php foreach ($villas as $vil => $sectoresList): ?>
                                      <div class="hierarchy-level-villa">
                                          <i class="bi bi-pin-map"></i> <strong>Villa/Caserío:</strong> <?= htmlspecialchars($vil) ?>
                                      </div>
                                      <div style="margin-left: 120px; margin-bottom: 20px;">
                                          <?php foreach ($sectoresList as $sec): ?>
                                              <div class="sector-card">
                                                  <div class="sector-info">
                                                      <h4 style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                                          <i class="bi bi-geo-alt-fill" style="color: var(--color-primary);"></i> 
                                                          <?= htmlspecialchars($sec['nombre']) ?>
                                                      </h4>
                                                      <p style="margin: 5px 0 0 0;"><i class="bi bi-info-circle"></i> <?= htmlspecialchars($sec['descripcion'] ?? 'Sin descripción') ?></p>
                                                      <div style="margin-top: 8px;">
                                                          <span class="badge badge-<?= $sec['estado'] === 'activo' ? 'active' : 'suspended' ?>">
                                                              <?= ucfirst(htmlspecialchars($sec['estado'])) ?>
                                                          </span>
                                                          <span style="font-size: 0.85rem; color: #666; margin-left: 10px;">
                                                              <i class="bi bi-house"></i> <?= count($casasPorSector[$sec['id']] ?? []) ?> casas
                                                          </span>
                                                      </div>
                                                  </div>
                                                  <div class="sector-actions" style="display: flex; gap: 5px;">
                                                      <button class="btn btn-secondary btn-sm" onclick='openEditSectorModal(<?= json_encode($sec, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                          <i class="bi bi-pencil"></i> Editar
                                                      </button>
                                                      <form action="" method="POST" style="margin:0;" onsubmit="return confirm('¿Seguro que deseas eliminar este sector?');">
                                                          <input type="hidden" name="action" value="delete_sector">
                                                          <input type="hidden" name="id" value="<?= $sec['id'] ?>">
                                                          <button type="submit" class="btn btn-danger btn-sm">
                                                              <i class="bi bi-trash"></i> Eliminar
                                                          </button>
                                                      </form>
                                                  </div>
                                              </div>
                                          <?php endforeach; ?>
                                      </div>
                                  <?php endforeach; ?>
                              <?php endforeach; ?>
                          <?php endforeach; ?>
                      </div>
                  <?php endforeach; ?>
              <?php endif; ?>
          </div>
      </div>
  </div>

  <!-- ── TAB 2: CASAS ────────────────────────────── -->
  <div class="tab-panel" data-panel="terr-casas" data-group="terr-tabs">
      <div class="card">
          <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
              <div class="search-bar" style="width: 300px; max-width: 100%;">
                  <span class="search-icon"><i class="bi bi-search"></i></span>
                  <input type="text" id="casaSearchGlobal" placeholder="Buscar dirección o cliente..." onkeyup="filterGlobalHouses()" />
              </div>
              <select id="sectorFilterGlobal" class="form-control" style="width: auto;" onchange="filterGlobalHouses()">
                  <option value="">Todos los sectores</option>
                  <?php foreach($sectores_db as $s): ?>
                      <option value="<?= htmlspecialchars($s['nombre']) ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div style="padding: 20px;">
              <div class="house-grid" id="globalHouseGrid">
                  <?php foreach ($casas_db as $casa): ?>
                      <?php
                          $sectorNombre = '';
                          foreach ($sectores_db as $s) { if ($s['id'] == $casa['sector_id']) $sectorNombre = $s['nombre']; }
                      ?>
                      <div class="house-card gh-item" data-sector="<?= htmlspecialchars($sectorNombre) ?>">
                          <div class="house-icon"><i class="bi bi-house-check-fill"></i></div>
                          <div class="house-details">
                              <h5 class="gh-dir"><?= htmlspecialchars($casa['direccion']) ?></h5>
                              <p><i class="bi bi-geo-alt"></i> Sector: <?= htmlspecialchars($sectorNombre) ?></p>
                              <p><i class="bi bi-person-fill"></i> <span class="gh-cli"><?= htmlspecialchars($casa['cliente_nombre'] ?? 'Sin asignar') ?></span></p>
                              <?php if ($casa['numero_medidor']): ?>
                                  <p><i class="bi bi-speedometer2"></i> Medidor: <?= htmlspecialchars($casa['numero_medidor']) ?></p>
                              <?php endif; ?>
                              
                              <div class="house-status">
                                  <?php
                                  $badgeClass = 'badge-review';
                                  if ($casa['estado'] === 'Activa') $badgeClass = 'badge-active';
                                  if ($casa['estado'] === 'Suspendida') $badgeClass = 'badge-suspended';
                                  ?>
                                  <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($casa['estado']) ?></span>
                                  <div style="display: flex; gap: 5px;">
                                      <button class="btn btn-secondary btn-sm" onclick='openEditHouseModal(<?= json_encode($casa, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                          <i class="bi bi-pencil"></i>
                                      </button>
                                      <form action="" method="POST" style="margin:0;" onsubmit="return confirm('¿Seguro que deseas eliminar esta vivienda?');">
                                          <input type="hidden" name="action" value="delete_casa">
                                          <input type="hidden" name="id" value="<?= $casa['id'] ?>">
                                          <button type="submit" class="btn btn-danger btn-sm">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  <?php endforeach; ?>
              </div>
          </div>
      </div>
  </div>

  <!-- ── TAB 3: MAPA GENERAL ─────────────────── -->
  <div class="tab-panel" data-panel="terr-mapa" data-group="terr-tabs">
      <div class="card">
          <div class="card-header">
              <h3><i class="bi bi-map-fill"></i> Mapa Geográfico Completo</h3>
              <p style="font-size: 0.85rem; color: var(--color-text-light); margin: 0;">Haz clic en el mapa para registrar una nueva vivienda en esa ubicación.</p>
          </div>
          <div id="map" style="height: 500px; width: 100%; border-radius: 0 0 8px 8px;"></div>
      </div>
  </div>

</div><!-- /view-territorio -->


<div class="modal-overlay" id="sectorModal" style="display:none;">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title" id="sectorModalTitle"><i class="bi bi-geo-alt"></i> Gestionar Sector</span>
      <button type="button" class="modal-close" onclick="closeTerritoryModal('sectorModal')"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-body">
      <form id="sectorForm" action="" method="POST">
        <input type="hidden" name="action" id="sec_action" value="store_sector">
        <input type="hidden" name="id" id="sec_id">
        
        <div class="form-group">
            <label class="form-label">Nombre del Sector *</label>
            <input type="text" name="nombre" id="sec_nombre" class="form-control" required>
        </div>
        <div class="form-group"><label class="form-label">Departamento</label><input type="text" name="departamento" id="sec_departamento" class="form-control"></div>
        <div class="form-group"><label class="form-label">Municipio</label><input type="text" name="municipio" id="sec_municipio" class="form-control"></div>
        <div class="form-group"><label class="form-label">Cantón</label><input type="text" name="canton" id="sec_canton" class="form-control"></div>
        <div class="form-group"><label class="form-label">Villa / Caserío</label><input type="text" name="villa" id="sec_villa" class="form-control"></div>
        
        <div class="form-group">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" id="sec_descripcion" class="form-control">
        </div>
        <div class="form-group">
            <label class="form-label">Estado</label>
            <select name="estado" id="sec_estado" class="form-control form-select">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost" onclick="closeTerritoryModal('sectorModal')">Cancelar</button>
      <button type="submit" form="sectorForm" class="btn btn-primary">💾 Guardar</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="houseModal" style="display:none;">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title" id="houseModalTitle"><i class="bi bi-house-add"></i> Gestionar Vivienda</span>
      <button type="button" class="modal-close" onclick="closeTerritoryModal('houseModal')"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="modal-body">
      <form id="houseForm" action="" method="POST">
        <input type="hidden" name="action" id="h_action" value="store_casa">
        <input type="hidden" name="house_id" id="h_id">
        <input type="hidden" name="lat" id="form_lat">
        <input type="hidden" name="lng" id="form_lng">
        
        <div class="form-group">
            <label class="form-label">Sector al que pertenece *</label>
            <select name="sector_id" id="h_sector_id" class="form-control form-select" required>
                <option value="">Seleccione un sector...</option>
                <?php foreach ($sectores_db as $sec): ?>
                    <option value="<?= $sec['id'] ?>"><?= htmlspecialchars($sec['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Coordenadas</label>
            <input type="text" id="coord_display" class="form-control" disabled value="Haz clic en el mapa">
        </div>
        <div class="form-group">
            <label class="form-label">Nombre Identificador (Opcional)</label>
            <input type="text" name="nombre" id="h_nombre" class="form-control">
        </div>
        <div class="form-group">
            <label class="form-label">Dirección Detallada *</label>
            <input type="text" name="direccion" id="h_direccion" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Cliente Asignado</label>
            <select name="cliente_id" id="h_cliente_id" class="form-control form-select">
                <option value="">-- Ninguno --</option>
                <?php foreach ($clientes_db as $cli): ?>
                    <option value="<?= $cli['id'] ?>"><?= htmlspecialchars($cli['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Estado</label>
            <select name="estado" id="h_estado" class="form-control form-select" required>
                <option value="En revisión">En revisión</option>
                <option value="Activa">Activa</option>
                <option value="Suspendida">Suspendida</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Número de Medidor (Si aplica)</label>
            <input type="text" name="numero_medidor" id="h_numero_medidor" class="form-control">
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-ghost" onclick="closeTerritoryModal('houseModal')">Cancelar</button>
      <button type="submit" form="houseForm" class="btn btn-primary">💾 Guardar</button>
    </div>
  </div>
</div>

<script>
function closeTerritoryModal(id) {
    const overlay = document.getElementById(id);
    if(overlay) {
        overlay.classList.remove('visible');
        setTimeout(() => { 
            overlay.style.display = 'none'; 
            if(id === 'houseModal' && typeof tempMarker !== 'undefined' && tempMarker) {
                if (typeof mapaGlobal !== 'undefined' && mapaGlobal) mapaGlobal.removeLayer(tempMarker);
                tempMarker = null;
            }
        }, 280);
    }
}

function openTerritoryModal(id) {
    const overlay = document.getElementById(id);
    if(overlay) {
        overlay.style.display = 'flex';
        requestAnimationFrame(() => overlay.classList.add('visible'));
    }
}

window.addEventListener('click', function(e) { 
    if(e.target.classList.contains('modal-overlay') && (e.target.id === 'sectorModal' || e.target.id === 'houseModal')) {
        closeTerritoryModal(e.target.id); 
    }
});

function openNewSectorModal() {
    document.getElementById('sectorModalTitle').innerHTML = '<i class="bi bi-plus-circle"></i> Nuevo Sector';
    document.getElementById('sec_action').value = 'store_sector';
    document.getElementById('sec_id').value = '';
    document.getElementById('sec_nombre').value = '';
    document.getElementById('sec_departamento').value = '';
    document.getElementById('sec_municipio').value = '';
    document.getElementById('sec_canton').value = '';
    document.getElementById('sec_villa').value = '';
    document.getElementById('sec_descripcion').value = '';
    openTerritoryModal('sectorModal');
}

function openEditSectorModal(sec) {
    document.getElementById('sectorModalTitle').innerHTML = '<i class="bi bi-pencil"></i> Editar Sector';
    document.getElementById('sec_action').value = 'update_sector';
    document.getElementById('sec_id').value = sec.id;
    document.getElementById('sec_nombre').value = sec.nombre;
    document.getElementById('sec_departamento').value = sec.departamento || '';
    document.getElementById('sec_municipio').value = sec.municipio || '';
    document.getElementById('sec_canton').value = sec.canton || '';
    document.getElementById('sec_villa').value = sec.villa || '';
    document.getElementById('sec_descripcion').value = sec.descripcion || '';
    document.getElementById('sec_estado').value = sec.estado || 'activo';
    openTerritoryModal('sectorModal');
}

function openNewHouseModal(lat='', lng='') {
    document.getElementById('houseModalTitle').innerHTML = '<i class="bi bi-house-add"></i> Registrar Vivienda';
    document.getElementById('h_action').value = 'store_casa';
    document.getElementById('h_id').value = '';
    document.getElementById('h_sector_id').value = '';
    document.getElementById('h_nombre').value = '';
    document.getElementById('h_direccion').value = '';
    document.getElementById('h_cliente_id').value = '';
    document.getElementById('h_numero_medidor').value = '';
    document.getElementById('h_estado').value = 'En revisión';
    document.getElementById('form_lat').value = lat;
    document.getElementById('form_lng').value = lng;
    document.getElementById('coord_display').value = (lat && lng) ? `${lat}, ${lng}` : 'Sin coordenadas';
    openTerritoryModal('houseModal');
}

function openEditHouseModal(casa) {
    document.getElementById('houseModalTitle').innerHTML = '<i class="bi bi-pencil"></i> Editar Vivienda';
    document.getElementById('h_action').value = 'update_casa';
    document.getElementById('h_id').value = casa.id;
    document.getElementById('h_sector_id').value = casa.sector_id || '';
    document.getElementById('h_nombre').value = casa.nombre || '';
    document.getElementById('h_direccion').value = casa.direccion;
    document.getElementById('h_cliente_id').value = casa.cliente_id || '';
    document.getElementById('h_numero_medidor').value = casa.numero_medidor || '';
    document.getElementById('h_estado').value = casa.estado || 'En revisión';
    document.getElementById('form_lat').value = casa.lat || '';
    document.getElementById('form_lng').value = casa.lng || '';
    document.getElementById('coord_display').value = (casa.lat && casa.lng) ? `${casa.lat}, ${casa.lng}` : '';
    openTerritoryModal('houseModal');
}

function filterGlobalHouses() {
    const q = document.getElementById('casaSearchGlobal').value.toLowerCase();
    const sec = document.getElementById('sectorFilterGlobal').value;
    document.querySelectorAll('.gh-item').forEach(el => {
        const text = (el.querySelector('.gh-dir').innerText + ' ' + el.querySelector('.gh-cli').innerText).toLowerCase();
        const elSec = el.getAttribute('data-sector');
        if (text.includes(q) && (sec === '' || elSec === sec)) el.style.display = '';
        else el.style.display = 'none';
    });
}

// Inicialización de Mapa en su pestaña
let mapaGlobal = null;
let tempMarker = null;

document.querySelectorAll('[data-panel="terr-mapa"]').forEach(tab => {
    tab.addEventListener('click', () => {
        setTimeout(() => {
            if (!mapaGlobal) {
                mapaGlobal = L.map('map').setView([13.794185, -88.89653], 9);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(mapaGlobal);
                mapaGlobal.setMaxBounds([[13.0, -90.5], [14.5, -87.5]]);
                
                const icon = L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34]
                });
                
                const allCasas = <?= json_encode($casas_db) ?>;
                allCasas.forEach(c => {
                    if (c.lat && c.lng) {
                        L.marker([c.lat, c.lng], {icon: icon}).addTo(mapaGlobal)
                         .bindPopup(`<b>${c.direccion}</b><br>${c.cliente_nombre || 'Sin asignar'}`);
                    }
                });

                mapaGlobal.on('click', function(e) {
                    if (tempMarker) mapaGlobal.removeLayer(tempMarker);
                    const lat = e.latlng.lat.toFixed(6);
                    const lng = e.latlng.lng.toFixed(6);
                    tempMarker = L.marker([lat, lng]).addTo(mapaGlobal);
                    openNewHouseModal(lat, lng);
                });
            } else {
                mapaGlobal.invalidateSize();
            }
        }, 200);
    });
});
</script>
