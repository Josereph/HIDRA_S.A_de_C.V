<div class="view" id="view-operaciones">

  <div class="page-header">
    <div><h1 class="page-title">Operaciones</h1><p class="page-subtitle">Lecturas, facturas, pagos, moras y estado de cuenta</p></div>
    <div class="btn-group">
      <button class="btn btn-ghost btn-sm" onclick="showToast('Exportando…','info')"><i class="bi bi-download"></i> Exportar</button>
    </div>
  </div>

  <div class="section-tabs" data-group="ops-tabs">
    <div class="section-tab active" data-panel="ops-lecturas"  data-group="ops-tabs"><i class="bi bi-bar-chart-fill"></i> Lecturas</div>
    <div class="section-tab"        data-panel="ops-facturas"  data-group="ops-tabs"><i class="bi bi-file-earmark-text"></i> Facturas</div>
    <div class="section-tab"        data-panel="ops-pagos"     data-group="ops-tabs"><i class="bi bi-credit-card"></i> Pagos</div>
    <div class="section-tab"        data-panel="ops-moras"     data-group="ops-tabs"><i class="bi bi-exclamation-triangle"></i> Moras</div>
    <div class="section-tab"        data-panel="ops-estado"    data-group="ops-tabs"><i class="bi bi-clipboard-data"></i> Estado de cuenta</div>
  </div>

  <div class="tab-panel active" data-panel="ops-lecturas" data-group="ops-tabs">
    <div class="grid-2-1">

      <div class="card">
        <div class="card-header"><h2 class="card-title">Captura de lectura</h2></div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Casa / Medidor</label>
            <div style="display:flex;gap:8px;">
              <input class="form-control" id="lec-casa" placeholder="Ej: H-001 / M-10023" style="flex:1;" />
              <button class="btn btn-agua btn-sm" onclick="buscarCasaLectura()">Buscar</button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Periodo</label>
            <select class="form-control form-select" id="lec-periodo">
              <option>Mayo 2026</option>
              <option>Abril 2026</option>
              <option>Marzo 2026</option>
            </select>
          </div>
        </div>

        <input type="hidden" id="lec-id-medidor" value="" />
        <div id="lec-info" style="display:none; background:var(--celeste-xlt);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:18px;grid-template-columns:1fr 1fr;gap:6px;font-size:.8rem;">
          <div><span style="color:var(--text-muted);font-weight:700;">Cliente</span><div style="font-weight:700;" id="lec-info-cliente">-</div></div>
          <div><span style="color:var(--text-muted);font-weight:700;">Sector</span><div id="lec-info-sector">-</div></div>
          <div><span style="color:var(--text-muted);font-weight:700;">Medidor</span><div class="td-mono" id="lec-info-medidor">-</div></div>
          <div><span style="color:var(--text-muted);font-weight:700;">Última lectura</span><div id="lec-info-ultima">-</div></div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Lectura anterior (m³)</label>
            <input class="form-control" id="lec-ant" type="number" value="0" readonly style="background:var(--celeste-xlt);" />
          </div>
          <div class="form-group">
            <label class="form-label">Lectura actual (m³)</label>
            <input class="form-control" id="lec-act" type="number" placeholder="0" oninput="calcConsumo()" />
          </div>
        </div>

        <div class="alert alert-info mb-16" id="lec-consumo-alert">
          <div class="alert-icon"><i class="bi bi-droplet-fill"></i></div>
          <div class="alert-body">
            <div class="alert-title">Consumo calculado</div>
            <div class="alert-msg" id="lec-consumo-txt">Ingresa la lectura actual para calcular el consumo.</div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Observaciones (opcional)</label>
          <input class="form-control" placeholder="Ej: Medidor con dificultad de lectura…" />
        </div>

        <div class="card-footer">
          <button class="btn btn-ghost" type="button">Limpiar</button>
          <button class="btn btn-primary" type="button" onclick="guardarLectura()"><i class="bi bi-floppy"></i> Guardar lectura</button>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h2 class="card-title">Recientes</h2></div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <?php foreach ($lecturas_recientes as $l): ?>
          <div class="stat-row">
            <div>
              <div style="font-weight:700;font-size:.8rem;"><?= htmlspecialchars($l['cliente']) ?> <span class="td-mono" style="font-size:.7rem;color:var(--text-muted);">H-<?= str_pad($l['id_medidor'], 3, '0', STR_PAD_LEFT) ?></span></div>
              <div style="font-size:.72rem;color:var(--text-muted);"><?= htmlspecialchars($l['mes'] . '/' . $l['anio']) ?></div>
            </div>
            <div style="text-align:right;">
              <div style="font-weight:800;font-size:.9rem;color:var(--negro);"><?= htmlspecialchars($l['consumo_m3']) ?> m³</div>
              <div style="font-size:.8rem;"><i class="bi bi-check-circle-fill" style="color: #10b981;"></i></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div><div class="tab-panel" data-panel="ops-facturas" data-group="ops-tabs">
    <div class="grid-2-1">
      <div class="card">
        <div class="card-header"><h2 class="card-title">Generar factura</h2></div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Casa</label>
            <select class="form-control form-select" onchange="calcFactura()">
              <option value="h1">H-001 — Juan Pérez</option>
              <option value="h3">H-003 — Carlos Ramírez</option>
              <option value="h5">H-005 — Luis Morales</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Periodo</label>
            <select class="form-control form-select" id="fac-periodo">
              <option>Mayo 2026</option><option>Abril 2026</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Consumo (m³)</label>
            <input class="form-control" id="fac-consumo" type="number" value="50" oninput="calcFactura()" />
          </div>
          <div class="form-group">
            <label class="form-label">Tarifa aplicada</label>
            <select class="form-control form-select" id="fac-tarifa" onchange="calcFactura()">
              <option value="5">Residencial — $5.00 base</option>
              <option value="8">Comercial — $8.00 base</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Precio por m³ ($)</label>
            <input class="form-control" id="fac-precio" type="number" value="0.35" step="0.01" oninput="calcFactura()" />
          </div>
          <div class="form-group">
            <label class="form-label">Mora anterior ($)</label>
            <input class="form-control" id="fac-mora" type="number" value="0" step="0.01" oninput="calcFactura()" />
          </div>
        </div>
        <div class="card-footer">
          <button class="btn btn-ghost">Cancelar</button>
          <button class="btn btn-primary" onclick="showToast('Factura generada correctamente','success')"><i class="bi bi-file-earmark-plus"></i> Generar factura</button>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h2 class="card-title">Resumen</h2></div>
        <div class="pago-resumen">
          <div class="resumen-linea"><span>Consumo</span><strong id="fac-r-consumo">50 m³</strong></div>
          <div class="resumen-linea"><span>Tarifa base</span><strong id="fac-r-tarifa">$5.00</strong></div>
          <div class="resumen-linea"><span>Cargo por consumo</span><strong id="fac-r-cargo">$17.50</strong></div>
          <div class="resumen-linea"><span>Mora anterior</span><strong id="fac-r-mora">$0.00</strong></div>
          <div class="resumen-total"><span>Total a pagar</span><strong id="fac-r-total">$22.50</strong></div>
        </div>
        <div class="alert alert-info mt-16">
          <div class="alert-icon"><i class="bi bi-droplet-fill"></i></div>
          <div class="alert-body">
            <div class="alert-title">Vista preliminar</div>
            <div class="alert-msg">El backend validará y guardará la factura.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mt-24 tabla-facturas">
      <div class="card-header">
        <h2 class="card-title">Facturas recientes</h2>
        <div class="search-bar"><span class="search-icon"><i class="bi bi-search"></i></span><input type="text" id="buscarFactura" placeholder="Buscar factura…" /></div>
      </div>
      <div class="table-wrap">
        <table><thead><tr><th>N° Factura</th><th>Cliente</th><th>Casa</th><th>Periodo</th><th>Consumo</th><th>Total</th><th>Estado</th><th>Acción</th></tr></thead>
        <tbody id="tablaFacturas">
          <?php foreach($facturas_operaciones as $f): ?>
          <tr>
            <td class="td-mono"><?= htmlspecialchars($f['numero_factura']) ?></td>
            <td class="td-primary"><?= htmlspecialchars($f['cliente']) ?></td>
            <td>H-<?= str_pad($f['id_usuario'], 3, '0', STR_PAD_LEFT) ?></td>
            <td><?= htmlspecialchars(date('M Y', strtotime($f['fecha_emision']))) ?></td>
            <td>-</td>
            <td>$<?= number_format($f['total'], 2) ?></td>
            <td><span class="badge badge-<?= $f['estado'] === 'pagada' ? 'green' : ($f['estado'] === 'vencida' ? 'red' : 'yellow') ?>"><?= ucfirst(htmlspecialchars($f['estado'])) ?></span></td>
            <td><button class="btn btn-ghost btn-sm">Ver</button></td>
          </tr>
          <?php endforeach; ?>
        </tbody></table>
      </div>
    </div>
  </div><div class="tab-panel" data-panel="ops-pagos" data-group="ops-tabs">
    <div class="grid-2-1">
      <div class="card">
        <div class="card-header"><h2 class="card-title">Registrar pago</h2></div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Cliente / Casa</label>
            <div style="display:flex;gap:8px;">
              <input class="form-control" placeholder="Juan Pérez / H-001" style="flex:1;" />
              <button class="btn btn-agua btn-sm">Buscar</button>
            </div>
          </div>
        </div>

        <div style="margin-bottom:18px;">
          <label class="form-label mb-8">Facturas pendientes</label>
          <div class="table-wrap">
            <table>
              <thead><tr><th></th><th>Factura</th><th>Periodo</th><th>Total</th><th>Saldo</th></tr></thead>
              <tbody>
                <tr><td><input type="checkbox" checked /></td><td class="td-mono">FAC-0002</td><td>Abr 2026</td><td>$11.30</td><td style="color:var(--danger);font-weight:700;">$11.30</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Monto recibido ($)</label>
            <input class="form-control" id="montoPago" type="number" placeholder="0.00" step="0.01" oninput="calcularPago()" />
          </div>
          <div class="form-group">
            <label class="form-label">Método de pago</label>
            <select class="form-control form-select">
              <option>Efectivo</option><option>Transferencia</option><option>Cheque</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Referencia (opcional)</label>
          <input class="form-control" placeholder="N° de transacción, recibo…" />
        </div>
        <div class="card-footer">
          <button class="btn btn-ghost">Limpiar</button>
          <button class="btn btn-primary" onclick="showToast('Pago registrado correctamente','success')"><i class="bi bi-floppy"></i> Guardar pago</button>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h2 class="card-title">Resumen del pago</h2></div>
        <div class="pago-resumen">
          <div class="resumen-linea"><span>Cliente</span><strong id="resumenCliente">María López</strong></div>
          <div class="resumen-linea"><span>Factura</span><strong id="resumenFactura">FAC-0002</strong></div>
          <div class="resumen-linea"><span>Monto recibido</span><strong id="resumenMontoPago">$0.00</strong></div>
          <div class="resumen-total"><span>Saldo pendiente</span><strong id="resumenTotalPago">$11.30</strong></div>
        </div>
        <div class="alert alert-info mt-16">
          <div class="alert-icon"><i class="bi bi-credit-card"></i></div>
          <div class="alert-body">
            <div class="alert-title">Pago parcial</div>
            <div class="alert-msg">Si el monto es menor al saldo, quedará un saldo pendiente.</div>
          </div>
        </div>
      </div>
    </div>
  </div><div class="tab-panel" data-panel="ops-moras" data-group="ops-tabs">
    <div class="kpi-grid mb-24" style="grid-template-columns:repeat(3,1fr);">
      <div class="kpi-card"><div class="kpi-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div><div class="kpi-label">Clientes en mora</div><div class="kpi-value">96</div></div>
      <div class="kpi-card"><div class="kpi-icon yellow"><i class="bi bi-cash-coin"></i></div><div class="kpi-label">Mora total acumulada</div><div class="kpi-value">$1,248</div></div>
      <div class="kpi-card"><div class="kpi-icon cyan"><i class="bi bi-calendar-x"></i></div><div class="kpi-label">Días prom. de atraso</div><div class="kpi-value">47</div></div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Gestión de moras</h2>
        <div class="flex-gap">
          <select class="form-control" style="width:auto;padding:6px 10px;">
            <option>Todos los sectores</option><option>Colonia Centro</option><option>Norte</option>
          </select>
          <select class="form-control" style="width:auto;padding:6px 10px;">
            <option>Todos</option><option>+30 días</option><option>+60 días</option><option>+90 días</option>
          </select>
          <div class="search-bar"><span class="search-icon"><i class="bi bi-search"></i></span><input type="text" placeholder="Cliente, casa…" /></div>
        </div>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Casa</th><th>Cliente</th><th>Periodo vencido</th><th>Saldo ($)</th><th>Mora ($)</th><th>Días</th><th>Acción</th></tr></thead>
          <tbody>
            <?php foreach($gestion_moras as $m): ?>
            <tr>
              <td class="td-mono">H-<?= str_pad($m['id_usuario'], 3, '0', STR_PAD_LEFT) ?></td>
              <td class="td-primary"><?= htmlspecialchars($m['cliente']) ?></td>
              <td><?= htmlspecialchars($m['mes'] . '/' . $m['anio']) ?></td>
              <td style="color:var(--danger);font-weight:700;">$<?= number_format($m['total'], 2) ?></td>
              <td style="color:var(--danger);">$<?= number_format($m['monto_mora'], 2) ?></td>
              <td><span class="badge badge-red"><?= htmlspecialchars($m['dias_retraso']) ?> días</span></td>
              <td>
                <div class="flex-gap">
                  <button class="btn btn-ghost btn-sm">Ver</button>
                  <button class="btn btn-agua btn-sm" onclick="showToast('Registrando cobro…','info')">Cobrar</button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($gestion_moras)): ?>
            <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">No hay clientes en mora</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div><div class="tab-panel" data-panel="ops-estado" data-group="ops-tabs">
    <div class="card mb-24">
      <div style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
        <div class="form-group mb-0" style="flex:1;min-width:220px;">
          <label class="form-label">Cliente o Casa</label>
          <div style="display:flex;gap:8px;">
            <input class="form-control" id="ec-buscar" placeholder="Juan Pérez / H-001…" style="flex:1;" />
            <button class="btn btn-agua" onclick="cargarEstadoCuenta()">Buscar</button>
          </div>
        </div>
        <div class="form-group mb-0">
          <label class="form-label">Año</label>
          <select class="form-control form-select" style="width:auto;">
            <option>2026</option><option>2025</option><option>2024</option>
          </select>
        </div>
        <button class="btn btn-ghost" onclick="showToast('Exportando estado de cuenta…','info')"><i class="bi bi-download"></i> Exportar PDF</button>
      </div>
    </div>

    <div class="kpi-grid mb-24" style="grid-template-columns:repeat(4,1fr);">
      <div class="kpi-card"><div class="kpi-icon red"><i class="bi bi-cash"></i></div><div class="kpi-label">Saldo pendiente</div><div class="kpi-value" style="color:var(--danger);" id="ec-saldo">$18.50</div></div>
      <div class="kpi-card"><div class="kpi-icon green"><i class="bi bi-check-circle-fill"></i></div><div class="kpi-label">Pagado este año</div><div class="kpi-value" id="ec-pagado">$95.00</div></div>
      <div class="kpi-card"><div class="kpi-icon yellow"><i class="bi bi-file-earmark-x"></i></div><div class="kpi-label">Facturas vencidas</div><div class="kpi-value" id="ec-vencidas">1</div></div>
      <div class="kpi-card"><div class="kpi-icon blue"><i class="bi bi-droplet-fill"></i></div><div class="kpi-label">Consumo total</div><div class="kpi-value" id="ec-consumo">284 m³</div></div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Historial — Juan Pérez / H-001</h2>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Periodo</th><th>Consumo</th><th>Total</th><th>Pagado</th><th>Saldo</th><th>Estado</th></tr></thead>
          <tbody>
            <tr><td>Mayo 2026</td><td>50 m³</td><td>$22.50</td><td>$0.00</td><td style="color:var(--danger);font-weight:700;">$22.50</td><td><span class="badge badge-yellow">Pendiente</span></td></tr>
            <tr><td>Abr 2026</td><td>12 m³</td><td>$9.20</td><td>$9.20</td><td>$0.00</td><td><span class="badge badge-green">Pagada</span></td></tr>
            <tr><td>Mar 2026</td><td>44 m³</td><td>$20.40</td><td>$20.40</td><td>$0.00</td><td><span class="badge badge-green">Pagada</span></td></tr>
            <tr><td>Feb 2026</td><td>38 m³</td><td>$18.30</td><td>$18.30</td><td>$0.00</td><td><span class="badge badge-green">Pagada</span></td></tr>
            <tr><td>Ene 2026</td><td>41 m³</td><td>$19.35</td><td>$19.35</td><td>$0.00</td><td><span class="badge badge-green">Pagada</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div></div><script>
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
  if (!q) { showToast('Ingresa un término de búsqueda', 'warning'); return; }
  
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
        showToast('Medidor encontrado', 'success');
      } else {
        showToast(data.error, 'danger');
        document.getElementById('lec-info').style.display = 'none';
        document.getElementById('lec-id-medidor').value = '';
      }
    })
    .catch(err => {
      console.error(err);
      showToast('Error al buscar medidor', 'danger');
    });
}

function guardarLectura() {
  const id_medidor = document.getElementById('lec-id-medidor')?.value;
  const act = document.getElementById('lec-act')?.value;
  const periodoText = document.getElementById('lec-periodo')?.value || ''; // Ej: Mayo 2026
  
  if (!id_medidor) { showToast('Primero busca un medidor válido', 'warning'); return; }
  if (!act) { showToast('Ingresa la lectura actual', 'warning'); return; }
  
  // Extraer mes y año del texto "Mayo 2026"
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
      showToast(`Lectura guardada. Consumo: ${data.consumo} m³`, 'success');
      document.getElementById('lec-act').value = '';
      document.getElementById('lec-info').style.display = 'none';
      document.getElementById('lec-id-medidor').value = '';
      document.getElementById('lec-ant').value = '0';
      calcConsumo();
    } else {
      showToast(data.error, 'danger');
    }
  })
  .catch(err => {
    console.error(err);
    showToast('Error de conexión', 'danger');
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
  document.getElementById('fac-r-consumo').textContent = consumo + ' m³';
  document.getElementById('fac-r-tarifa').textContent  = fmt(tarifa);
  document.getElementById('fac-r-cargo').textContent   = fmt(cargo);
  document.getElementById('fac-r-mora').textContent    = fmt(mora);
  document.getElementById('fac-r-total').textContent   = fmt(total);
}

function calcularPago() {
  const monto = parseFloat(document.getElementById('montoPago')?.value || 0);
  const saldo = 11.30;
  document.getElementById('resumenMontoPago').textContent = '$' + monto.toFixed(2);
  document.getElementById('resumenTotalPago').textContent = '$' + Math.max(saldo - monto, 0).toFixed(2);
}

function cargarEstadoCuenta() {
  showToast('Estado de cuenta cargado: Juan Pérez / H-001', 'success');
}
</script>