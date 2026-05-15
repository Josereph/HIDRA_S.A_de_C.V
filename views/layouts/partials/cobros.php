<div class="view" id="view-cobros">

  <div class="page-header">
    <div><h1 class="page-title">Cobros y Moras</h1><p class="page-subtitle">Gestión de pagos y control de deudores</p></div>
  </div>

  <div class="section-tabs" data-group="cobros-tabs">
    <div class="section-tab active" data-panel="cobros-pagos" data-group="cobros-tabs"><i class="fas fa-credit-card"></i> Registro de Pagos</div>
    <div class="section-tab"        data-panel="cobros-moras" data-group="cobros-tabs"><i class="fas fa-exclamation-triangle"></i> Control de Moras</div>
  </div>

  <!-- PESTAÑA: REGISTRO DE PAGOS -->
  <div class="tab-panel active" data-panel="cobros-pagos" data-group="cobros-tabs">
    <div class="grid-2-1">
      <div class="card">
        <div class="card-header"><h2 class="card-title">Registrar pago</h2></div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Cliente / Código de Casa</label>
            <div style="display:flex;gap:8px;">
              <input class="form-control" id="buscarCobro" placeholder="Juan Pérez / H-001" style="flex:1;" />
              <button class="btn btn-agua btn-sm" onclick="buscarDeuda()">Buscar</button>
            </div>
          </div>
        </div>

        <div style="margin-bottom:18px;">
          <label class="form-label mb-8">Facturas pendientes</label>
          <div class="table-wrap">
            <table>
              <thead><tr><th></th><th>Factura</th><th>Periodo</th><th>Total</th><th>Saldo</th></tr></thead>
              <tbody id="listaFacturasCobro">
                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:10px;">Ingresa un término de búsqueda</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Monto recibido ($)</label>
            <input class="form-control" id="montoPagoCobro" type="number" placeholder="0.00" step="0.01" oninput="calcularCobro()" />
          </div>
          <div class="form-group">
            <label class="form-label">Método de pago</label>
            <select class="form-control form-select" id="metodoPagoCobro">
              <option value="efectivo">Efectivo</option>
              <option value="transferencia">Transferencia</option>
              <option value="cheque">Cheque</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Referencia (opcional)</label>
          <input class="form-control" id="referenciaPagoCobro" placeholder="N° de transacción, recibo…" />
        </div>
        <div class="card-footer">
          <button class="btn btn-ghost" onclick="limpiarCobro()">Limpiar</button>
          <button class="btn btn-primary" onclick="registrarPago()"><i class="fas fa-save"></i> Guardar pago</button>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h2 class="card-title">Resumen del pago</h2></div>
        <div class="pago-resumen">
          <div class="resumen-linea"><span>Cliente</span><strong id="resumenClienteCobro">-</strong></div>
          <div class="resumen-linea"><span>Factura</span><strong id="resumenFacturaCobro">-</strong></div>
          <div class="resumen-linea"><span>Monto recibido</span><strong id="resumenMontoRecibidoCobro">$0.00</strong></div>
          <div class="resumen-total"><span>Saldo restante</span><strong id="resumenSaldoPendienteCobro">$0.00</strong></div>
        </div>
        <div class="alert alert-info mt-16">
          <div class="alert-icon"><i class="fas fa-credit-card"></i></div>
          <div class="alert-body">
            <div class="alert-title">Validación automática</div>
            <div class="alert-msg">El sistema restará el monto ingresado del saldo y cambiará el estado de la factura automáticamente en la base de datos si se liquida el 100%.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- PESTAÑA: MORAS -->
  <div class="tab-panel" data-panel="cobros-moras" data-group="cobros-tabs">
    <div class="kpi-grid mb-24" style="grid-template-columns:repeat(3,1fr);">
      <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-exclamation-triangle"></i></div><div class="kpi-label">Facturas en mora</div><div class="kpi-value" id="kpi-moras-count"><?= count($gestion_moras ?? []) ?></div></div>
      <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-coins"></i></div><div class="kpi-label">Mora total acumulada</div><div class="kpi-value" id="kpi-moras-total">$<?= number_format(array_sum(array_column($gestion_moras ?? [], 'monto_mora')), 2) ?></div></div>
      <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-calendar-times"></i></div><div class="kpi-label">Prom. de días atraso</div><div class="kpi-value" id="kpi-moras-dias">--</div></div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Gestión de moras</h2>
        <div class="flex-gap">
          <div class="search-bar"><span class="search-icon"><i class="fas fa-search"></i></span><input type="text" placeholder="Buscar cliente, casa…" /></div>
        </div>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Casa</th><th>Cliente</th><th>Periodo vencido</th><th>Saldo ($)</th><th>Mora ($)</th><th>Días</th><th>Acción</th></tr></thead>
          <tbody>
            <?php if(isset($gestion_moras)): foreach($gestion_moras as $m): ?>
            <tr>
              <td class="td-mono">H-<?= str_pad($m['id_usuario'], 3, '0', STR_PAD_LEFT) ?></td>
              <td class="td-primary"><?= htmlspecialchars($m['cliente']) ?></td>
              <td><?= htmlspecialchars($m['mes'] . '/' . $m['anio']) ?></td>
              <td style="color:var(--danger);font-weight:700;">$<?= number_format($m['total'], 2) ?></td>
              <td style="color:var(--danger);">$<?= number_format($m['monto_mora'], 2) ?></td>
              <td><span class="badge badge-red"><?= htmlspecialchars($m['dias_retraso']) ?> días</span></td>
              <td>
                <div class="flex-gap">
                  <button class="btn btn-agua btn-sm" onclick="showToast('Registrando cobro…','info')">Cobrar</button>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
            <?php if(empty($gestion_moras)): ?>
            <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">No hay clientes en mora</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
