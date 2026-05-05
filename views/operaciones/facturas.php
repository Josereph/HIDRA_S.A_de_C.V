<main class="page-content view active" id="facturasView">

  <div class="page-header">
    <div>
      <h1 class="page-title">Generación de Facturas</h1>
      <p class="page-subtitle">Crea facturas mensuales a partir del consumo registrado por casa o cliente.</p>
    </div>

    <div class="btn-group">
      <button class="btn btn-secondary" type="button">Exportar</button>
      <button class="btn btn-primary" type="button" id="btnGenerarFactura">Generar factura</button>
    </div>
  </div>

  <section class="kpi-grid">
    <article class="kpi-card">
      <div class="kpi-icon blue">📄</div>
      <div class="kpi-label">Facturas del mes</div>
      <div class="kpi-value">128</div>
      <span class="kpi-delta neutral">Periodo actual</span>
    </article>

    <article class="kpi-card">
      <div class="kpi-icon green">✅</div>
      <div class="kpi-label">Pagadas</div>
      <div class="kpi-value">96</div>
      <span class="kpi-delta up">Al día</span>
    </article>

    <article class="kpi-card">
      <div class="kpi-icon yellow">⏳</div>
      <div class="kpi-label">Pendientes</div>
      <div class="kpi-value">24</div>
      <span class="kpi-delta neutral">Por cobrar</span>
    </article>

    <article class="kpi-card">
      <div class="kpi-icon red">⚠️</div>
      <div class="kpi-label">Con mora</div>
      <div class="kpi-value">8</div>
      <span class="kpi-delta down">Revisar</span>
    </article>
  </section>

  <section class="grid-2-1">
    <article class="card">
      <div class="card-header">
        <h2 class="card-title">Datos para facturación</h2>
      </div>

      <form id="formFactura">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Cliente o casa</label>
            <input class="form-control" type="text" id="clienteFactura" placeholder="Ej: Juan Pérez / CASA-001">
          </div>

          <div class="form-group">
            <label class="form-label">Periodo</label>
            <input class="form-control" type="month" id="periodoFactura">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Lectura anterior</label>
            <input class="form-control" type="number" id="lecturaAnterior" placeholder="0">
          </div>

          <div class="form-group">
            <label class="form-label">Lectura actual</label>
            <input class="form-control" type="number" id="lecturaActual" placeholder="0">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tarifa aplicada</label>
            <select class="form-control form-select" id="tarifaFactura">
              <option value="5">Residencial - $5.00 base</option>
              <option value="8">Comercial - $8.00 base</option>
              <option value="12">Especial - $12.00 base</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Precio por m³</label>
            <input class="form-control" type="number" id="precioMetro" value="0.75" step="0.01">
          </div>
        </div>

        <div class="btn-group">
          <button class="btn btn-primary" type="submit">Generar factura</button>
          <button class="btn btn-ghost" type="reset">Limpiar</button>
        </div>
      </form>
    </article>

    <article class="card factura-resumen">
      <div class="card-header">
        <h2 class="card-title">Resumen</h2>
      </div>

      <div class="resumen-linea">
        <span>Consumo</span>
        <strong id="resumenConsumo">0 m³</strong>
      </div>

      <div class="resumen-linea">
        <span>Tarifa base</span>
        <strong id="resumenTarifa">$0.00</strong>
      </div>

      <div class="resumen-linea">
        <span>Cargo por consumo</span>
        <strong id="resumenCargo">$0.00</strong>
      </div>

      <div class="resumen-total">
        <span>Total a pagar</span>
        <strong id="resumenTotal">$0.00</strong>
      </div>

      <div class="alert alert-info">
        <div class="alert-icon">💧</div>
        <div class="alert-body">
          <div class="alert-title">Vista preliminar</div>
          <div class="alert-msg">El cálculo es visual; backend validará y guardará la factura.</div>
        </div>
      </div>
    </article>
  </section>

  <section class="card tabla-facturas">
    <div class="card-header">
      <h2 class="card-title">Facturas recientes</h2>

      <div class="search-bar">
        <span class="search-icon">🔎</span>
        <input type="text" id="buscarFactura" placeholder="Buscar factura...">
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>N° Factura</th>
            <th>Cliente</th>
            <th>Casa</th>
            <th>Periodo</th>
            <th>Consumo</th>
            <th>Total</th>
            <th>Estado</th>
          </tr>
        </thead>

        <tbody id="tablaFacturas">
          <tr>
            <td class="td-mono">FAC-0001</td>
            <td class="td-primary">Juan Pérez</td>
            <td>CASA-001</td>
            <td>Abril 2026</td>
            <td>12 m³</td>
            <td>$14.00</td>
            <td><span class="badge badge-green">Pagada</span></td>
          </tr>

          <tr>
            <td class="td-mono">FAC-0002</td>
            <td class="td-primary">María López</td>
            <td>CASA-014</td>
            <td>Abril 2026</td>
            <td>18 m³</td>
            <td>$18.50</td>
            <td><span class="badge badge-yellow">Pendiente</span></td>
          </tr>

          <tr>
            <td class="td-mono">FAC-0003</td>
            <td class="td-primary">Carlos Ramírez</td>
            <td>CASA-022</td>
            <td>Abril 2026</td>
            <td>25 m³</td>
            <td>$25.75</td>
            <td><span class="badge badge-red">Mora</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

</main>