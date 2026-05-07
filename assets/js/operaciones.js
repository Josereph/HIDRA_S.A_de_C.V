'use strict';

const lecturaAnterior = document.getElementById('lecturaAnterior');
const lecturaActual = document.getElementById('lecturaActual');
const tarifaFactura = document.getElementById('tarifaFactura');
const precioMetro = document.getElementById('precioMetro');

const resumenConsumo = document.getElementById('resumenConsumo');
const resumenTarifa = document.getElementById('resumenTarifa');
const resumenCargo = document.getElementById('resumenCargo');
const resumenTotal = document.getElementById('resumenTotal');

const formFactura = document.getElementById('formFactura');
const buscarFactura = document.getElementById('buscarFactura');
const tablaFacturas = document.getElementById('tablaFacturas');

function formatoDinero(valor) {
  return `$${Number(valor || 0).toFixed(2)}`;
}

function calcularFactura() {
  const anterior = Number(lecturaAnterior?.value || 0);
  const actual = Number(lecturaActual?.value || 0);
  const tarifa = Number(tarifaFactura?.value || 0);
  const precio = Number(precioMetro?.value || 0);

  const consumo = Math.max(actual - anterior, 0);
  const cargo = consumo * precio;
  const total = tarifa + cargo;

  if (resumenConsumo) resumenConsumo.textContent = `${consumo} m³`;
  if (resumenTarifa) resumenTarifa.textContent = formatoDinero(tarifa);
  if (resumenCargo) resumenCargo.textContent = formatoDinero(cargo);
  if (resumenTotal) resumenTotal.textContent = formatoDinero(total);
}

[lecturaAnterior, lecturaActual, tarifaFactura, precioMetro].forEach(campo => {
  campo?.addEventListener('input', calcularFactura);
  campo?.addEventListener('change', calcularFactura);
});

formFactura?.addEventListener('submit', e => {
  e.preventDefault();

  const cliente = document.getElementById('clienteFactura')?.value.trim();
  const periodo = document.getElementById('periodoFactura')?.value;

  if (!cliente || !periodo) {
    if (window.showToast) {
      window.showToast('Completa cliente/casa y periodo antes de generar la factura', 'warning');
    } else {
      alert('Completa cliente/casa y periodo antes de generar la factura');
    }
    return;
  }

  if (window.showToast) {
    window.showToast('Factura generada correctamente en vista preliminar', 'success');
  } else {
    alert('Factura generada correctamente');
  }
});

buscarFactura?.addEventListener('input', e => {
  const texto = e.target.value.toLowerCase();

  tablaFacturas?.querySelectorAll('tr').forEach(fila => {
    fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
  });
});

document.addEventListener('DOMContentLoaded', calcularFactura);

'use strict';

const clientePago = document.getElementById('clientePago');
const facturaPago = document.getElementById('facturaPago');
const montoPago = document.getElementById('montoPago');
const fechaPago = document.getElementById('fechaPago');

const resumenCliente = document.getElementById('resumenCliente');
const resumenFactura = document.getElementById('resumenFactura');
const resumenMontoPago = document.getElementById('resumenMontoPago');
const resumenTotalPago = document.getElementById('resumenTotalPago');

const formPago = document.getElementById('formPago');
const buscarPago = document.getElementById('buscarPago');
const tablaPagos = document.getElementById('tablaPagos');

function formatoDinero(valor) {
  return `$${Number(valor || 0).toFixed(2)}`;
}

function calcularPago() {
  const monto = Number(montoPago?.value || 0);

  if (resumenMontoPago) resumenMontoPago.textContent = formatoDinero(monto);
  if (resumenTotalPago) resumenTotalPago.textContent = formatoDinero(monto);
}

[montoPago, fechaPago].forEach(campo => {
  campo?.addEventListener('input', calcularPago);
  campo?.addEventListener('change', calcularPago);
});

formPago?.addEventListener('submit', e => {
  e.preventDefault();

  const cliente = clientePago?.value.trim();
  const factura = facturaPago?.value.trim();

  if (!cliente || !factura || !montoPago || !fechaPago) {
    alert('Por favor, complete todos los campos.');
    return;
  }

  // Resumen
  if (resumenCliente) resumenCliente.textContent = cliente;
  if (resumenFactura) resumenFactura.textContent = factura;

  calcularPago();

  alert('Pago registrado correctamente');
});

buscarPago?.addEventListener('input', e => {
  const texto = e.target.value.toLowerCase();

  tablaPagos?.querySelectorAll('tr').forEach(fila => {
    fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
  });
});