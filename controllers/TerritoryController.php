<?php
require_once __DIR__ . '/../models/Territorio.php';
require_once __DIR__ . '/../models/Cliente.php';

class TerritoryController {
    private $territorioModel;
    private $clienteModel;

    public function __construct() {
        $this->territorioModel = new Territorio();
        $this->clienteModel = new Cliente();
    }

    public function index() {
        $sectores = $this->territorioModel->getSectores();
        $casas = $this->territorioModel->getCasas();
        $clientes = $this->clienteModel->getAll();

        $casasPorSector = [];
        foreach ($sectores as $sector) {
            $casasPorSector[$sector['id']] = [];
        }

        foreach ($casas as $casa) {
            if (isset($casasPorSector[$casa['sector_id']])) {
                $casasPorSector[$casa['sector_id']][] = $casa;
            }
        }

        $content = __DIR__ . '/../views/territorios.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function sector() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_PATH . '/territorio');
            exit;
        }

        $sector = $this->territorioModel->getSectorById($id);
        $casas = $this->territorioModel->getCasasBySector($id);
        $clientes = $this->clienteModel->getAll();

        if (!$sector) {
            echo "Sector no encontrado.";
            return;
        }

        $content = __DIR__ . '/../views/territorios.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function casa() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_PATH . '/territorio');
            exit;
        }

        $casa = $this->territorioModel->getCasaById($id);
        if (!$casa) {
            echo "Casa no encontrada.";
            return;
        }

        $content = __DIR__ . '/../views/territorios.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
