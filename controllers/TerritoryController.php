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
        $todasLasCasas = $this->territorioModel->getCasas();
        $clientes = $this->clienteModel->getAll();

        // Agrupar casas por sector
        $casasPorSector = [];
        foreach ($sectores as $sector) {
            $casasPorSector[$sector['id']] = [];
        }

        foreach ($todasLasCasas as $casa) {
            if (isset($casasPorSector[$casa['sector_id']])) {
                $casasPorSector[$casa['sector_id']][] = $casa;
            }
        }

        $content = __DIR__ . '/../views/territorio.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'sector_id' => $_POST['sector_id'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'lat' => $_POST['lat'] ?? null,
                'lng' => $_POST['lng'] ?? null
            ];

            if ($this->territorioModel->createCasa($data)) {
                header('Location: ' . BASE_PATH . '/territorio');
                exit;
            } else {
                echo "Error al guardar la vivienda.";
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'house_id' => $_POST['house_id'] ?? '',
                'cliente_id' => $_POST['cliente_id'] ?? null,
                'estado' => $_POST['estado'] ?? 'En revisión'
            ];

            if ($this->territorioModel->updateCasa($data)) {
                header('Location: ' . BASE_PATH . '/territorio');
                exit;
            } else {
                echo "Error al actualizar la vivienda.";
            }
        }
    }
}
