<main class="page-content view active" id="pagosView">

  <div class="page-header">
    <div>
      <h1 class="page-title">Registro de Pagos</h1>
      <p class="page-subtitle">Registra pagos de las facturas generadas para actualizar el estado de cuenta de los clientes.</p>
    </div>

    <div class="btn-group">
      <button class="btn btn-secondary" type="button">Exportar</button>
      <button class="btn btn-primary" type="button" id="btnRegistrarPago">Registrar pago</button>
    </div>
  </div>

  <section class="kpi-grid">
    <article class="kpi-card">
      <div class="kpi-icon blue">📄</div>
      <div class="kpi-label">Pagos registrados</div>
      <div class="kpi-value">180</div>
      <span class="kpi-delta neutral">Periodo actual</span>
    </article>

    <article class="kpi-card">
      <div class="kpi-icon green">✅</div>
      <div class="kpi-label">Pagos completos</div>
      <div class="kpi-value">160</div>
      <span class="kpi-delta up">Al día</span>
    </article>

    <article class="kpi-card">
      <div class="kpi-icon yellow">⏳</div>
      <div class="kpi-label">Pendientes</div>
      <div class="kpi-value">20</div>
      <span class="kpi-delta neutral">Por cobrar</span>
    </article>

    <article class="kpi-card">
      <div class="kpi-icon red">⚠️</div>
      <div class="kpi-label">Con mora</div>
      <div class="kpi-value">5</div>
      <span class="kpi-delta down">Revisar</span>
    </article>
  </section>

  <section class="grid-2-1">
    <article class="card">
      <div class="card-header">
        <h2 class="card-title">Datos del Pago</h2>
      </div>

      <form id="formPago">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Cliente o Casa</label>
            <input class="form-control" type="text" id="clientePago" placeholder="Ej: Juan Pérez / CASA-001">
          </div>

          <div class="form-group">
            <label class="form-label">Factura N°</label>
            <input class="form-control" type="text" id="facturaPago" placeholder="Ej: FAC-0001">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Monto a Pagar</label>
            <input class="form-control" type="number" id="montoPago" placeholder="0.00" step="0.01">
          </div>

          <div class="form-group">
            <label class="form-label">Fecha de Pago</label>
            <input class="form-control" type="date" id="fechaPago">
          </div>
        </div>

        <div class="btn-group">
          <button class="btn btn-primary" type="submit">Registrar Pago</button>
          <button class="btn btn-ghost" type="reset">Limpiar</button>
        </div>
      </form>
    </article>

    <article class="card pago-resumen">
      <div class="card-header">
        <h2 class="card-title">Resumen del Pago</h2>
      </div>

      <div class="resumen-linea">
        <span>Cliente</span>
        <strong id="resumenCliente">Juan Pérez</strong>
      </div>

      <div class="resumen-linea">
        <span>Factura</span>
        <strong id="resumenFactura">FAC-0001</strong>
      </div>

      <div class="resumen-linea">
        <span>Monto pagado</span>
        <strong id="resumenMontoPago">$50.00</strong>
      </div>

      <div class="resumen-total">
        <span>Total a pagar</span>
        <strong id="resumenTotalPago">$50.00</strong>
      </div>

      <div class="alert alert-info">
        <div class="alert-icon">💧</div>
        <div class="alert-body">
          <div class="alert-title">Vista preliminar</div>
          <div class="alert-msg">El cálculo es visual; backend validará y registrará el pago.</div>
        </div>
      </div>
    </article>
  </section>

  <section class="card tabla-pagos">
    <div class="card-header">
      <h2 class="card-title">Pagos recientes</h2>

      <div class="search-bar">
        <span class="search-icon">🔎</span>
        <input type="text" id="buscarPago" placeholder="Buscar pago...">
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>N° Pago</th>
            <th>Cliente</th>
            <th>Factura</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Estado</th>
          </tr>
        </thead>

        <tbody id="tablaPagos">
          <tr>
            <td class="td-mono">PAY-0001</td>
            <td class="td-primary">Juan Pérez</td>
            <td>FAC-0001</td>
            <td>2026-04-20</td>
            <td>$50.00</td>
            <td><span class="badge badge-green">Pagado</span></td>
          </tr>

          <tr>
            <td class="td-mono">PAY-0002</td>
            <td class="td-primary">María López</td>
            <td>FAC-0002</td>
            <td>2026-04-21</td>
            <td>$30.00</td>
            <td><span class="badge badge-yellow">Pendiente</span></td>
          </tr>

          <tr>
            <td class="td-mono">PAY-0003</td>
            <td class="td-primary">Carlos Ramírez</td>
            <td>FAC-0003</td>
            <td>2026-04-22</td>
            <td>$25.00</td>
            <td><span class="badge badge-red">Con mora</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

</main>