<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIDRA - Sistema de Facturación</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/sidebar.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/modals.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/utilities.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/style.css">
    <?php if (($title ?? '') === 'Territorio'): ?>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/territorio.css">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-tint"></i> HIDRA</h2>
                <p>Agua Potable</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="<?= BASE_PATH ?>/clientes">Clientes</a></li>
                    <li><a href="<?= BASE_PATH ?>/territorio">Territorio</a></li>
                    <li><a href="<?= BASE_PATH ?>/configuracion">Configuración</a></li>
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
    <?php if (($title ?? '') === 'Territorio'): ?>
    <script src="<?= BASE_PATH ?>/assets/js/territorio.js" defer></script>
    <?php endif; ?>
</body>
</html>
