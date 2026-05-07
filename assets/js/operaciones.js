'use strict';

/* ══════════════════════════════════════════════════════
   OPERACIONES — HIDRA
   Lógica de: Facturas (cálculo) + Registro de Pagos
   Funciona tanto con index.php (SPA) como con vistas PHP separadas.
══════════════════════════════════════════════════════ */

// ── Utilidad compartida ────────────────────────────────
function formatoDinero(valor) {
  return `$${Number(valor || 0).toFixed(2)}`;
}

// ════════════════════════════════════════════════════════
// MÓDULO 1 — GENERACIÓN DE FACTURAS
// ════════════════════════════════════════════════════════
const lecturaAnterior = document.getElementById('lecturaAnterior');
const lecturaActual   = document.getElementById('lecturaActual');
const tarifaFactura   = document.getElementById('tarifaFactura');
const precioMetro     = document.getElementById('precioMetro');

const resumenConsumo  = document.getElementById('resumenConsumo');
const resumenTarifa   = document.getElementById('resumenTarifa');
const resumenCargo    = document.getElementById('resumenCargo');
const resumenTotal    = document.getElementById('resumenTotal');

const formFactura     = document.getElementById('formFactura');
const buscarFactura   = document.getElementById('buscarFactura');
const tablaFacturas   = document.getElementById('tablaFacturas');

function calcularFactura() {
  const anterior = Number(lecturaAnterior?.value || 0);
  const actual   = Number(lecturaActual?.value   || 0);
  const tarifa   = Number(tarifaFactura?.value   || 0);
  const precio   = Number(precioMetro?.value     || 0);

  const consumo = Math.max(actual - anterior, 0);
  const cargo   = consumo * precio;
  const total   = tarifa + cargo;

  if (resumenConsumo) resumenConsumo.textContent = `${consumo} m³`;
  if (resumenTarifa)  resumenTarifa.textContent  = formatoDinero(tarifa);
  if (resumenCargo)   resumenCargo.textContent   = formatoDinero(cargo);
  if (resumenTotal)   resumenTotal.textContent   = formatoDinero(total);
}

[lecturaAnterior, lecturaActual, tarifaFactura, precioMetro].forEach(campo => {
  campo?.addEventListener('input',  calcularFactura);
  campo?.addEventListener('change', calcularFactura);
});

formFactura?.addEventListener('submit', e => {
  e.preventDefault();
  const cliente = document.getElementById('clienteFactura')?.value.trim();
  const periodo = document.getElementById('periodoFactura')?.value;

  if (!cliente || !periodo) {
    window.showToast
      ? window.showToast('Completa cliente/casa y periodo antes de generar la factura', 'warning')
      : alert('Completa cliente/casa y periodo antes de generar la factura');
    return;
  }
  window.showToast
    ? window.showToast('Factura generada correctamente en vista preliminar', 'success')
    : alert('Factura generada correctamente');
});

buscarFactura?.addEventListener('input', e => {
  const texto = e.target.value.toLowerCase();
  tablaFacturas?.querySelectorAll('tr').forEach(fila => {
    fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
  });
});

// ════════════════════════════════════════════════════════
// MÓDULO 2 — REGISTRO DE PAGOS
// ════════════════════════════════════════════════════════
const clientePago       = document.getElementById('clientePago');
const facturaPago       = document.getElementById('facturaPago');
const montoPago         = document.getElementById('montoPago');
const fechaPago         = document.getElementById('fechaPago');

const resumenCliente    = document.getElementById('resumenCliente');
const resumenFactura    = document.getElementById('resumenFactura');
const resumenMontoPago  = document.getElementById('resumenMontoPago');
const resumenTotalPago  = document.getElementById('resumenTotalPago');

const formPago          = document.getElementById('formPago');
const buscarPago        = document.getElementById('buscarPago');
const tablaPagos        = document.getElementById('tablaPagos');

function calcularPago() {
  const monto = Number(montoPago?.value || 0);
  if (resumenMontoPago) resumenMontoPago.textContent = formatoDinero(monto);
  if (resumenTotalPago) resumenTotalPago.textContent = formatoDinero(monto);
}

[montoPago, fechaPago].forEach(campo => {
  campo?.addEventListener('input',  calcularPago);
  campo?.addEventListener('change', calcularPago);
});

formPago?.addEventListener('submit', e => {
  e.preventDefault();
  const cliente = clientePago?.value.trim();
  const factura = facturaPago?.value.trim();
  const monto   = montoPago?.value;
  const fecha   = fechaPago?.value;

  if (!cliente || !factura || !monto || !fecha) {
    window.showToast
      ? window.showToast('Por favor, completa todos los campos del pago', 'warning')
      : alert('Por favor, complete todos los campos.');
    return;
  }

  if (resumenCliente) resumenCliente.textContent = cliente;
  if (resumenFactura) resumenFactura.textContent = factura;
  calcularPago();

  window.showToast
    ? window.showToast('Pago registrado correctamente', 'success')
    : alert('Pago registrado correctamente');
});

buscarPago?.addEventListener('input', e => {
  const texto = e.target.value.toLowerCase();
  tablaPagos?.querySelectorAll('tr').forEach(fila => {
    fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
  });
});

// ── Init ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  calcularFactura();
  calcularPago();
});