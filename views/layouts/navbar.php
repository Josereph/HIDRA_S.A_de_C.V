<header class="topbar">
    <h1><?= htmlspecialchars($title ?? 'Panel') ?></h1>
    <div class="user-info">
        <?= htmlspecialchars($_SESSION['operador_nombre'] ?? 'Administrador') ?>
    </div>
</header>
