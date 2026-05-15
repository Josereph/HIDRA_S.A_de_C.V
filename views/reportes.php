<!-- ══════════════════════════════════
     VISTA: REPORTES
══════════════════════════════════ -->
<div class="view" id="view-reportes">

  <div class="page-header">
    <div>
      <h1 class="page-title">Reportes</h1>
      <p class="page-subtitle">Generación de documentos formales, listados y exportaciones</p>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;">

    <!-- ── Panel izquierdo: configurador ── -->
    <div style="display:flex;flex-direction:column;gap:16px;">

      <!-- Tipo de reporte -->
      <div class="card">
        <div class="card-header"><h2 class="card-title">Configurar reporte</h2></div>

        <div class="form-group">
          <label class="form-label">Tipo de reporte</label>
          <select class="form-control form-select" id="rep-tipo" onchange="cambiarTipoReporte(this.value)">
            <option value="ingresos">Ingresos por periodo</option>
            <option value="morosos">Listado de morosos</option>
            <option value="consumo">Consumo por periodo</option>
            <option value="facturas">Facturas emitidas</option>
            <option value="pagos">Pagos realizados</option>
            <option value="clientes">Clientes y casas</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Formato de salida</label>
          <select class="form-control form-select" id="rep-formato">
            <option value="pdf">📄 PDF</option>
            <option value="excel">📊 Excel / CSV</option>
          </select>
        </div>

        <!-- Filtros comunes -->
        <div id="rep-filtros-comunes">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Desde</label>
              <input type="date" class="form-control" id="rep-desde" value="2026-04-01" />
            </div>
            <div class="form-group">
              <label class="form-label">Hasta</label>
              <input type="date" class="form-control" id="rep-hasta" value="2026-04-30" />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Sector</label>
            <select class="form-control form-select" id="rep-sector">
              <option value="">Todos los sectores</option>
              <option>Colonia Centro</option>
              <option>Comunidad Norte</option>
              <option>Las Margaritas</option>
              <option>El Calvario</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Estado</label>
            <select class="form-control form-select" id="rep-estado">
              <option value="">Todos</option>
              <option>Pagada</option>
              <option>Pendiente</option>
              <option>Vencida</option>
            </select>
          </div>
        </div>

        <!-- Filtros específicos por tipo -->
        <div id="rep-filtros-extra" style="display:none;">
          <!-- Se llena dinámicamente -->
        </div>

        <button class="btn btn-primary w-full mt-16" style="width:100%;" onclick="generarVistaPrevia()">
          👁 Vista previa
        </button>
        <div class="flex-gap" style="margin-top:10px;gap:8px;">
          <button class="btn btn-agua" style="flex:1;" onclick="exportarReporte('pdf')">↓ PDF</button>
          <button class="btn btn-ghost" style="flex:1;" onclick="exportarReporte('excel')">↓ Excel</button>
        </div>

      </div>

      <!-- Reportes rápidos -->
      <div class="card">
        <div class="card-header"><h2 class="card-title">Reportes rápidos</h2></div>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <?php
          $quick = [
            ['Morosos del mes',       'morosos', '⚠', 'badge-red'],
            ['Ingresos de abril',     'ingresos','💰', 'badge-green'],
            ['Consumo por sector',    'consumo', '💧', 'badge-blue'],
            ['Facturas pendientes',   'facturas','📄', 'badge-yellow'],
          ];
          foreach ($quick as $q): ?>
          <button class="btn btn-ghost" style="text-align:left;padding:10px 14px;justify-content:flex-start;gap:10px;"
            onclick="cargarReporteRapido('<?= $q[1] ?>')">
            <span class="badge <?= $q[3] ?>" style="font-size:.7rem;"><?= $q[2] ?></span>
            <?= $q[0] ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

    </div><!-- /panel izquierdo -->

    <!-- ── Panel derecho: vista previa ── -->
    <div class="card" style="min-height:520px;">
      <div class="card-header" id="rep-preview-header">
        <h2 class="card-title" id="rep-preview-title">Selecciona un reporte y haz clic en "Vista previa"</h2>
        <div id="rep-preview-meta" style="font-size:.72rem;color:var(--text-muted);"></div>
      </div>

      <!-- Estado vacío -->
      <div id="rep-empty" style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:380px;color:var(--text-muted);">
        <div style="font-size:3rem;margin-bottom:16px;">📋</div>
        <div style="font-weight:600;font-size:.85rem;">Vista previa del reporte</div>
        <div style="font-size:.75rem;margin-top:6px;">Configura los filtros y haz clic en "Vista previa"</div>
      </div>

      <!-- Contenido de la vista previa -->
      <div id="rep-preview-content" style="display:none;">

        <!-- Encabezado del documento -->
        <div id="rep-doc-header" style="display:flex;align-items:flex-start;justify-content:space-between;padding:16px;background:var(--celeste-xlt);border-radius:var(--radius-sm);margin-bottom:18px;">
          <div>
            <div style="font-weight:800;font-size:.9rem;color:var(--negro);">HIDRA S.A. de C.V.</div>
            <div id="rep-doc-title" style="font-size:1rem;font-weight:700;color:var(--celeste);margin-top:4px;"></div>
            <div id="rep-doc-periodo" style="font-size:.72rem;color:var(--text-muted);margin-top:2px;"></div>
          </div>
          <div style="text-align:right;font-size:.72rem;color:var(--text-muted);">
            <div>Generado: <?= date('d/m/Y H:i') ?></div>
            <div>Usuario: Samuel Admin</div>
          </div>
        </div>

        <!-- KPI resumen del reporte -->
        <div id="rep-kpis" class="kpi-grid mb-24" style="grid-template-columns:repeat(3,1fr);"></div>

        <!-- Tabla de datos -->
        <div class="table-wrap">
          <table id="rep-tabla">
            <thead id="rep-thead"></thead>
            <tbody id="rep-tbody"></tbody>
          </table>
        </div>

        <!-- Totales -->
        <div id="rep-totales" style="margin-top:16px;padding:12px 16px;background:var(--celeste-xlt);border-radius:var(--radius-sm);font-size:.82rem;font-weight:700;display:flex;gap:24px;flex-wrap:wrap;"></div>

      </div><!-- /rep-preview-content -->

    </div><!-- /panel derecho -->

  </div>

</div><!-- /view-reportes -->

<script>
// ── Datos mock por tipo de reporte ──────────────────────
const REPORTES = {
  ingresos: {
    titulo: 'Ingresos por periodo',
    kpis: [
      { icon:'💰', label:'Total facturado', val:'$7,850.00', delta:'↑ 12%', color:'green' },
      { icon:'✓',  label:'Cobrado',         val:'$7,204.50', delta:'92%',   color:'blue'  },
      { icon:'⚠',  label:'Pendiente',       val:'$645.50',   delta:'8%',    color:'red'   },
    ],
    cols: ['N° Pago','Cliente','Casa','Periodo','Monto','Método','Fecha'],
    rows: [
      ['PAY-001','Juan Pérez','H-001','Abr 2026','$9.20','Efectivo','20/04/2026'],
      ['PAY-002','María López','H-014','Abr 2026','$11.30','Transferencia','21/04/2026'],
      ['PAY-003','Carlos Ramírez','H-022','Abr 2026','$13.75','Efectivo','19/04/2026'],
      ['PAY-004','Rosa Herrera','H-006','Abr 2026','$9.20','Efectivo','22/04/2026'],
      ['PAY-005','Luis Morales','H-005','Abr 2026','$10.50','Cheque','18/04/2026'],
    ],
    totales: [{ label:'Total cobrado', val:'$53.95' },{ label:'Registros', val:'5' }],
  },
  morosos: {
    titulo: 'Listado de morosos',
    kpis: [
      { icon:'⚠', label:'Clientes morosos', val:'96',      delta:'Del total', color:'red'    },
      { icon:'💸', label:'Mora acumulada',   val:'$1,248', delta:'Total',     color:'yellow' },
      { icon:'📅', label:'Días prom. atraso',val:'47 días', delta:'Promedio', color:'cyan'   },
    ],
    cols: ['Casa','Cliente','Sector','Periodo vencido','Saldo','Mora','Días'],
    rows: [
      ['H-002','Ana López','Norte','Mar 2026','$12.00','$1.50','68 días'],
      ['H-004','María García','El Calvario','Feb 2026','$28.50','$4.00','95 días'],
      ['H-009','Pedro Sánchez','Centro','Abr 2026','$9.75','$1.00','32 días'],
      ['H-017','Roberto Díaz','Margaritas','Mar 2026','$15.20','$2.00','61 días'],
    ],
    totales: [{ label:'Total deuda', val:'$65.45' },{ label:'Total mora', val:'$8.50' }],
  },
  consumo: {
    titulo: 'Consumo por periodo',
    kpis: [
      { icon:'💧', label:'Consumo total', val:'4,930 m³', delta:'Período',    color:'blue' },
      { icon:'🏠', label:'Casas activas', val:'602',       delta:'Registros', color:'cyan' },
      { icon:'📊', label:'Promedio/casa', val:'8.2 m³',    delta:'Por mes',   color:'green'},
    ],
    cols: ['Casa','Cliente','Sector','Periodo','Lect. ant.','Lect. act.','Consumo'],
    rows: [
      ['H-001','Juan Pérez','Centro','May 2026','1200','1250','50 m³'],
      ['H-003','Carlos Ramírez','Margaritas','May 2026','930','980','50 m³'],
      ['H-005','Luis Morales','Centro','May 2026','610','640','30 m³'],
      ['H-006','Rosa Herrera','Norte','May 2026','820','855','35 m³'],
    ],
    totales: [{ label:'Total consumo', val:'165 m³' },{ label:'Casas registradas', val:'4' }],
  },
  facturas: {
    titulo: 'Facturas emitidas',
    kpis: [
      { icon:'📄', label:'Total facturas', val:'602',     delta:'Período',   color:'blue'  },
      { icon:'✓',  label:'Pagadas',        val:'486',     delta:'80.7%',     color:'green' },
      { icon:'⏳', label:'Pendientes',     val:'116',     delta:'19.3%',     color:'yellow'},
    ],
    cols: ['N° Factura','Cliente','Casa','Periodo','Consumo','Total','Estado'],
    rows: [
      ['FAC-0001','Juan Pérez','H-001','Abr 2026','12 m³','$9.20','Pagada'],
      ['FAC-0002','María López','H-014','Abr 2026','18 m³','$11.30','Pendiente'],
      ['FAC-0003','Carlos Ramírez','H-022','Abr 2026','25 m³','$13.75','Mora'],
      ['FAC-0004','Rosa Herrera','H-006','Abr 2026','14 m³','$9.90','Pagada'],
    ],
    totales: [{ label:'Total facturado', val:'$44.15' },{ label:'Total registros', val:'4' }],
  },
  pagos: {
    titulo: 'Pagos realizados',
    kpis: [
      { icon:'💳', label:'Pagos registrados', val:'486',      delta:'Período',  color:'green' },
      { icon:'💰', label:'Monto total',        val:'$7,204',  delta:'Cobrado',  color:'blue'  },
      { icon:'📅', label:'Últ. pago',          val:'22/04',   delta:'Abr 2026', color:'cyan'  },
    ],
    cols: ['N° Pago','Cliente','Factura','Monto','Método','Referencia','Fecha'],
    rows: [
      ['PAY-001','Juan Pérez','FAC-0001','$9.20','Efectivo','—','20/04/2026'],
      ['PAY-002','María López','FAC-0002','$11.30','Transferencia','TRF-8821','21/04/2026'],
      ['PAY-003','Rosa Herrera','FAC-0004','$9.90','Efectivo','—','22/04/2026'],
    ],
    totales: [{ label:'Total cobrado', val:'$30.40' },{ label:'Registros', val:'3' }],
  },
  clientes: {
    titulo: 'Clientes y casas',
    kpis: [
      { icon:'👤', label:'Total clientes', val:'1,248', delta:'Activos', color:'blue'  },
      { icon:'🏠', label:'Total casas',    val:'654',   delta:'Activas', color:'cyan'  },
      { icon:'⚠',  label:'En mora',        val:'96',    delta:'7.7%',    color:'red'   },
    ],
    cols: ['Cód.','Cliente','Documento','Teléfono','Casas','Saldo','Estado'],
    rows: [
      ['C-001','Juan Pérez','00000000-0','7000-0001','1','$0.00','Activo'],
      ['C-002','Ana López','11111111-1','7000-0002','1','$12.00','Mora'],
      ['C-003','Carlos Ramírez','22222222-2','7000-0003','2','$0.00','Activo'],
      ['C-004','María García','33333333-3','7000-0004','1','$28.50','Mora'],
    ],
    totales: [{ label:'Total clientes', val:'4' },{ label:'Saldo pendiente total', val:'$40.50' }],
  },
};

let reporteActual = null;

function cambiarTipoReporte(tipo) {
  // Filtros extra por tipo
  const extra = document.getElementById('rep-filtros-extra');
  extra.style.display = 'none';
  extra.innerHTML = '';

  if (tipo === 'morosos') {
    extra.style.display = 'block';
    extra.innerHTML = `
      <div class="form-group">
        <label class="form-label">Días mínimos de atraso</label>
        <select class="form-control form-select">
          <option>Cualquier atraso</option>
          <option>+30 días</option>
          <option>+60 días</option>
          <option>+90 días</option>
        </select>
      </div>`;
  } else if (tipo === 'pagos') {
    extra.style.display = 'block';
    extra.innerHTML = `
      <div class="form-group">
        <label class="form-label">Método de pago</label>
        <select class="form-control form-select">
          <option>Todos los métodos</option>
          <option>Efectivo</option>
          <option>Transferencia</option>
          <option>Cheque</option>
        </select>
      </div>`;
  }
}

function generarVistaPrevia() {
  const tipo = document.getElementById('rep-tipo').value;
  const desde = document.getElementById('rep-desde').value;
  const hasta = document.getElementById('rep-hasta').value;
  const data = REPORTES[tipo];
  if (!data) return;

  reporteActual = tipo;

  // Mostrar contenido, ocultar empty
  document.getElementById('rep-empty').style.display = 'none';
  document.getElementById('rep-preview-content').style.display = 'block';

  // Encabezado
  document.getElementById('rep-doc-title').textContent = data.titulo;
  document.getElementById('rep-doc-periodo').textContent =
    `Periodo: ${desde} — ${hasta}`;
  document.getElementById('rep-preview-title').textContent = data.titulo;
  document.getElementById('rep-preview-meta').textContent =
    `${data.rows.length} registros encontrados`;

  // KPIs
  const kpiEl = document.getElementById('rep-kpis');
  kpiEl.innerHTML = data.kpis.map(k => `
    <div class="kpi-card">
      <div class="kpi-icon ${k.color}">${k.icon}</div>
      <div class="kpi-label">${k.label}</div>
      <div class="kpi-value" style="font-size:1.1rem;">${k.val}</div>
      <span class="kpi-delta neutral">${k.delta}</span>
    </div>`).join('');

  // Tabla
  document.getElementById('rep-thead').innerHTML =
    '<tr>' + data.cols.map(c => `<th>${c}</th>`).join('') + '</tr>';

  const colorMap = { 'Pagada':'badge-green','Pagado':'badge-green','Activo':'badge-green',
    'Pendiente':'badge-yellow','Mora':'badge-red','Moroso':'badge-red','Morosos':'badge-red' };

  document.getElementById('rep-tbody').innerHTML = data.rows.map(row =>
    '<tr>' + row.map((cell, i) => {
      const badge = colorMap[cell];
      return `<td>${badge ? `<span class="badge ${badge}">${cell}</span>` : cell}</td>`;
    }).join('') + '</tr>'
  ).join('');

  // Totales
  document.getElementById('rep-totales').innerHTML =
    data.totales.map(t => `<span>${t.label}: <strong>${t.val}</strong></span>`).join('');

  showToast('Vista previa generada', 'success');
}

function exportarReporte(fmt) {
  if (!reporteActual) {
    showToast('Genera una vista previa primero', 'warning');
    return;
  }
  const tipo = REPORTES[reporteActual]?.titulo || 'Reporte';
  showToast(`Exportando "${tipo}" como ${fmt.toUpperCase()}…`, 'info');
  setTimeout(() => showToast('Reporte listo para descargar', 'success'), 1200);
}

function cargarReporteRapido(tipo) {
  document.getElementById('rep-tipo').value = tipo;
  cambiarTipoReporte(tipo);
  generarVistaPrevia();
}
</script>
