'use strict';

(function () {
  const data = window.HIDRA_TERRITORIO || { sectores: [], casas: [], primerSectorId: 0, basePath: '' };
  const sectores = Array.isArray(data.sectores) ? data.sectores : [];
  const casas = Array.isArray(data.casas) ? data.casas : [];

  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => Array.from(root.querySelectorAll(selector));

  function escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function casaBadgeClass(estado) {
    if (estado === 'Activa') return 'badge-active';
    if (estado === 'Suspendida') return 'badge-suspended';
    return 'badge-review';
  }

  function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('is-open');
  }

  function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('is-open');
  }

  function setSelectValue(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value ?? '';
  }

  function setInputValue(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value ?? '';
  }

  function getSectorById(id) {
    return sectores.find((sector) => String(sector.id) === String(id));
  }

  function renderCasasPorSector(sectorId) {
    const container = document.getElementById('casasSectorContainer');
    const empty = document.getElementById('casasSectorEmpty');
    const title = document.getElementById('casasSectorTitle');
    const selector = document.getElementById('sectorSelectorDetalle');

    if (!container || !empty) return;

    const sector = getSectorById(sectorId);
    const casasDelSector = casas
      .filter((casa) => String(casa.sector_id) === String(sectorId))
      .sort((a, b) => String(a.direccion || '').localeCompare(String(b.direccion || ''), 'es'));

    if (selector) selector.value = sectorId;

    $$('#territorioCardGrid [data-sector-card]').forEach((card) => {
      card.classList.toggle('is-selected', String(card.dataset.sectorCard) === String(sectorId));
    });

    if (title) {
      title.innerHTML = `<i class="fas fa-home"></i> Casas de ${escapeHtml(sector ? sector.nombre : 'territorio')}`;
    }

    if (casasDelSector.length === 0) {
      container.innerHTML = '';
      empty.style.display = 'block';
      return;
    }

    empty.style.display = 'none';
    container.innerHTML = casasDelSector.map((casa) => {
      const cliente = casa.cliente_nombre || 'Sin asignar';
      const coords = casa.lat && casa.lng ? `${casa.lat}, ${casa.lng}` : 'Sin coordenadas';
      const estado = casa.estado || 'En revisión';
      const clienteCodigo = casa.cliente_codigo ? ` · ${escapeHtml(casa.cliente_codigo)}` : '';

      return `
        <article class="house-card">
          <h4><i class="fas fa-home"></i> ${escapeHtml(casa.direccion)}</h4>
          <p><i class="fas fa-user"></i> Cliente: ${escapeHtml(cliente)}${clienteCodigo}</p>
          <p><i class="fas fa-map-pin"></i> Coordenadas: ${escapeHtml(coords)}</p>
          <div class="house-card-footer">
            <span class="badge ${casaBadgeClass(estado)}">${escapeHtml(estado)}</span>
            <button type="button"
                    class="btn btn-secondary btn-sm"
                    data-edit-casa
                    data-id="${escapeHtml(casa.id)}"
                    data-sector-id="${escapeHtml(casa.sector_id)}"
                    data-cliente-id="${escapeHtml(casa.cliente_id || '')}"
                    data-direccion="${escapeHtml(casa.direccion)}"
                    data-lat="${escapeHtml(casa.lat || '')}"
                    data-lng="${escapeHtml(casa.lng || '')}"
                    data-estado="${escapeHtml(estado)}">
              Editar
            </button>
          </div>
        </article>
      `;
    }).join('');
  }

  function fillEditTerritorio(button) {
    setInputValue('edit_territorio_id', button.dataset.id);
    setInputValue('edit_territorio_nombre', button.dataset.nombre);
    setInputValue('edit_territorio_descripcion', button.dataset.descripcion);
    setSelectValue('edit_territorio_estado', button.dataset.estado || 'activo');
    openModal('modalTerritorioEditar');
  }

  function fillEditCasa(button) {
    setInputValue('edit_casa_id', button.dataset.id);
    setSelectValue('edit_casa_sector_id', button.dataset.sectorId);
    setSelectValue('edit_casa_cliente_id', button.dataset.clienteId || '');
    setInputValue('edit_casa_direccion', button.dataset.direccion);
    setInputValue('edit_casa_lat', button.dataset.lat);
    setInputValue('edit_casa_lng', button.dataset.lng);
    setSelectValue('edit_casa_estado', button.dataset.estado || 'En revisión');
    openModal('modalCasaEditar');
  }

  function bindModals() {
    document.addEventListener('click', (event) => {
      const openButton = event.target.closest('[data-open-modal]');
      if (openButton) {
        openModal(openButton.dataset.openModal);
        return;
      }

      const closeButton = event.target.closest('[data-close-modal]');
      if (closeButton) {
        closeModal(closeButton.closest('.territorio-modal'));
        return;
      }

      const modalBackdrop = event.target.classList.contains('territorio-modal') ? event.target : null;
      if (modalBackdrop) {
        closeModal(modalBackdrop);
        return;
      }

      const editTerritorio = event.target.closest('[data-edit-territorio]');
      if (editTerritorio) {
        fillEditTerritorio(editTerritorio);
        return;
      }

      const editCasa = event.target.closest('[data-edit-casa]');
      if (editCasa) {
        fillEditCasa(editCasa);
        return;
      }

      const sectorCard = event.target.closest('[data-sector-card]');
      if (sectorCard) {
        const sectorId = sectorCard.dataset.sectorCard;
        renderCasasPorSector(sectorId);
        document.getElementById('casasPorTerritorioCard')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        $$('.territorio-modal.is-open').forEach(closeModal);
      }
    });
  }

  function bindSectorSelector() {
    const selector = document.getElementById('sectorSelectorDetalle');
    if (!selector) return;
    selector.addEventListener('change', () => renderCasasPorSector(selector.value));
  }

  function initMap() {
    const mapEl = document.getElementById('mapTerritorio');
    if (!mapEl) return;

    if (typeof L === 'undefined') {
      mapEl.innerHTML = '<div class="detail-empty" style="height:100%;display:flex;align-items:center;justify-content:center;">No se pudo cargar Leaflet. Revisa conexión a internet o la librería del mapa.</div>';
      return;
    }

    const map = L.map('mapTerritorio').setView([13.794185, -88.89653], 8);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const bounds = [
      [13.0, -90.5],
      [14.5, -87.5]
    ];

    map.setMaxBounds(bounds);
    map.on('drag', () => map.panInsideBounds(bounds, { animate: false }));

    casas.forEach((casa) => {
      if (!casa.lat || !casa.lng) return;
      L.marker([Number(casa.lat), Number(casa.lng)]).addTo(map)
        .bindPopup(`
          <strong>${escapeHtml(casa.direccion)}</strong><br>
          Territorio: ${escapeHtml(casa.nombre_sector || 'Sin territorio')}<br>
          Estado: ${escapeHtml(casa.estado || 'En revisión')}<br>
          Cliente: ${escapeHtml(casa.cliente_nombre || 'Sin asignar')}
        `);
    });

    let tempMarker = null;
    map.on('click', (event) => {
      const lat = event.latlng.lat.toFixed(6);
      const lng = event.latlng.lng.toFixed(6);

      if (tempMarker) map.removeLayer(tempMarker);
      tempMarker = L.marker([lat, lng]).addTo(map);

      setInputValue('casa_lat', lat);
      setInputValue('casa_lng', lng);
      openModal('modalCasaCrear');
    });
  }

  function init() {
    bindModals();
    bindSectorSelector();

    const initialSector = data.primerSectorId || (sectores[0] ? sectores[0].id : null);
    if (initialSector) {
      renderCasasPorSector(initialSector);
    }

    initMap();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
