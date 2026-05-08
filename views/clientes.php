<div class="card">
    <div class="card-header">
        <h2>Listado de Clientes</h2>
        <a href="/clientes/create" class="btn btn-primary">+ Nuevo Cliente</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Identificador (DUI/NIT)</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No hay clientes registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['id']) ?></td>
                            <td><span class="badge <?= $cliente['tipo_persona'] === 'Natural' ? 'badge-active' : 'badge-review' ?>"><?= htmlspecialchars($cliente['tipo_persona']) ?></span></td>
                            <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                            <td><?= htmlspecialchars($cliente['identificador']) ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($cliente['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
