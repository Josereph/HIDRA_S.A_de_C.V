<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIDRA - Sistema de Facturación</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="bi bi-droplet-fill"></i> HIDRA</h2>
                <p>Agua Potable</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/clientes">Clientes</a></li>
                    <li><a href="/territorio">Territorio</a></li>
                    <li><a href="/configuracion">Configuración</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="topbar">
                <h1><?= htmlspecialchars($title ?? 'Panel') ?></h1>
                <div class="user-info">Administrador</div>
            </header>
            
            <div class="content-wrapper">
                <?php if (isset($_SESSION['alert'])): ?>
                    <div class="alert alert-<?= $_SESSION['alert']['type'] ?>">
                        <?= htmlspecialchars($_SESSION['alert']['message']) ?>
                    </div>
                    <?php unset($_SESSION['alert']); ?>
                <?php endif; ?>

                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
</body>
</html>
