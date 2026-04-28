<style>
.sector-section { margin-bottom: 30px; }
.house-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 15px; }
.house-card { background: var(--color-white); border: 1px solid var(--color-border); border-radius: 8px; padding: 15px; box-shadow: var(--shadow-sm); transition: transform 0.2s; position: relative; }
.house-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.house-icon { font-size: 2rem; margin-bottom: 10px; color: var(--color-primary-light); }
.house-details h4 { margin-bottom: 5px; color: var(--color-primary-dark); }
.house-details p { font-size: 0.9rem; color: var(--color-text-light); margin-bottom: 10px; }
#map { height: 400px; width: 100%; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--color-border); }

/* Modales */
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
.modal-content { background-color: var(--color-white); padding: 25px; border-radius: 8px; width: 100%; max-width: 500px; box-shadow: var(--shadow-md); }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.close-modal { cursor: pointer; font-size: 1.5rem; color: var(--color-text-light); border: none; background: transparent; }
</style>

<div class="card">
    <div class="card-header">
        <h2><i class="bi bi-geo-alt-fill"></i> Mapa Geográfico de Viviendas</h2>
        <p style="color: var(--color-text-light); font-size: 0.9rem;">Haz clic en el mapa de El Salvador para registrar una nueva vivienda.</p>
    </div>
    <div id="map"></div>
</div>

<?php foreach ($sectores as $sector): ?>
    <div class="sector-section">
        <h3><i class="bi bi-geo-alt-fill" style="color: var(--color-primary);"></i> <?= htmlspecialchars($sector['nombre']) ?></h3>
        <div class="house-grid">
            <?php if (empty($casasPorSector[$sector['id']])): ?>
                <p>No hay casas en este sector.</p>
            <?php else: ?>
                <?php foreach ($casasPorSector[$sector['id']] as $casa): ?>
                    <div class="house-card">
                        <div class="house-icon"><i class="bi bi-house-door-fill"></i></div>
                        <div class="house-details">
                            <h4><?= htmlspecialchars($casa['direccion']) ?></h4>
                            <p><i class="bi bi-person-fill"></i> Cliente: <?= htmlspecialchars($casa['cliente_nombre'] ?? 'Sin asignar') ?></p>
                            
                            <?php
                            $badgeClass = 'badge-active';
                            if ($casa['estado'] === 'Suspendida') $badgeClass = 'badge-suspended';
                            if ($casa['estado'] === 'En revisión') $badgeClass = 'badge-review';
                            ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($casa['estado']) ?></span>
                                <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;" 
                                    onclick="openEditModal(<?= $casa['id'] ?>, <?= $casa['cliente_id'] ?? 'null' ?>, '<?= $casa['estado'] ?>')">
                                    <i class="bi bi-pencil-square"></i> Asignar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<!-- Modal Nueva Casa -->
<div id="newHouseModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="bi bi-plus-circle-fill"></i> Registrar Nueva Vivienda</h3>
            <button class="close-modal" onclick="closeModal('newHouseModal')">&times;</button>
        </div>
        <form action="/territorio/store" method="POST">
            <input type="hidden" name="lat" id="form_lat">
            <input type="hidden" name="lng" id="form_lng">
            
            <div class="form-group">
                <label>Coordenadas Seleccionadas</label>
                <input type="text" id="coord_display" class="form-control" disabled>
            </div>

            <div class="form-group">
                <label for="sector_id">Sector</label>
                <select name="sector_id" id="sector_id" class="form-control" required>
                    <option value="">Seleccione un sector...</option>
                    <?php foreach ($sectores as $sec): ?>
                        <option value="<?= $sec['id'] ?>"><?= htmlspecialchars($sec['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección Detallada (Lote, Polígono)</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Guardar Vivienda</button>
        </form>
    </div>
</div>

<!-- Modal Asignar Cliente/Estado -->
<div id="editHouseModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="bi bi-pencil-square"></i> Editar / Asignar Vivienda</h3>
            <button class="close-modal" onclick="closeModal('editHouseModal')">&times;</button>
        </div>
        <form action="/territorio/update" method="POST">
            <input type="hidden" name="house_id" id="edit_house_id">

            <div class="form-group">
                <label for="cliente_id">Asignar Cliente</label>
                <select name="cliente_id" id="edit_cliente_id" class="form-control">
                    <option value="">-- Sin asignar --</option>
                    <?php foreach ($clientes as $cli): ?>
                        <option value="<?= $cli['id'] ?>"><?= htmlspecialchars($cli['nombre']) ?> (<?= htmlspecialchars($cli['identificador']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="estado">Estado del Servicio</label>
                <select name="estado" id="edit_estado" class="form-control" required>
                    <option value="Activa">Activa</option>
                    <option value="Suspendida">Suspendida</option>
                    <option value="En revisión">En revisión</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Actualizar Vivienda</button>
        </form>
    </div>
</div>

<script>
// --- Configuración Leaflet ---
const map = L.map('map').setView([13.794185, -88.89653], 8); // Centro de El Salvador

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Limitar el mapa a El Salvador
const bounds = [
    [13.0, -90.5], // Suroeste
    [14.5, -87.5]  // Noreste
];
map.setMaxBounds(bounds);
map.on('drag', function() {
    map.panInsideBounds(bounds, { animate: false });
});

// Cargar pines de casas existentes
const casas = <?= json_encode($todasLasCasas) ?>;
const customIcon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
});

casas.forEach(casa => {
    if (casa.lat && casa.lng) {
        L.marker([casa.lat, casa.lng], {icon: customIcon}).addTo(map)
         .bindPopup(`<b>${casa.direccion}</b><br>Estado: ${casa.estado}<br>Cliente: ${casa.cliente_nombre || 'Sin asignar'}`);
    }
});

// Evento click en el mapa para registrar nueva casa
let tempMarker;
map.on('click', function(e) {
    if (tempMarker) map.removeLayer(tempMarker);
    
    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);
    
    tempMarker = L.marker([lat, lng]).addTo(map);
    
    // Abrir Modal de Creación
    document.getElementById('form_lat').value = lat;
    document.getElementById('form_lng').value = lng;
    document.getElementById('coord_display').value = `${lat}, ${lng}`;
    
    document.getElementById('newHouseModal').style.display = 'flex';
});

// --- Lógica de Modales ---
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    if (id === 'newHouseModal' && tempMarker) {
        map.removeLayer(tempMarker); // Quitar pin si cancela
    }
}

function openEditModal(houseId, clienteId, estado) {
    document.getElementById('edit_house_id').value = houseId;
    document.getElementById('edit_cliente_id').value = clienteId || '';
    document.getElementById('edit_estado').value = estado;
    document.getElementById('editHouseModal').style.display = 'flex';
}

// Cerrar modales si se hace clic fuera
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        if (event.target.id === 'newHouseModal' && tempMarker) {
            map.removeLayer(tempMarker);
        }
    }
}
</script>
