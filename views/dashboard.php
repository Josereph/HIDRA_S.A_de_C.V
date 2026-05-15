          <div class="alert-body">
            <div class="alert-title">Morosos pendientes de atenciÃ³n</div>
            <div class="alert-msg">8 clientes con facturas vencidas hace mÃ¡s de 30 dÃ­as. Revisar en Operaciones â†’ Vencidas.</div>
          </div>
        </div>

        <div class="kpi-grid">
          <div class="kpi-card glow">
            <div class="kpi-icon blue"><i class="bi bi-people-fill"></i></div>
            <div class="kpi-label">Clientes activos</div>
            <div class="kpi-value"><?= number_format($stats['clientes_activos']) ?></div>
            <span class="kpi-delta up">Al dÃ­a</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon cyan"><i class="bi bi-droplet-fill"></i></div>
            <div class="kpi-label">Facturas del mes</div>
            <div class="kpi-value"><?= number_format($stats['facturas_mes']) ?></div>
            <span class="kpi-delta up">Al dÃ­a</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon green"><i class="bi bi-currency-dollar"></i></div>
            <div class="kpi-label">Facturado (mes)</div>
            <div class="kpi-value">$<?= number_format($stats['facturado_mes'], 2) ?></div>
            <span class="kpi-delta up">Al dÃ­a</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="kpi-label">Morosos</div>
            <div class="kpi-value"><?= number_format($stats['morosos']) ?></div>
            <span class="kpi-delta down">Pendientes</span>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon yellow"><i class="bi bi-map-fill"></i></div>
            <div class="kpi-label">Sectores activos</div>
            <div class="kpi-value"><?= number_format($stats['sectores_activos']) ?></div>
            <span class="kpi-delta neutral">100%</span>
          </div>
        </div>

        <div class="grid-3-1 mb-24">
          <div class="card">
            <div class="card-header">
              <span class="card-title">FacturaciÃ³n mensual</span>
              <span class="card-action">Ver detalle â†’</span>
            </div>
            <div class="section-tabs" data-group="dash-chart">
              <div class="section-tab active" data-panel="chart-bar" data-group="dash-chart">Barras</div>
              <div class="section-tab" data-panel="chart-donut" data-group="dash-chart">DistribuciÃ³n</div>
            </div>
            <div class="tab-panel active" data-panel="chart-bar" data-group="dash-chart">
              <div class="bar-chart">
                <div class="bar-group"><div class="bar" data-pct="55" style="height:4px"></div><div class="bar-label">Nov</div></div>
                <div class="bar-group"><div class="bar" data-pct="70" style="height:4px"></div><div class="bar-label">Dic</div></div>
                <div class="bar-group"><div class="bar" data-pct="62" style="height:4px"></div><div class="bar-label">Ene</div></div>
                <div class="bar-group"><div class="bar" data-pct="80" style="height:4px"></div><div class="bar-label">Feb</div></div>
                <div class="bar-group"><div class="bar" data-pct="74" style="height:4px"></div><div class="bar-label">Mar</div></div>
                <div class="bar-group"><div class="bar accent" data-pct="92" style="height:4px"></div><div class="bar-label">Abr</div></div>
              </div>
            </div>
            <div class="tab-panel" data-panel="chart-donut" data-group="dash-chart">
              <div class="donut-wrap" style="justify-content:center; padding:8px 0;">
                <svg class="donut-svg" width="110" height="110" viewBox="0 0 42 42">
                  <circle cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--celeste-xlt)" stroke-width="4.5"/>
                  <circle class="donut-ring" cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--celeste)" stroke-width="4.5" stroke-linecap="round" data-offset="62" transform="rotate(-90 21 21)"/>
                  <circle class="donut-ring" cx="21" cy="21" r="15.9" fill="transparent" stroke="var(--negro)" stroke-width="4.5" stroke-linecap="round" data-offset="45" transform="rotate(-90 21 21)" style="opacity:.5"/>
                  <text x="21" y="24" text-anchor="middle" fill="var(--negro)" font-size="5.5" font-weight="800">92%</text>
                </svg>
                <div class="donut-legend">
                  <div class="legend-item"><div class="legend-dot" style="background:var(--celeste)"></div>Al dÃ­a (92%)</div>
                  <div class="legend-item"><div class="legend-dot" style="background:var(--negro)"></div>Pendiente (5%)</div>
                  <div class="legend-item"><div class="legend-dot" style="background:var(--danger)"></div>Moroso (3%)</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <span class="card-title">Alertas</span>
              <span class="card-action">Ver todas</span>
            </div>
            <div style="display:flex; flex-direction:column; gap:10px;">
              <div class="alert alert-danger mb-0" style="padding:9px 12px;">
                <span class="alert-icon" style="font-size:.85rem;"><i class="bi bi-circle-fill" style="color: var(--danger)"></i></span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">GarcÃ­a LÃ³pez â€” vencida</div>
                  <div class="alert-msg">45 dÃ­as sin pago</div>
                </div>
              </div>
              <div class="alert alert-warning mb-0" style="padding:9px 12px;">
                <span class="alert-icon" style="font-size:.85rem;"><i class="bi bi-circle-fill" style="color: var(--warning)"></i></span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">Sector B â€” corte pendiente</div>
                  <div class="alert-msg">Programado para 30/04</div>
                </div>
              </div>
              <div class="alert alert-info mb-0" style="padding:9px 12px;">
                <span class="alert-icon" style="font-size:.85rem;"><i class="bi bi-droplet-fill" style="color: var(--info)"></i></span>
                <div class="alert-body">
                  <div class="alert-title" style="font-size:.74rem;">Nuevas tarifas 2026</div>
                  <div class="alert-msg">Vigentes desde mayo</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <span class="card-title">Ãšltimas transacciones</span>
            <span class="card-action" onclick="showView('operaciones')">Ver todas â†’</span>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th># Factura</th><th>Cliente</th><th>Sector</th><th>Fecha</th><th>Monto</th><th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($facturas_recientes as $f): ?>
                <tr>
                  <td class="td-mono"><?= htmlspecialchars($f['numero_factura']) ?></td>
                  <td class="td-primary"><?= htmlspecialchars($f['cliente']) ?></td>
                  <td>-</td>
                  <td><?= htmlspecialchars(date('d/m/Y', strtotime($f['fecha_emision']))) ?></td>
                  <td class="td-mono">$<?= number_format($f['total'], 2) ?></td>
                  <td>
                    <span class="badge badge-<?= $f['estado'] === 'pagada' ? 'green' : ($f['estado'] === 'vencida' ? 'red' : 'yellow') ?>">
                      <?= ucfirst(htmlspecialchars($f['estado'])) ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($facturas_recientes)): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);">No hay transacciones recientes</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div><!-- /dashboard -->
      <script>
      document.addEventListener('DOMContentLoaded', () => {
          if (typeof initDashboard === 'function') initDashboard();
      });
      </script>

