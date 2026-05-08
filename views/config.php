<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    
    <!-- Panel de Tarifas -->
    <div class="card">
        <div class="card-header">
            <h2>Gestión de Tarifas</h2>
            <button class="btn btn-primary" onclick="alert('Funcionalidad de agregar tarifa pendiente')">+</button>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio Base ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tarifas)): ?>
                        <tr><td colspan="3">No hay tarifas configuradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($tarifas as $tarifa): ?>
                            <tr>
                                <td><?= htmlspecialchars($tarifa['id']) ?></td>
                                <td><?= htmlspecialchars($tarifa['nombre']) ?></td>
                                <td>$<?= number_format($tarifa['precio_base'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel de Usuarios / Operadores -->
    <div class="card">
        <div class="card-header">
            <h2>Operadores del Sistema</h2>
            <button class="btn btn-primary" onclick="alert('Funcionalidad de agregar operador pendiente')">+</button>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr><td colspan="3">No hay usuarios registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = $usuario['rol'] === 'Admin' ? 'badge-active' : 'badge-review';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($usuario['rol']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
