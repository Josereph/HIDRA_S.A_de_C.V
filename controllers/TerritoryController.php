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

        // Agrupar casas por sector para el mapa global
        $casasPorSector = [];
        foreach ($sectores as $sector) {
            $casasPorSector[$sector['id']] = [];
        }

        foreach ($todasLasCasas as $casa) {
            if (isset($casasPorSector[$casa['sector_id']])) {
                $casasPorSector[$casa['sector_id']][] = $casa;
            }
        }

        // Agrupar sectores por jerarquía
        $jerarquia = [];
        foreach ($sectores as $sec) {
            $dep = $sec['departamento'] ?: 'Sin Departamento';
            $mun = $sec['municipio'] ?: 'Sin Municipio';
            $can = $sec['canton'] ?: 'Sin Cantón';
            $vil = $sec['villa'] ?: 'Sin Villa';

            if (!isset($jerarquia[$dep])) $jerarquia[$dep] = [];
            if (!isset($jerarquia[$dep][$mun])) $jerarquia[$dep][$mun] = [];
            if (!isset($jerarquia[$dep][$mun][$can])) $jerarquia[$dep][$mun][$can] = [];
            if (!isset($jerarquia[$dep][$mun][$can][$vil])) $jerarquia[$dep][$mun][$can][$vil] = [];
            
            $jerarquia[$dep][$mun][$can][$vil][] = $sec;
        }

        $content = __DIR__ . '/../views/territorio.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    // --- SECTOR ACTIONS ---
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

        $content = __DIR__ . '/../views/territorio_sector.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function store_sector() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if ($this->territorioModel->createSector($data)) {
                $redirectUrl = isset($_POST['redirect_to_dashboard']) && $_POST['redirect_to_dashboard'] == 1 
                               ? BASE_PATH . '/views/layouts/pagina_principal.php' 
                               : BASE_PATH . '/territorio';
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                echo "Error al crear sector.";
            }
        }
    }

    public function update_sector() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if ($this->territorioModel->updateSector($data)) {
                $redirectUrl = isset($_POST['redirect_to_dashboard']) && $_POST['redirect_to_dashboard'] == 1 
                               ? BASE_PATH . '/views/layouts/pagina_principal.php' 
                               : BASE_PATH . '/territorio';
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                echo "Error al actualizar sector.";
            }
        }
    }

    public function delete_sector() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id && $this->territorioModel->deleteSector($id)) {
                $redirectUrl = isset($_POST['redirect_to_dashboard']) && $_POST['redirect_to_dashboard'] == 1 
                               ? BASE_PATH . '/views/layouts/pagina_principal.php' 
                               : BASE_PATH . '/territorio';
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                echo "Error al eliminar sector o sector en uso.";
            }
        }
    }

    // --- CASA ACTIONS ---
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

        $content = __DIR__ . '/../views/territorio_casa.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if ($this->territorioModel->createCasa($data)) {
                $redirectUrl = isset($_POST['redirect_to_dashboard']) && $_POST['redirect_to_dashboard'] == 1 
                               ? BASE_PATH . '/views/layouts/pagina_principal.php'
                               : (isset($_POST['redirect_to_sector']) && $_POST['redirect_to_sector'] == 1 
                                  ? BASE_PATH . '/territorio/sector?id=' . $data['sector_id'] 
                                  : BASE_PATH . '/territorio');
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                echo "Error al guardar la vivienda.";
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if ($this->territorioModel->updateCasa($data)) {
                $redirectUrl = isset($_POST['redirect_to_dashboard']) && $_POST['redirect_to_dashboard'] == 1 
                               ? BASE_PATH . '/views/layouts/pagina_principal.php'
                               : (isset($_POST['redirect_to_sector']) && $_POST['redirect_to_sector'] == 1 
                                  ? BASE_PATH . '/territorio/sector?id=' . $data['sector_id'] 
                                  : BASE_PATH . '/territorio');
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                echo "Error al actualizar la vivienda.";
            }
        }
    }

    public function delete_casa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $sector_id = $_POST['sector_id'] ?? null;
            if ($id && $this->territorioModel->deleteCasa($id)) {
                $redirectUrl = isset($_POST['redirect_to_dashboard']) && $_POST['redirect_to_dashboard'] == 1 
                               ? BASE_PATH . '/views/layouts/pagina_principal.php'
                               : (isset($_POST['redirect_to_sector']) && $_POST['redirect_to_sector'] == 1 && $sector_id
                                  ? BASE_PATH . '/territorio/sector?id=' . $sector_id 
                                  : BASE_PATH . '/territorio');
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                echo "Error al eliminar la vivienda.";
            }
        }
    }
}
