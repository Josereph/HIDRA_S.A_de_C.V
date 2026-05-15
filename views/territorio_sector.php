<link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/territorio.css">

<div class="territorio-header">
    <div>
        <a href="<?= BASE_PATH ?>/territorio" class="btn btn-secondary btn-sm" style="margin-bottom: 10px;">
            <i class="bi bi-arrow-left"></i> Volver a Territorios
        </a>
        <h2><i class="bi bi-pin-map-fill" style="color: var(--color-primary);"></i> Sector: <?= htmlspecialchars($sector['nombre']) ?></h2>
        <p style="color: var(--color-text-light);">
            <?= htmlspecialchars($sector['departamento'] ?? '-') ?> &gt; 
            <?= htmlspecialchars($sector['municipio'] ?? '-') ?> &gt; 
            <?= htmlspecialchars($sector['canton'] ?? '-') ?> &gt; 
            <?= htmlspecialchars($sector['villa'] ?? '-') ?>
        </p>
    </div>
    <button class="btn btn-primary" onclick="openNewHouseModal()">
        <i class="bi bi-house-add"></i> Agregar Vivienda
    </button>
</div>

<div class="card" style="margin-bottom: 30px;">
    <div class="card-header">
        <h3><i class="bi bi-map"></i> Ubicación de Viviendas en el Sector</h3>
    </div>
    <div id="map"></div>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3><i class="bi bi-houses-fill"></i> Listado de Viviendas</h3>
        <input type="text" id="filterHouses" class="form-control" placeholder="Buscar por cliente o dirección..." style="max-width: 300px;" onkeyup="filterHouseCards()">
    </div>
    <div style="padding: 20px;">
        <?php if (empty($casas)): ?>
            <p>No hay viviendas registradas en este sector.</p>
        <?php else: ?>
            <div class="house-grid" id="houseGrid">
                <?php foreach ($casas as $casa): ?>
                    <div class="house-card house-item" onclick="window.location.href='<?= BASE_PATH ?>/territorio/casa?id=<?= $casa['id'] ?>'">
                        <div class="house-icon"><i class="bi bi-house-check-fill"></i></div>
                        <div class="house-details">
                            <h5 class="house-dir"><?= htmlspecialchars($casa['direccion']) ?></h5>
                            <p><i class="bi bi-person-fill"></i> <span class="house-client"><?= htmlspecialchars($casa['cliente_nombre'] ?? 'Sin asignar') ?></span></p>
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
                                <div>
                                    <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); openEditHouseModal(<?= htmlspecialchars(json_encode($casa)) ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?= BASE_PATH ?>/territorio/delete_casa" method="POST" style="display:inline;" onsubmit="event.stopPropagation(); return confirm('¿Seguro que deseas eliminar esta vivienda?');">
                                        <input type="hidden" name="id" value="<?= $casa['id'] ?>">
                                        <input type="hidden" name="sector_id" value="<?= $sector['id'] ?>">
                                        <input type="hidden" name="redirect_to_sector" value="1">
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
        <?php endif; ?>
    </div>
</div>

<!-- Modal Nueva/Editar Casa -->
<div id="houseModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="houseModalTitle"><i class="bi bi-house-add"></i> Gestionar Vivienda</h3>
            <button class="close-modal" onclick="closeModal('houseModal')">&times;</button>
        </div>
        <form id="houseForm" method="POST">
            <input type="hidden" name="house_id" id="h_id">
            <input type="hidden" name="sector_id" value="<?= $sector['id'] ?>">
            <input type="hidden" name="redirect_to_sector" value="1">
            <input type="hidden" name="lat" id="form_lat">
            <input type="hidden" name="lng" id="form_lng">
            
            <div class="form-group">
                <label>Coordenadas (Clic en el mapa para auto-llenar)</label>
                <input type="text" id="coord_display" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label>Nombre Identificador (Opcional)</label>
                <input type="text" name="nombre" id="h_nombre" class="form-control" placeholder="Ej. Casa de Doña Maria">
            </div>

            <div class="form-group">
                <label>Dirección Detallada *</label>
                <input type="text" name="direccion" id="h_direccion" class="form-control" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Asignar Cliente</label>
                    <select name="cliente_id" id="h_cliente_id" class="form-control">
                        <option value="">-- Sin asignar --</option>
                        <?php foreach ($clientes as $cli): ?>
                            <option value="<?= $cli['id'] ?>"><?= htmlspecialchars($cli['nombre']) ?> (<?= htmlspecialchars($cli['identificador']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" id="h_estado" class="form-control" required>
                        <option value="En revisión">En revisión</option>
                        <option value="Activa">Activa</option>
                        <option value="Suspendida">Suspendida</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Número de Medidor Asignado</label>
                <input type="text" name="numero_medidor" id="h_numero_medidor" class="form-control" placeholder="Ej. MED-001">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Guardar Vivienda</button>
        </form>
    </div>
</div>

<script>
function filterHouseCards() {
    let input = document.getElementById('filterHouses').value.toLowerCase();
    let cards = document.querySelectorAll('.house-item');
    cards.forEach(card => {
        let dir = card.querySelector('.house-dir').innerText.toLowerCase();
        let cli = card.querySelector('.house-client').innerText.toLowerCase();
        if (dir.includes(input) || cli.includes(input)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function closeAnimatedModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
        if (id === 'houseModal' && tempMarker) {
            map.removeLayer(tempMarker);
            tempMarker = null;
        }
    }, 400);
}

function openAnimatedModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = 'flex';
    void modal.offsetWidth;
    modal.classList.add('show');
}

function closeModal(id) {
    closeAnimatedModal(id);
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeAnimatedModal(event.target.id);
    }
}

function openNewHouseModal(lat = '', lng = '') {
    document.getElementById('houseModalTitle').innerHTML = '<i class="bi bi-house-add"></i> Registrar Nueva Vivienda';
    document.getElementById('houseForm').action = '<?= BASE_PATH ?>/territorio/store';
    document.getElementById('h_id').value = '';
    document.getElementById('h_nombre').value = '';
    document.getElementById('h_direccion').value = '';
    document.getElementById('h_cliente_id').value = '';
    document.getElementById('h_estado').value = 'En revisión';
    document.getElementById('h_numero_medidor').value = '';
    
    document.getElementById('form_lat').value = lat;
    document.getElementById('form_lng').value = lng;
    document.getElementById('coord_display').value = lat && lng ? `${lat}, ${lng}` : 'Haz clic en el mapa';
    
    openAnimatedModal('houseModal');
}

function openEditHouseModal(casa) {
    document.getElementById('houseModalTitle').innerHTML = '<i class="bi bi-pencil"></i> Editar Vivienda';
    document.getElementById('houseForm').action = '<?= BASE_PATH ?>/territorio/update';
    document.getElementById('h_id').value = casa.id;
    document.getElementById('h_nombre').value = casa.nombre || '';
    document.getElementById('h_direccion').value = casa.direccion;
    document.getElementById('h_cliente_id').value = casa.cliente_id || '';
    document.getElementById('h_estado').value = casa.estado || 'En revisión';
    document.getElementById('h_numero_medidor').value = casa.numero_medidor || '';
    
    document.getElementById('form_lat').value = casa.lat || '';
    document.getElementById('form_lng').value = casa.lng || '';
    document.getElementById('coord_display').value = casa.lat && casa.lng ? `${casa.lat}, ${casa.lng}` : '';
    
    openAnimatedModal('houseModal');
}

// Configuración del Mapa
const map = L.map('map').setView([13.794185, -88.89653], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap'
}).addTo(map);

const bounds = [[13.0, -90.5], [14.5, -87.5]];
map.setMaxBounds(bounds);
map.on('drag', function() { map.panInsideBounds(bounds, { animate: false }); });

const casas = <?= json_encode($casas) ?>;
const customIcon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34]
});

let firstMarker = true;
const markers = L.featureGroup();

casas.forEach(casa => {
    if (casa.lat && casa.lng) {
        let marker = L.marker([casa.lat, casa.lng], {icon: customIcon})
         .bindPopup(`<b>${casa.direccion}</b><br>Estado: ${casa.estado}<br>Cliente: ${casa.cliente_nombre || 'Sin asignar'}`);
        markers.addLayer(marker);
    }
});

map.addLayer(markers);
if(casas.length > 0 && markers.getBounds().isValid()) {
    map.fitBounds(markers.getBounds(), {padding: [50, 50], maxZoom: 16});
}

let tempMarker;
map.on('click', function(e) {
    if (tempMarker) map.removeLayer(tempMarker);
    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);
    tempMarker = L.marker([lat, lng]).addTo(map);
    openNewHouseModal(lat, lng);
});
</script>
