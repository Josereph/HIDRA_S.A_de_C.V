<?php
// controllers/TerritoryController.php
require_once __DIR__ . '/../models/Territorio.php';

class TerritoryController {
    private $territorioModel;

    public function __construct() {
        $this->territorioModel = new Territorio();
    }

    public function index() {
        session_start();

        $sectores       = $this->territorioModel->getSectores();
        $clientes       = $this->territorioModel->getClientesDisponibles();
        $page           = max(1, (int)($_GET['page'] ?? 1));
        $perPage        = 10;
        $viviendas      = $this->territorioModel->getViviendas($page, $perPage);
        $totalViviendas = $this->territorioModel->countViviendas();
        $lastPage       = (int)ceil($totalViviendas / $perPage);
        $title          = 'Territorio';

        ob_start();
        require __DIR__ . '/../views/territorio.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layouts/main.php';
    }
}
