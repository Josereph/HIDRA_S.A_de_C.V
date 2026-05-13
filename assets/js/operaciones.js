'use strict';

/* ══════════════════════════════════════════════════════
   OPERACIONES — HIDRA
   Lógica complementaria del partial operaciones.php
   IDs sincronizados con el HTML real del partial.
══════════════════════════════════════════════════════ */

// ── Utilidad compartida ────────────────────────────────
function formatoDinero(valor) {
  return `$${Number(valor || 0).toFixed(2)}`;
}

// ════════════════════════════════════════════════════════
// MÓDULO 1 — LECTURAS
// calcConsumo(), buscarCasaLectura(), guardarLectura()
// están definidos inline en el partial.
// Aquí solo agregamos el listener del botón "Limpiar" de lecturas.
// ════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {

  // ── Botón limpiar lecturas ──────────────────────────
  const btnLimpiarLec = document.querySelector('#view-operaciones [data-panel="ops-lecturas"] .btn.btn-ghost');
  if (btnLimpiarLec) {
    btnLimpiarLec.addEventListener('click', () => {
      const casa = document.getElementById('lec-casa');
      const ant  = document.getElementById('lec-ant');
      const act  = document.getElementById('lec-act');
      const info = document.getElementById('lec-info');
      const mid  = document.getElementById('lec-id-medidor');
      if (casa) casa.value = '';
      if (ant)  ant.value  = '0';
      if (act)  act.value  = '';
      if (mid)  mid.value  = '';
      if (info) info.style.display = 'none';
      const txt = document.getElementById('lec-consumo-txt');
      if (txt) txt.textContent = 'Ingresa la lectura actual para calcular el consumo.';
    });
  }

  // ── Cálculo inicial de factura ──────────────────────
  if (typeof calcFactura === 'function') calcFactura();

  // ── Cálculo inicial de pago ──────────────────────────
  if (typeof calcularPago === 'function') calcularPago();

  // ── Búsqueda en tabla de facturas ──────────────────
  const buscarFactura = document.getElementById('buscarFactura');
  const tablaFacturas = document.getElementById('tablaFacturas');
  if (buscarFactura && tablaFacturas) {
    buscarFactura.addEventListener('input', e => {
      const q = e.target.value.toLowerCase();
      tablaFacturas.querySelectorAll('tr').forEach(fila => {
        fila.style.display = fila.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  }

  // ── Búsqueda casas (Territorio) ────────────────────
  const casaSearch = document.getElementById('casaSearch');
  if (casaSearch) {
    casaSearch.addEventListener('input', e => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('#view-territorio .table-wrap tr').forEach(row => {
        if (row.closest('thead')) return;
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  }

});