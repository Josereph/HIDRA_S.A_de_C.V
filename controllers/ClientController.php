<?php
// controllers/ClientController.php

class ClientController {
    
    private function render($view, $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
    }

    public function index() {
        $clientModel = new Client();
        $clientes = $clientModel->getAll();
        $this->render('clients/index', [
            'title' => 'Gestión de Clientes',
            'clientes' => $clientes
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientModel = new Client();
            $data = [
                'tipo_persona' => $_POST['tipo_persona'],
                'nombre' => $_POST['nombre'],
                'identificador' => $_POST['identificador'],
                'historial' => $_POST['historial']
            ];
            
            try {
                $clientModel->create($data);
                session_start();
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Cliente registrado con éxito.'
                ];
                header('Location: /clientes');
                exit;
            } catch (Exception $e) {
                session_start();
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Error al registrar: ' . $e->getMessage()
                ];
                header('Location: /clientes/create');
                exit;
            }
        }

        $this->render('clients/create', [
            'title' => 'Nuevo Cliente'
        ]);
    }
}
