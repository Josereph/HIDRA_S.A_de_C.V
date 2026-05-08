<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2>Registrar Nuevo Cliente</h2>
        <a href="/clientes" class="btn btn-secondary">Volver</a>
    </div>
    <form action="/clientes/create" method="POST">
        <div class="form-group">
            <label for="tipo_persona">Tipo de Persona</label>
            <select name="tipo_persona" id="tipo_persona" class="form-control" onchange="toggleIdentificador()" required>
                <option value="Natural">Persona Natural</option>
                <option value="Juridica">Persona Jurídica</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre">Nombre Completo / Razón Social</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="identificador" id="label_identificador">DUI</label>
            <input type="text" name="identificador" id="identificador" class="form-control" placeholder="00000000-0" required>
        </div>

        <div class="form-group">
            <label for="historial">Notas Adicionales (Historial Inicial)</label>
            <textarea name="historial" id="historial" rows="4" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Guardar Cliente</button>
    </form>
</div>

<script>
function toggleIdentificador() {
    const tipo = document.getElementById('tipo_persona').value;
    const label = document.getElementById('label_identificador');
    const input = document.getElementById('identificador');

    if (tipo === 'Natural') {
        label.innerText = 'DUI';
        input.placeholder = '00000000-0';
    } else {
        label.innerText = 'NIT';
        input.placeholder = '0000-000000-000-0';
    }
}
</script>
