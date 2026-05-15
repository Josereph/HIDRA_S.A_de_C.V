<div class="view" id="view-operaciones">

  <div class="page-header">
    <div><h1 class="page-title">Operaciones</h1><p class="page-subtitle">Lecturas, facturas, pagos, moras y estado de cuenta</p></div>
    <div class="btn-group">
      <button class="btn btn-ghost btn-sm" onclick="showToast('Exportando…','info')"><i class="fas fa-download"></i> Exportar</button>
    </div>
  </div>

  <div class="section-tabs" data-group="ops-tabs">
    <div class="section-tab active" data-panel="ops-lecturas"  data-group="ops-tabs"><i class="fas fa-chart-bar"></i> Lecturas</div>
    <div class="section-tab"        data-panel="ops-facturas"  data-group="ops-tabs"><i class="fas fa-file-alt"></i> Facturas</div>
    <div class="section-tab"        data-panel="ops-estado"    data-group="ops-tabs"><i class="fas fa-clipboard-list"></i> Estado de cuenta</div>
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
          <div class="alert-icon"><i class="fas fa-tint"></i></div>
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
          <button class="btn btn-primary" type="button" onclick="guardarLectura()"><i class="fas fa-save"></i> Guardar lectura</button>
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
              <div style="font-weight:800;font-size:.9rem;color:var(--negro);"><?= htmlspecialchars($l['lectura_actual'] - $l['lectura_anterior']) ?> m³</div>
              <div style="font-size:.8rem;"><i class="fas fa-check-circle"></i></div>
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
            <label class="form-label">Cliente</label>
            <select class="form-control form-select" id="fac-cliente" onchange="calcFactura()">
              <?php foreach ($clientes_lista as $c): ?>
                <option value="<?= $c['id_usuario'] ?>"><?= htmlspecialchars($c['codigo_usuario'] . ' — ' . $c['cliente']) ?></option>
              <?php endforeach; ?>
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
          <button class="btn btn-primary" onclick="generarFactura()"><i class="fas fa-file-medical"></i> Generar factura</button>
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
          <div class="alert-icon"><i class="fas fa-tint"></i></div>
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
        <div class="search-bar" style="display:flex; gap:8px;">
          <input type="text" id="buscarFactura" class="form-control" placeholder="Buscar factura…" style="padding: 6px 12px; height: 32px;" />
          <button class="btn btn-agua btn-sm" id="btnBuscarFactura">Buscar</button>
          <button class="btn btn-ghost btn-sm" id="btnResetFactura"><i class="fas fa-times"></i></button>
        </div>
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
        <button class="btn btn-ghost" onclick="showToast('Exportando estado de cuenta…','info')"><i class="fas fa-download"></i> Exportar PDF</button>
      </div>
    </div>

    <div class="kpi-grid mb-24" style="grid-template-columns:repeat(4,1fr);">
      <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-money-bill"></i></div><div class="kpi-label">Saldo pendiente</div><div class="kpi-value" style="color:var(--danger);" id="ec-saldo">$18.50</div></div>
      <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-check-circle"></i></div><div class="kpi-label">Pagado este año</div><div class="kpi-value" id="ec-pagado">$95.00</div></div>
      <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-file-times"></i></div><div class="kpi-label">Facturas vencidas</div><div class="kpi-value" id="ec-vencidas">1</div></div>
      <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-tint"></i></div><div class="kpi-label">Consumo total</div><div class="kpi-value" id="ec-consumo">284 m³</div></div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="card-title" id="ec-titulo-cliente">Historial — Búsqueda de cliente</h2>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Periodo</th><th>Consumo</th><th>Total</th><th>Pagado</th><th>Saldo</th><th>Estado</th></tr></thead>
          <tbody id="tbody-estado-cuenta">
            <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:10px;">Ingresa el código de usuario para buscar</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div><!-- /view-operaciones -->