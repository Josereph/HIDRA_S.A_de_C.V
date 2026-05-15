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
        $title = 'Territorio';
        $sectores = $this->territorioModel->getSectores(true);
        $sectoresActivos = $this->territorioModel->getSectores(false);
        $todasLasCasas = $this->territorioModel->getCasas();
        $clientes = $this->clienteModel->getAll();

        $casasPorSector = [];
        foreach ($sectores as $sector) {
            $casasPorSector[(int)$sector['id']] = [];
        }

        foreach ($todasLasCasas as $casa) {
            $sectorId = (int)($casa['sector_id'] ?? 0);
            if (!isset($casasPorSector[$sectorId])) {
                $casasPorSector[$sectorId] = [];
            }
            $casasPorSector[$sectorId][] = $casa;
        }

        $totalTerritorios = count($sectores);
        $totalTerritoriosActivos = count($sectoresActivos);
        $totalCasas = count($todasLasCasas);
        $totalCasasAsignadas = 0;

        foreach ($todasLasCasas as $casa) {
            if (!empty($casa['sector_id'])) {
                $totalCasasAsignadas++;
            }
        }

        $content = __DIR__ . '/../views/territorio.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function storeCasa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTerritorio();
        }

        $data = [
            'sector_id' => $_POST['sector_id'] ?? '',
            'cliente_id' => $_POST['cliente_id'] ?? null,
            'direccion' => trim($_POST['direccion'] ?? ''),
            'lat' => $_POST['lat'] ?? null,
            'lng' => $_POST['lng'] ?? null,
            'estado' => $_POST['estado'] ?? 'En revisión'
        ];

        if ($data['sector_id'] === '' || $data['direccion'] === '') {
            die('Faltan datos obligatorios para registrar la casa.');
        }

        $this->territorioModel->createCasa($data);
        $this->redirectTerritorio();
    }

    public function updateCasa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTerritorio();
        }

        $data = [
            'id' => $_POST['id'] ?? '',
            'sector_id' => $_POST['sector_id'] ?? '',
            'cliente_id' => $_POST['cliente_id'] ?? null,
            'direccion' => trim($_POST['direccion'] ?? ''),
            'lat' => $_POST['lat'] ?? null,
            'lng' => $_POST['lng'] ?? null,
            'estado' => $_POST['estado'] ?? 'En revisión'
        ];

        if ($data['id'] === '' || $data['sector_id'] === '' || $data['direccion'] === '') {
            die('Faltan datos obligatorios para actualizar la casa.');
        }

        $this->territorioModel->updateCasa($data);
        $this->redirectTerritorio();
    }

    public function deleteCasa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTerritorio();
        }

        $id = $_POST['id'] ?? '';
        if ($id === '') {
            die('No se recibió la casa a eliminar.');
        }

        $this->territorioModel->deleteCasa($id);
        $this->redirectTerritorio();
    }

    public function storeTerritorio() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTerritorio();
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        if ($data['nombre'] === '') {
            die('El nombre del territorio es obligatorio.');
        }

        $this->territorioModel->createTerritorio($data);
        $this->redirectTerritorio();
    }

    public function updateTerritorio() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTerritorio();
        }

        $data = [
            'id' => $_POST['id'] ?? '',
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        if ($data['id'] === '' || $data['nombre'] === '') {
            die('Faltan datos obligatorios para actualizar el territorio.');
        }

        $this->territorioModel->updateTerritorio($data);
        $this->redirectTerritorio();
    }

    public function deleteTerritorio() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTerritorio();
        }

        $id = $_POST['id'] ?? '';
        if ($id === '') {
            die('No se recibió el territorio a desactivar.');
        }

        // Se desactiva en vez de borrar físicamente para no romper usuarios, medidores o viviendas existentes.
        $this->territorioModel->deleteTerritorio($id);
        $this->redirectTerritorio();
    }

    public function assignCasa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectTerritorio();
        }

        $idCasa = $_POST['id_casa'] ?? '';
        $idSector = $_POST['id_sector'] ?? '';

        if ($idCasa === '' || $idSector === '') {
            die('Debe seleccionar una casa y un territorio.');
        }

        $this->territorioModel->assignCasaTerritorio($idCasa, $idSector);
        $this->redirectTerritorio('#vista-territorios');
    }

    // Compatibilidad con las rutas antiguas /territorio/store y /territorio/update
    public function store() {
        $this->storeCasa();
    }

    public function update() {
        $this->updateCasa();
    }

    private function redirectTerritorio($anchor = '') {
        header('Location: ' . BASE_PATH . '/territorio' . $anchor);
        exit;
    }
}
