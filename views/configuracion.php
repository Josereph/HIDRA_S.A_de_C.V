      <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
           VISTA 6: CONFIGURACIÃ“N
      â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
      <div class="view active" id="view-config">
        <div class="page-header">
          <div><h1 class="page-title">ConfiguraciÃ³n</h1><p class="page-subtitle">ParÃ¡metros del sistema, tarifas y perfiles de usuario</p></div>
        </div>

        <div class="section-tabs" data-group="cfg-tabs">
          <div class="section-tab active" data-panel="cfg-general"  data-group="cfg-tabs"><i class="bi bi-gear-fill"></i> General</div>
          <div class="section-tab"        data-panel="cfg-tarifas" data-group="cfg-tabs"><i class="bi bi-currency-dollar"></i> Tarifas</div>
          <div class="section-tab"        data-panel="cfg-moras"   data-group="cfg-tabs"><i class="bi bi-exclamation-triangle-fill"></i> Moras</div>
          <div class="section-tab"        data-panel="cfg-usuarios" data-group="cfg-tabs"><i class="bi bi-person-fill"></i> Usuarios y roles</div>
        </div>

        <div class="tab-panel active" data-panel="cfg-general" data-group="cfg-tabs">
          <div class="card" style="max-width:600px;">
            <div class="card-header"><span class="card-title">Datos de la empresa</span></div>
            <div class="form-group"><label class="form-label">Nombre de la empresa</label><input type="text" class="form-control" value="HIDRA S.A. de C.V." /></div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">NIT</label><input type="text" class="form-control" value="0614-010101-000-0" /></div>
              <div class="form-group"><label class="form-label">NRC</label><input type="text" class="form-control" value="123456-7" /></div>
            </div>
            <div class="form-group"><label class="form-label">DirecciÃ³n fiscal</label><input type="text" class="form-control" value="Calle Principal #1, Ilobasco, CabaÃ±as" /></div>
            <div class="form-row">
              <div class="form-group"><label class="form-label">TelÃ©fono</label><input type="text" class="form-control" value="2362-0000" /></div>
              <div class="form-group"><label class="form-label">DÃ­a de vencimiento</label><select class="form-control form-select"><option selected>DÃ­a 5 de cada mes</option><option>DÃ­a 10</option><option>DÃ­a 15</option></select></div>
            </div>
            <button class="btn btn-primary mt-16" onclick="showToast('ConfiguraciÃ³n guardada correctamente','success')">ðŸ’¾ Guardar cambios</button>
          </div>
        </div>

        <div class="tab-panel" data-panel="cfg-tarifas" data-group="cfg-tabs">
          <div class="grid-2" style="max-width:900px;">
            <div class="card">
              <div class="card-header"><span class="card-title">Tarifas vigentes</span><button class="btn btn-agua btn-sm">+ Nueva tarifa</button></div>
              <div class="table-wrap mb-0">
                <table>
                  <thead><tr><th>CategorÃ­a</th><th>Tarifa / mes</th><th>LÃ­mite mÂ³</th><th></th></tr></thead>
                  <tbody>
                    <?php foreach($tarifas_lista as $t): ?>
                    <tr>
                      <td class="td-primary"><?= htmlspecialchars($t['nombre_tarifa']) ?></td>
                      <td class="td-mono">$<?= number_format($t['precio_m3'], 4) ?></td>
                      <td>Cargo fijo: $<?= number_format($t['cargo_fijo'], 2) ?></td>
                      <td><button class="btn btn-ghost btn-sm" onclick="showToast('Editando tarifaâ€¦','info')">âœ</button></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">ParÃ¡metros de mora</span></div>
              <div class="form-group"><label class="form-label">DÃ­as antes de aplicar mora</label><input type="number" class="form-control" value="15" /></div>
              <div class="form-group"><label class="form-label">% recargo por mora mensual</label><input type="number" class="form-control" value="5" /></div>
              <div class="form-group"><label class="form-label">DÃ­as para iniciar proceso de corte</label><input type="number" class="form-control" value="30" /></div>
              <div class="form-group"><label class="form-label">Cargo por reconexiÃ³n</label><input type="text" class="form-control" value="$5.00" /></div>
              <button class="btn btn-primary btn-sm mt-16" onclick="showToast('ParÃ¡metros de mora actualizados','success')">ðŸ’¾ Guardar parÃ¡metros</button>
            </div>
          </div>
        </div>

        <!-- â”€â”€ TAB: MORAS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
        <div class="tab-panel" data-panel="cfg-moras" data-group="cfg-tabs">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:900px;">

            <!-- Formulario de reglas de mora -->
            <div class="card">
              <div class="card-header"><h2 class="card-title">Regla de mora activa</h2></div>

              <div class="alert alert-info mb-16">
                <div class="alert-icon">âš </div>
                <div class="alert-body">
                  <div class="alert-title">Solo administradores</div>
                  <div class="alert-msg">Los cambios afectan el cÃ¡lculo de todas las facturas futuras.</div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">DÃ­as de gracia</label>
                <input type="number" class="form-control" id="mora-dias" value="10" min="0" max="30" />
                <span style="font-size:.72rem;color:var(--text-muted);margin-top:4px;display:block;">DÃ­as despuÃ©s del vencimiento antes de aplicar mora.</span>
              </div>

              <div class="form-group">
                <label class="form-label">Tipo de recargo</label>
                <select class="form-control form-select" id="mora-tipo" onchange="toggleMoraValor(this.value)">
                  <option value="fijo">Monto fijo ($)</option>
                  <option value="porcentaje">Porcentaje (%)</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" id="mora-valor-label">Valor de mora ($)</label>
                <div style="display:flex;align-items:center;gap:8px;">
                  <input type="number" class="form-control" id="mora-valor" value="1.00" step="0.01" min="0" style="flex:1;" />
                  <span id="mora-unidad" style="font-weight:700;color:var(--text-muted);white-space:nowrap;">$ por factura</span>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Aplica a partir de</label>
                <select class="form-control form-select">
                  <option selected>Fecha de vencimiento + dÃ­as de gracia</option>
                  <option>Fecha de emisiÃ³n de factura</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Estado de la regla</label>
                <select class="form-control form-select" id="mora-estado">
                  <option value="activo" selected>âœ… Activo</option>
                  <option value="inactivo">â¸ Inactivo</option>
                </select>
              </div>

              <div class="card-footer">
                <button class="btn btn-ghost" type="button">Cancelar</button>
                <button class="btn btn-primary" type="button"
                  onclick="showToast('Regla de mora guardada correctamente','success')">
                  ðŸ’¾ Guardar configuraciÃ³n
                </button>
              </div>
            </div>

            <!-- Historial de reglas -->
            <div class="card">
              <div class="card-header"><h2 class="card-title">Historial de reglas</h2></div>
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr><th>Fecha</th><th>Tipo</th><th>Valor</th><th>Gracia</th><th>Usuario</th><th>Estado</th></tr>
                  </thead>
                  <tbody>
                    <?php foreach($moras_lista as $m): ?>
                    <tr>
                      <td><?= htmlspecialchars(date('d/m/Y', strtotime($m['fecha_inicio']))) ?></td>
                      <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $m['tipo_mora']))) ?></td>
                      <td class="td-mono"><?= $m['tipo_mora'] === 'monto_fijo' ? '$' . number_format($m['monto_fijo'], 2) : number_format($m['porcentaje'], 2) . '%' ?></td>
                      <td><?= htmlspecialchars($m['dias_gracia']) ?> dÃ­as</td>
                      <td>-</td>
                      <td>
                        <span class="badge badge-<?= $m['estado'] === 'activa' ? 'green' : 'yellow' ?>">
                          <?= ucfirst(htmlspecialchars($m['estado'])) ?>
                        </span>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div><!-- /cfg-moras -->

        <div class="tab-panel" data-panel="cfg-usuarios" data-group="cfg-tabs">
          <div class="flex-between mb-16">
            <div></div>
            <button class="btn btn-primary btn-sm">+ Nuevo usuario</button>
          </div>
          <div class="table-wrap" style="max-width:800px;">
            <table>
              <thead><tr><th>Usuario</th><th>Correo</th><th>Perfil / Rol</th><th>Permisos</th><th>Estado</th><th>Acciones</th></tr></thead>
              <tbody>
                <?php foreach($operadores_lista as $op): ?>
                <tr>
                  <td>
                    <div class="flex-gap">
                      <div class="user-avatar" style="width:30px;height:30px;font-size:.7rem;background:var(--grad-brand);border:none;">
                        <?= strtoupper(substr($op['nombre_completo'], 0, 2)) ?>
                      </div>
                      <span class="td-primary"><?= htmlspecialchars($op['nombre_completo']) ?></span>
                    </div>
                  </td>
                  <td class="td-mono"><?= htmlspecialchars($op['correo'] ?? '-') ?></td>
                  <td><span class="badge badge-blue"><?= ucfirst(htmlspecialchars($op['rol'])) ?></span></td>
                  <td style="font-size:.72rem; color:var(--text-muted);">-</td>
                  <td>
                    <span class="badge badge-<?= $op['estado'] === 'activo' ? 'green' : 'red' ?>">
                      <?= ucfirst(htmlspecialchars($op['estado'])) ?>
                    </span>
                  </td>
                  <td><button class="btn btn-ghost btn-sm">âœ Editar</button></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /config -->
