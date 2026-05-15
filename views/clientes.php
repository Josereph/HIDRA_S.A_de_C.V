

      <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
           VISTA 2: CLIENTES
      â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
      <div class="view active" id="view-clientes">
        <div class="page-header">
          <div>
            <h1 class="page-title">Clientes</h1>
            <p class="page-subtitle">GestiÃ³n del padrÃ³n de abonados</p>
          </div>
          <div class="btn-group">
            <button class="btn btn-ghost btn-sm">â†“ Exportar CSV</button>
            <button class="btn btn-primary btn-sm" id="btnNuevoCliente">+ Nuevo cliente</button>
          </div>
        </div>

        <div class="section-tabs" data-group="clientes-tabs">
          <div class="section-tab active" data-panel="cli-listado" data-group="clientes-tabs"><i class="bi bi-card-list"></i> Listado</div>
          <div class="section-tab" data-panel="cli-registro" data-group="clientes-tabs"><i class="bi bi-pencil-square"></i> Registro</div>
          <div class="section-tab" data-panel="cli-historial" data-group="clientes-tabs"><i class="bi bi-calendar3"></i> Historial</div>
        </div>

        <div class="tab-panel active" data-panel="cli-listado" data-group="clientes-tabs">
          <div class="flex-between mb-16" style="gap:12px; flex-wrap:wrap;">
            <div class="search-bar">
              <span class="search-icon"><i class="bi bi-search"></i></span>
              <input type="text" id="clienteSearch" placeholder="Buscar nombre, cÃ³digo, sectorâ€¦" />
            </div>
            <div class="flex-gap">
              <select class="form-control" style="width:auto; padding:8px 12px;">
                <option>Todos los sectores</option>
                <option>Sector A</option><option>Sector B</option><option>Sector C</option><option>Sector D</option>
              </select>
              <select class="form-control" style="width:auto; padding:8px 12px;">
                <option>Todos los estados</option>
                <option>Al dÃ­a</option><option>Pendiente</option><option>Moroso</option>
              </select>
            </div>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th class="sortable">CÃ³digo <span class="sort-icon"><i class="bi bi-arrow-down-up"></i></span></th>
                  <th class="sortable">Nombre <span class="sort-icon"><i class="bi bi-arrow-down-up"></i></span></th>
                  <th>DirecciÃ³n</th><th>Sector</th><th>TelÃ©fono</th><th>Tarifa</th>
                  <th class="sortable">Estado <span class="sort-icon"><i class="bi bi-arrow-down-up"></i></span></th><th>Acciones</th>
                </tr>
              </thead>
              <tbody id="clienteTabla">
                <?php foreach($clientes_lista as $c): ?>
                <tr>
                  <td class="td-mono"><?= htmlspecialchars($c['codigo_usuario']) ?></td>
                  <td class="td-primary"><?= htmlspecialchars($c['cliente']) ?></td>
                  <td>-</td>
                  <td><?= htmlspecialchars($c['sector']) ?></td>
                  <td class="td-mono">-</td>
                  <td class="td-mono">-</td>
                  <td>
                    <span class="badge badge-<?= $c['estado_usuario'] === 'activo' ? 'green' : 'red' ?>">
                      <?= ucfirst(htmlspecialchars($c['estado_usuario'])) ?>
                    </span>
                  </td>
                  <td>
                    <div class="flex-gap">
                      <button class="btn btn-ghost btn-sm"><i class="bi bi-pencil-square"></i> Editar</button>
                      <button class="btn btn-agua btn-sm"><i class="bi bi-file-earmark-text"></i> Historial</button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($clientes_lista)): ?>
                <tr><td colspan="8" style="text-align:center;color:var(--text-muted);">No hay clientes registrados</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="flex-between mt-16" style="color:var(--text-muted); font-size:.75rem;">
            <span>Mostrando 1â€“6 de 342 clientes</span>
            <div class="flex-gap">
              <button class="btn btn-ghost btn-sm">â† Anterior</button>
              <span style="color:var(--negro); font-weight:800; background:var(--celeste-xlt); padding:4px 10px; border-radius:6px;">1</span>
              <button class="btn btn-ghost btn-sm">2</button>
              <button class="btn btn-ghost btn-sm">3</button>
              <button class="btn btn-ghost btn-sm">Siguiente â†’</button>
            </div>
          </div>
        </div>

        <div class="tab-panel" data-panel="cli-registro" data-group="clientes-tabs">
          <div class="card" style="max-width:620px;">
            <div class="card-header"><span class="card-title">Nuevo Cliente</span></div>
            <form onsubmit="event.preventDefault(); showToast('Cliente guardado correctamente','success');">
              <div class="form-row">
                <div class="form-group"><label class="form-label">Nombres</label><input type="text" class="form-control" placeholder="Nombre(s)" required /></div>
                <div class="form-group"><label class="form-label">Apellidos</label><input type="text" class="form-control" placeholder="Apellido(s)" required /></div>
              </div>
              <div class="form-row">
                <div class="form-group"><label class="form-label">DUI</label><input type="text" class="form-control" placeholder="00000000-0" /></div>
                <div class="form-group"><label class="form-label">TelÃ©fono</label><input type="tel" class="form-control" placeholder="0000-0000" /></div>
              </div>
              <div class="form-group"><label class="form-label">DirecciÃ³n</label><input type="text" class="form-control" placeholder="Calle, colonia, nÃºmeroâ€¦" /></div>
              <div class="form-row">
                <div class="form-group"><label class="form-label">Sector</label><select class="form-control form-select"><option>A-1</option><option>A-2</option><option>A-3</option><option>B-1</option><option>B-2</option><option>B-3</option><option>C-1</option><option>C-2</option></select></div>
                <div class="form-group"><label class="form-label">Tarifa mensual</label><select class="form-control form-select"><option>$12.50 â€” DomÃ©stica bÃ¡sica</option><option>$15.00 â€” DomÃ©stica plus</option><option>$25.00 â€” Comercial</option></select></div>
              </div>
              <div class="flex-gap mt-16">
                <button type="submit" class="btn btn-primary">ðŸ’¾ Guardar cliente</button>
                <button type="reset" class="btn btn-ghost">Limpiar</button>
              </div>
            </form>
          </div>
        </div>

        <div class="tab-panel" data-panel="cli-historial" data-group="clientes-tabs">
          <div class="grid-2-1">
            <div class="card">
              <div class="card-header">
                <span class="card-title">Historial de pagos</span>
                <div class="search-bar"><span class="search-icon">ðŸ”</span><input type="text" placeholder="Buscar clienteâ€¦" style="min-width:140px;" /></div>
              </div>
              <div class="timeline">
                <div class="timeline-item"><div class="timeline-dot paid">âœ“</div><div class="timeline-body"><div class="timeline-title">Ana MartÃ­nez â€” Abril 2026</div><div class="timeline-meta">Pagado el 28/04/2026 Â· Ref: #2026-0431</div><div class="timeline-amount">$12.50</div></div></div>
                <div class="timeline-item"><div class="timeline-dot paid">âœ“</div><div class="timeline-body"><div class="timeline-title">Ana MartÃ­nez â€” Marzo 2026</div><div class="timeline-meta">Pagado el 05/03/2026 Â· Ref: #2026-0312</div><div class="timeline-amount">$12.50</div></div></div>
                <div class="timeline-item"><div class="timeline-dot overdue">âœ•</div><div class="timeline-body"><div class="timeline-title">Ana MartÃ­nez â€” Febrero 2026</div><div class="timeline-meta">Vencido â€” sin pago registrado</div><div class="timeline-amount" style="color:var(--danger)">$12.50</div></div></div>
                <div class="timeline-item"><div class="timeline-dot paid">âœ“</div><div class="timeline-body"><div class="timeline-title">Ana MartÃ­nez â€” Enero 2026</div><div class="timeline-meta">Pagado el 10/01/2026 Â· Ref: #2026-0101</div><div class="timeline-amount">$12.50</div></div></div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Perfil del cliente</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">CÃ³digo</span><span class="td-mono">CLT-001</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Nombre</span><span style="font-weight:700; font-size:.84rem;">Ana MartÃ­nez</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Sector</span><span class="badge badge-blue">A-3</span></div>
              <div class="stat-row"><span class="text-muted" style="font-size:.75rem">Estado</span><span class="badge badge-green">Al dÃ­a</span></div>
              <div class="stat-row" style="margin-bottom:16px;"><span class="text-muted" style="font-size:.75rem">Tarifa</span><span class="td-mono">$12.50/mes</span></div>
              <div style="font-size:.7rem; color:var(--text-muted); margin-bottom:6px;">Cumplimiento de pago</div>
              <div class="progress-bar-wrap mb-8"><div class="progress-bar-fill" style="width:83%"></div></div>
              <div style="font-size:.7rem; color:var(--text-muted);">10 de 12 meses pagados a tiempo (83%)</div>
              <div class="btn-group mt-16"><button class="btn btn-primary btn-sm w-full" onclick="showToast('Abriendo registro de pagoâ€¦','info')">+ Registrar pago</button></div>
            </div>
          </div>
        </div>
      </div><!-- /clientes -->

