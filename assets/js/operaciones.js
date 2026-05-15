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
  const inputBuscarFac = document.getElementById('buscarFactura');
  const btnBuscarFac = document.getElementById('btnBuscarFactura');
  const btnResetFac = document.getElementById('btnResetFactura');
  const tablaFacturas = document.getElementById('tablaFacturas');
  
  function filtrarFacturas() {
    if(!inputBuscarFac || !tablaFacturas) return;
    const q = inputBuscarFac.value.toLowerCase();
    tablaFacturas.querySelectorAll('tr').forEach(fila => {
      fila.style.display = fila.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  if (btnBuscarFac) btnBuscarFac.addEventListener('click', filtrarFacturas);
  if (inputBuscarFac) {
    inputBuscarFac.addEventListener('keyup', e => { if (e.key === 'Enter') filtrarFacturas(); });
  }
  if (btnResetFac) {
    btnResetFac.addEventListener('click', () => {
      if (inputBuscarFac) inputBuscarFac.value = '';
      filtrarFacturas();
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

function generarFactura() {
  const selectCasa = document.getElementById('fac-cliente');
  const id_usuario = selectCasa ? parseInt(selectCasa.value) : 1; 
  const periodo = document.getElementById('fac-periodo')?.value || '5';
  const consumo = parseFloat(document.getElementById('fac-consumo')?.value || 0);
  const tarifaId = parseInt(document.getElementById('fac-tarifa')?.value || 1);
  const precio = parseFloat(document.getElementById('fac-precio')?.value || 0);
  const mora = parseFloat(document.getElementById('fac-mora')?.value || 0);

  // We need the month and year from the select
  let mes = new Date().getMonth() + 1;
  let anio = new Date().getFullYear();
  if (periodo.includes('Abril')) mes = 4;
  else if (periodo.includes('Mayo')) mes = 5;

  fetch('../../api/generar_factura.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      id_usuario: id_usuario,
      mes: mes,
      anio: anio,
      consumo_m3: consumo,
      id_tarifa: tarifaId,
      cargo_fijo: tarifaId === 5 ? 5 : 8,
      precio_m3: precio,
      monto_mora: mora
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      if(typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'success', title: 'Factura Generada', text: 'La factura ha sido registrada en BD.', confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
      } else {
        if(typeof showToast === 'function') showToast('Factura generada correctamente','success');
      }
    } else {
      if(typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'error', title: 'Error', text: data.error, confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
      } else {
        alert("Error: " + data.error);
      }
    }
  })
  .catch(err => {
    console.error(err);
  });
}

function calcConsumo() {
  const ant = parseFloat(document.getElementById('lec-ant')?.value || 0);
  const act = parseFloat(document.getElementById('lec-act')?.value || 0);
  const consumo = Math.max(act - ant, 0);
  const el = document.getElementById('lec-consumo-txt');
  if (el) el.textContent = act > 0
    ? `Consumo: ${consumo} m³ (${ant} → ${act})`
    : 'Ingresa la lectura actual para calcular el consumo.';
}

function buscarCasaLectura() {
  const q = document.getElementById('lec-casa').value.trim();
  if (!q) { if(typeof showToast === 'function') showToast('Ingresa un término de búsqueda', 'warning'); return; }
  
  fetch('../../api/buscar_medidor.php?q=' + encodeURIComponent(q))
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('lec-id-medidor').value = data.data.id_medidor;
        document.getElementById('lec-info-cliente').textContent = data.data.cliente;
        document.getElementById('lec-info-sector').textContent = data.data.sector;
        document.getElementById('lec-info-medidor').textContent = data.data.numero_medidor;
        document.getElementById('lec-info-ultima').textContent = data.data.lectura_anterior + ' m³ — ' + data.data.fecha_lectura;
        document.getElementById('lec-ant').value = data.data.lectura_anterior;
        document.getElementById('lec-act').value = '';
        document.getElementById('lec-info').style.display = 'grid';
        calcConsumo();
        if(typeof showToast === 'function') showToast('Medidor encontrado', 'success');
      } else {
        if(typeof showToast === 'function') showToast(data.error, 'danger');
        document.getElementById('lec-info').style.display = 'none';
        document.getElementById('lec-id-medidor').value = '';
      }
    })
    .catch(err => {
      console.error(err);
      if(typeof showToast === 'function') showToast('Error al buscar medidor', 'danger');
    });
}

function guardarLectura() {
  const id_medidor = document.getElementById('lec-id-medidor')?.value;
  const act = document.getElementById('lec-act')?.value;
  const periodoText = document.getElementById('lec-periodo')?.value || '';
  
  if (!id_medidor) { if(typeof showToast === 'function') showToast('Primero busca un medidor válido', 'warning'); return; }
  if (!act) { if(typeof showToast === 'function') showToast('Ingresa la lectura actual', 'warning'); return; }
  
  const meses = {'Enero':1, 'Febrero':2, 'Marzo':3, 'Abril':4, 'Mayo':5, 'Junio':6, 'Julio':7, 'Agosto':8, 'Septiembre':9, 'Octubre':10, 'Noviembre':11, 'Diciembre':12};
  const partes = periodoText.split(' ');
  const mes = meses[partes[0]] || new Date().getMonth() + 1;
  const anio = partes[1] || new Date().getFullYear();

  fetch('../../api/guardar_lectura.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id_medidor: id_medidor, lectura_act: act, mes: mes, anio: anio })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      if(typeof showToast === 'function') showToast(`Lectura guardada. Consumo: ${data.consumo} m³`, 'success');
      document.getElementById('lec-act').value = '';
      document.getElementById('lec-info').style.display = 'none';
      document.getElementById('lec-id-medidor').value = '';
      document.getElementById('lec-ant').value = '0';
      calcConsumo();
    } else {
      if(typeof showToast === 'function') showToast(data.error, 'danger');
    }
  })
  .catch(err => {
    console.error(err);
    if(typeof showToast === 'function') showToast('Error de conexión', 'danger');
  });
}

function calcFactura() {
  const consumo = parseFloat(document.getElementById('fac-consumo')?.value || 0);
  const tarifa  = parseFloat(document.getElementById('fac-tarifa')?.value || 5);
  const precio  = parseFloat(document.getElementById('fac-precio')?.value || 0.35);
  const mora    = parseFloat(document.getElementById('fac-mora')?.value || 0);
  const cargo   = consumo * precio;
  const total   = tarifa + cargo + mora;
  const fmt = v => '$' + v.toFixed(2);
  const elRConsumo = document.getElementById('fac-r-consumo');
  if(elRConsumo) {
      elRConsumo.textContent = consumo + ' m³';
      document.getElementById('fac-r-tarifa').textContent  = fmt(tarifa);
      document.getElementById('fac-r-cargo').textContent   = fmt(cargo);
      document.getElementById('fac-r-mora').textContent    = fmt(mora);
      document.getElementById('fac-r-total').textContent   = fmt(total);
  }
}

function cargarEstadoCuenta() {
  const q = document.getElementById('ec-buscar').value.trim();
  if (!q) {
    if(typeof Swal !== 'undefined') Swal.fire({ icon: 'warning', title: 'Atención', text: 'Ingresa un código de cliente.', confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
    else if(typeof showToast === 'function') showToast('Ingresa un código de cliente', 'warning');
    return;
  }
  
  fetch(`../../api/estado_cuenta.php?codigo=${encodeURIComponent(q)}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('ec-titulo-cliente').textContent = `Historial — ${data.cliente.codigo_usuario} / ${data.cliente.cliente}`;
        
        document.getElementById('ec-saldo').textContent = '$' + parseFloat(data.kpis.saldo_pendiente).toFixed(2);
        document.getElementById('ec-pagado').textContent = '$' + parseFloat(data.kpis.pagado_anio).toFixed(2);
        document.getElementById('ec-vencidas').textContent = data.kpis.vencidas;
        document.getElementById('ec-consumo').textContent = data.kpis.consumo_total + ' m³';
        
        const tbody = document.getElementById('tbody-estado-cuenta');
        if (data.historial.length > 0) {
          let html = '';
          data.historial.forEach(h => {
            let colorSaldo = parseFloat(h.saldo_pendiente) > 0 ? 'color:var(--danger);font-weight:700;' : '';
            let badge = h.estado === 'pagada' ? 'badge-green' : (h.estado === 'vencida' ? 'badge-red' : 'badge-yellow');
            html += `
              <tr>
                <td>${h.mes_nombre}</td>
                <td>${h.consumo_m3} m³</td>
                <td>$${parseFloat(h.total).toFixed(2)}</td>
                <td>$${parseFloat(h.pagado).toFixed(2)}</td>
                <td style="${colorSaldo}">$${parseFloat(h.saldo_pendiente).toFixed(2)}</td>
                <td><span class="badge ${badge}">${h.estado.charAt(0).toUpperCase() + h.estado.slice(1)}</span></td>
              </tr>
            `;
          });
          tbody.innerHTML = html;
        } else {
          tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:10px;">No hay historial de facturas para este cliente.</td></tr>`;
        }
        
        if(typeof showToast === 'function') showToast('Estado de cuenta cargado', 'success');
      } else {
        if(typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Error', text: data.error, confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
        else alert(data.error);
      }
    })
    .catch(err => {
      console.error(err);
      if(typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error en el servidor.', confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
    });
}