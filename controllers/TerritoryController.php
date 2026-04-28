<?php
// controllers/TerritoryController.php

class TerritoryController {
    
    private function render($view, $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
    }

    public function index() {
        $sectorModel = new Sector();
        $houseModel = new House();
        $clientModel = new Client();
        
        $sectores = $sectorModel->getAll();
        $clientes = $clientModel->getAll();
        
        // Obtener casas agrupadas por sector
        $casasPorSector = [];
        $todasLasCasas = [];
        foreach ($sectores as $sector) {
            $casas = $houseModel->getBySector($sector['id']);
            $casasPorSector[$sector['id']] = $casas;
            $todasLasCasas = array_merge($todasLasCasas, $casas);
        }

        // Ya no insertamos mock data aquí porque usamos el mapa.

        $this->render('territory/index', [
            'title' => 'Gestión de Territorio',
            'sectores' => $sectores,
            'casasPorSector' => $casasPorSector,
            'todasLasCasas' => $todasLasCasas,
            'clientes' => $clientes
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $houseModel = new House();
            $data = [
                'sector_id' => $_POST['sector_id'],
                'direccion' => $_POST['direccion'],
                'lat' => $_POST['lat'],
                'lng' => $_POST['lng']
            ];
            
            try {
                $houseModel->create($data);
                session_start();
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Vivienda registrada correctamente en el mapa.'
                ];
            } catch (Exception $e) {
                session_start();
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Error al registrar: ' . $e->getMessage()
                ];
            }
            header('Location: /territorio');
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $houseModel = new House();
            $id = $_POST['house_id'];
            $data = [
                'estado' => $_POST['estado'],
                'cliente_id' => $_POST['cliente_id']
            ];
            
            try {
                $houseModel->update($id, $data);
                session_start();
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Vivienda actualizada (Usuario y Estado asignados).'
                ];
            } catch (Exception $e) {
                session_start();
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Error al actualizar: ' . $e->getMessage()
                ];
            }
            header('Location: /territorio');
            exit;
        }
    }
}
