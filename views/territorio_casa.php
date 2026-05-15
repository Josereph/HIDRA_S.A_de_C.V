<link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/territorio.css">

<div class="territorio-header">
    <div>
        <a href="<?= BASE_PATH ?>/territorio/sector?id=<?= $casa['sector_id'] ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 10px;">
            <i class="bi bi-arrow-left"></i> Volver al Sector
        </a>
        <h2><i class="bi bi-house-check-fill" style="color: var(--color-primary);"></i> Detalle de Vivienda</h2>
        <p style="color: var(--color-text-light);">
            Identificador: <?= htmlspecialchars($casa['id']) ?> | Sector: <?= htmlspecialchars($casa['sector_nombre']) ?>
        </p>
    </div>
</div>

<div class="house-detail-header">
    <div class="house-detail-title">
        <h2><?= htmlspecialchars($casa['direccion']) ?></h2>
        <?php if ($casa['nombre']): ?>
            <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 0;"><i class="bi bi-tag-fill"></i> <?= htmlspecialchars($casa['nombre']) ?></p>
        <?php endif; ?>
    </div>
    <div style="text-align: right;">
        <?php
        $badgeClass = 'badge-review';
        if ($casa['estado'] === 'Activa') $badgeClass = 'badge-active';
        if ($casa['estado'] === 'Suspendida') $badgeClass = 'badge-suspended';
        ?>
        <div style="margin-bottom: 10px;"><span class="badge <?= $badgeClass ?>" style="font-size: 1.2rem; padding: 10px 15px;"><?= htmlspecialchars($casa['estado']) ?></span></div>
        <div style="font-size: 0.9rem; opacity: 0.8;">Registrada: <?= htmlspecialchars(date('d/m/Y', strtotime($casa['fecha_creacion']))) ?></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <div>
        <div class="house-detail-card">
            <h3><i class="bi bi-person-lines-fill"></i> Información del Cliente Asignado</h3>
            <?php if ($casa['cliente_id']): ?>
                <div class="detail-row">
                    <div class="detail-label">Nombre completo:</div>
                    <div class="detail-value"><?= htmlspecialchars($casa['cliente_nombre']) ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Teléfono:</div>
                    <div class="detail-value"><?= htmlspecialchars($casa['cliente_telefono'] ?? 'No registrado') ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Correo:</div>
                    <div class="detail-value"><?= htmlspecialchars($casa['cliente_correo'] ?? 'No registrado') ?></div>
                </div>
                <div style="margin-top: 15px;">
                    <a href="<?= BASE_PATH ?>/clientes/ver?id=<?= $casa['cliente_id'] ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-vcard"></i> Ver Ficha del Cliente
                    </a>
                </div>
            <?php else: ?>
                <div style="padding: 20px 0; color: var(--color-text-light);">
                    <p><i class="bi bi-exclamation-circle"></i> Esta vivienda no tiene cliente asignado actualmente.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="house-detail-card">
            <h3><i class="bi bi-speedometer2"></i> Medición y Consumo</h3>
            <?php if ($casa['numero_medidor']): ?>
                <div class="detail-row">
                    <div class="detail-label">N° de Medidor:</div>
                    <div class="detail-value" style="font-family: monospace; font-size: 1.1rem; font-weight: bold;"><?= htmlspecialchars($casa['numero_medidor']) ?></div>
                </div>
                <div style="margin-top: 15px;">
                    <a href="<?= BASE_PATH ?>/operaciones" class="btn btn-secondary btn-sm">
                        <i class="bi bi-search"></i> Consultar Historial de Lecturas
                    </a>
                </div>
            <?php else: ?>
                <div style="padding: 20px 0; color: var(--color-text-light);">
                    <p><i class="bi bi-dash-circle"></i> No hay medidor registrado en esta vivienda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="house-detail-card">
            <h3><i class="bi bi-map"></i> Ubicación Geográfica</h3>
            <?php if ($casa['lat'] && $casa['lng']): ?>
                <div class="detail-row">
                    <div class="detail-label">Coordenadas:</div>
                    <div class="detail-value"><?= htmlspecialchars($casa['lat']) ?>, <?= htmlspecialchars($casa['lng']) ?></div>
                </div>
                <div id="mini-map" style="height: 250px; width: 100%; border-radius: 8px; margin-top: 15px;"></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const map = L.map('mini-map').setView([<?= $casa['lat'] ?>, <?= $casa['lng'] ?>], 16);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);
                        const customIcon = L.icon({
                            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34]
                        });
                        L.marker([<?= $casa['lat'] ?>, <?= $casa['lng'] ?>], {icon: customIcon}).addTo(map)
                         .bindPopup('<b><?= htmlspecialchars($casa['direccion']) ?></b>').openPopup();
                    });
                </script>
            <?php else: ?>
                <div style="padding: 20px 0; color: var(--color-text-light);">
                    <p><i class="bi bi-geo-alt"></i> No se han registrado coordenadas para esta vivienda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
