<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HIDRA S.A de C.V</title>

  <!-- CSS GLOBAL -->
  <link rel="stylesheet" href="/HIDRA_S.A_de_C.V/assets/css/variables.css">
  <link rel="stylesheet" href="/HIDRA_S.A_de_C.V/assets/css/base.css">
  <link rel="stylesheet" href="/HIDRA_S.A_de_C.V/assets/css/layout.css">
  <link rel="stylesheet" href="/HIDRA_S.A_de_C.V/assets/css/sidebar.css">
  <link rel="stylesheet" href="/HIDRA_S.A_de_C.V/assets/css/components.css">
  <link rel="stylesheet" href="/HIDRA_S.A_de_C.V/assets/css/utilities.css">

  <!-- CSS FRONTEND2 -->
  <link rel="stylesheet" href="/HIDRA_S.A_de_C.V/assets/css/operaciones.css">
</head>

<body>

<div class="app-shell">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
      <!-- CORREGIDO: ruta correcta de imágenes -->
      <img src="/HIDRA_S.A_de_C.V/assets/img/logos/HIDRA.png" alt="HIDRA" class="brand-logo">
      <img src="/HIDRA_S.A_de_C.V/assets/img/logos/HIDRA-icon.png" alt="HIDRA" class="brand-logo-icon">

      <button class="sidebar-toggle" id="sidebarToggle" type="button">
        ❯
      </button>
    </div>

    <nav class="sidebar-nav">

      <p class="nav-section-title">Principal</p>

      <a class="nav-item active" data-view="dashboard">
        <span class="nav-icon">🏠</span>
        <span class="nav-label">Dashboard</span>
      </a>

      <a class="nav-item" data-view="clientes">
        <span class="nav-icon">👥</span>
        <span class="nav-label">Clientes</span>
      </a>

      <a class="nav-item" data-view="territorio">
        <span class="nav-icon">🗺️</span>
        <span class="nav-label">Territorio</span>
      </a>

      <p class="nav-section-title">Gestión</p>

      <a class="nav-item" data-view="facturasView">
        <span class="nav-icon">📄</span>
        <span class="nav-label">Facturas</span>
      </a>

      <a class="nav-item" data-view="pagos">
        <span class="nav-icon">💵</span>
        <span class="nav-label">Pagos</span>
      </a>

      <a class="nav-item" data-view="lecturas">
        <span class="nav-icon">💧</span>
        <span class="nav-label">Lecturas</span>
      </a>

      <a class="nav-item" data-view="reportes">
        <span class="nav-icon">📊</span>
        <span class="nav-label">Reportes</span>
      </a>

      <a class="nav-item" data-view="configuracion">
        <span class="nav-icon">⚙️</span>
        <span class="nav-label">Configuración</span>
      </a>

    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar">AD</div>
        <div class="user-info">
          <div class="user-name">Administrador</div>
          <div class="user-role">HIDRA S.A de C.V</div>
        </div>
      </div>
    </div>

  </aside>

  <!-- MAIN -->
  <main class="main-area">

    <!-- TOP NAVBAR -->
    <header class="top-navbar">

      <div class="navbar-breadcrumb">
        <span class="breadcrumb-item">HIDRA</span>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-item active" id="breadPage">Facturas</span>
      </div>

      <div class="navbar-search">
        <span>🔎</span>
        <input type="text" placeholder="Buscar en el sistema...">
      </div>

      <div class="navbar-actions">
        <button class="btn-icon" type="button">
          🔔
          <span class="notif-dot"></span>
        </button>
        <button class="btn-icon" type="button">👤</button>
      </div>

    </header>

    <!-- CONTENIDO -->
    <section class="page-content">
      <?php include __DIR__ . '/../operaciones/pagos.php'; ?>
    </section>

  </main>

</div>

<div id="toastContainer"></div>

<!-- JS GLOBAL -->
<script src="/HIDRA_S.A_de_C.V/assets/js/sidebar.js"></script>
<script src="/HIDRA_S.A_de_C.V/assets/js/ui.js"></script>
<script src="/HIDRA_S.A_de_C.V/assets/js/router.js"></script>

<!-- JS FRONTEND2 -->
<script src="/HIDRA_S.A_de_C.V/assets/js/operaciones.js"></script>

</body>
</html>