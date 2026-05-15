<div class="dashboard-container">
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Resumen general del sistema</p>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">Clientes Activos</div>
                <div class="kpi-value">0</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-droplet-fill"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">Sectores</div>
                <div class="kpi-value">0</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-house-fill"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">Viviendas</div>
                <div class="kpi-value">0</div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="kpi-content">
                <div class="kpi-label">Facturado (Mes)</div>
                <div class="kpi-value">$0.00</div>
            </div>
        </div>
    </div>

    <div class="quick-links">
        <h2>Accesos Rápidos</h2>
        <div class="links-grid">
            <a href="<?= BASE_PATH ?>/clientes" class="quick-link-card">
                <i class="bi bi-people"></i>
                <span>Gestionar Clientes</span>
            </a>
            <a href="<?= BASE_PATH ?>/territorio" class="quick-link-card">
                <i class="bi bi-map"></i>
                <span>Gestionar Territorio</span>
            </a>
            <a href="<?= BASE_PATH ?>/configuracion" class="quick-link-card">
                <i class="bi bi-gear"></i>
                <span>Configuración</span>
            </a>
        </div>
    </div>
</div>

<style>
.dashboard-container { padding: 20px; }
.page-header { margin-bottom: 30px; }
.page-header h1 { margin: 0 0 5px 0; font-size: 2rem; }
.page-header p { margin: 0; color: #666; }
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
.kpi-card { background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; display: flex; align-items: center; gap: 15px; }
.kpi-icon { width: 50px; height: 50px; border-radius: 8px; background: #f0f7ff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #1976d2; }
.kpi-content { flex: 1; }
.kpi-label { font-size: 0.85rem; color: #999; margin-bottom: 5px; }
.kpi-value { font-size: 1.8rem; font-weight: bold; color: #333; }
.quick-links h2 { margin-bottom: 20px; font-size: 1.5rem; }
.links-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
.quick-link-card { background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 25px; text-align: center; text-decoration: none; color: inherit; transition: all 0.3s; display: flex; flex-direction: column; align-items: center; gap: 10px; }
.quick-link-card:hover { border-color: #1976d2; box-shadow: 0 4px 12px rgba(25, 118, 210, 0.15); transform: translateY(-2px); }
.quick-link-card i { font-size: 2rem; color: #1976d2; }
.quick-link-card span { font-weight: 500; }
</style>
