<?php
class DashboardController {
    public function index() {
        // Load main dashboard view
        $content = __DIR__ . '/../views/dashboard.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function operaciones() {
        $content = __DIR__ . '/../views/operaciones.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function estadisticas() {
        $content = __DIR__ . '/../views/estadisticas.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function reportes() {
        $content = __DIR__ . '/../views/reportes.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
