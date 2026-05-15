function buscarDeuda() {
  const q = document.getElementById('buscarCobro').value.trim();
  if (!q) {
    Swal.fire({ icon: 'warning', title: 'Atención', text: 'Ingresa un código de cliente o casa.', confirmButtonColor: '#66B3FF' });
    return;
  }
  
  fetch(`../../api/buscar_facturas_pendientes.php?q=${encodeURIComponent(q)}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('listaFacturasCobro');
      if (data.success && data.facturas.length > 0) {
        let html = '';
        data.facturas.forEach(f => {
          html += `
            <tr>
              <td><input type="radio" name="facturaSeleccionada" value="${f.id_factura}" data-saldo="${f.saldo_pendiente}" data-cliente="${f.cliente}" data-num="${f.numero_factura}" onchange="seleccionarFacturaCobro(this)" /></td>
              <td class="td-mono">${f.numero_factura}</td>
              <td>${f.mes}/${f.anio}</td>
              <td>$${parseFloat(f.total).toFixed(2)}</td>
              <td style="color:var(--danger);font-weight:700;">$${parseFloat(f.saldo_pendiente).toFixed(2)}</td>
            </tr>
          `;
        });
        tbody.innerHTML = html;
        // Auto select first
        const first = tbody.querySelector('input[type="radio"]');
        if (first) {
          first.checked = true;
          seleccionarFacturaCobro(first);
        }
      } else {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:10px;">No se encontraron deudas para: ${q}</td></tr>`;
        limpiarCobro(false); // Limpiar sin borrar input de búsqueda
      }
    })
    .catch(err => {
      console.error(err);
      Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error en el servidor.', confirmButtonColor: '#66B3FF' });
    });
}

let facturaActual = null;

function seleccionarFacturaCobro(radio) {
  facturaActual = {
    id: radio.value,
    saldo: parseFloat(radio.dataset.saldo),
    cliente: radio.dataset.cliente,
    num: radio.dataset.num
  };
  document.getElementById('resumenClienteCobro').textContent = facturaActual.cliente;
  document.getElementById('resumenFacturaCobro').textContent = facturaActual.num;
  document.getElementById('montoPagoCobro').value = facturaActual.saldo.toFixed(2);
  calcularCobro();
}

function calcularCobro() {
  if (!facturaActual) return;
  const monto = parseFloat(document.getElementById('montoPagoCobro').value || 0);
  const saldo = facturaActual.saldo;
  document.getElementById('resumenMontoRecibidoCobro').textContent = '$' + monto.toFixed(2);
  document.getElementById('resumenSaldoPendienteCobro').textContent = '$' + Math.max(saldo - monto, 0).toFixed(2);
}

function limpiarCobro(borrarBusqueda = true) {
  facturaActual = null;
  if (borrarBusqueda) document.getElementById('buscarCobro').value = '';
  document.getElementById('listaFacturasCobro').innerHTML = `<tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:10px;">Ingresa un término de búsqueda</td></tr>`;
  document.getElementById('montoPagoCobro').value = '';
  document.getElementById('referenciaPagoCobro').value = '';
  document.getElementById('resumenClienteCobro').textContent = '-';
  document.getElementById('resumenFacturaCobro').textContent = '-';
  document.getElementById('resumenMontoRecibidoCobro').textContent = '$0.00';
  document.getElementById('resumenSaldoPendienteCobro').textContent = '$0.00';
}

function registrarPago() {
  if (!facturaActual) {
    Swal.fire({ icon: 'warning', title: 'Atención', text: 'Selecciona una factura a pagar.', confirmButtonColor: '#66B3FF' });
    return;
  }
  
  const monto = parseFloat(document.getElementById('montoPagoCobro').value || 0);
  if (monto <= 0) {
    Swal.fire({ icon: 'warning', title: 'Atención', text: 'Ingresa un monto válido mayor a 0.', confirmButtonColor: '#66B3FF' });
    return;
  }
  
  if (monto > facturaActual.saldo) {
    Swal.fire({ icon: 'warning', title: 'Atención', text: 'El monto ingresado es mayor al saldo pendiente.', confirmButtonColor: '#66B3FF' });
    return;
  }

  const metodo = document.getElementById('metodoPagoCobro').value;
  const referencia = document.getElementById('referenciaPagoCobro').value.trim();

  Swal.fire({
    title: '¿Confirmar Pago?',
    text: `Registrar pago de $${monto.toFixed(2)} para la factura ${facturaActual.num}?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#66B3FF',
    cancelButtonColor: '#333',
    confirmButtonText: 'Sí, registrar',
    cancelButtonText: 'Cancelar',
    background: '#111',
    color: '#fff'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch('../../api/registrar_pago.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id_factura: facturaActual.id,
          monto_pagado: monto,
          metodo_pago: metodo,
          referencia: referencia
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Pago Exitoso', text: 'El pago se registró correctamente en la base de datos.', confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
          limpiarCobro(true);
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.error || 'No se pudo registrar el pago.', confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Error de red al conectar con el servidor.', confirmButtonColor: '#66B3FF', background: '#111', color: '#fff' });
      });
    }
  });
}
