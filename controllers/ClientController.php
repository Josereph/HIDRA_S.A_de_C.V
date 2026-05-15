<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClientController {
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    public function index() {
        $clientes = $this->clienteModel->getAll();
        
        // Incluir el layout y la vista
        $content = __DIR__ . '/../views/clientes.php';
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tipo_persona' => $_POST['tipo_persona'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'identificador' => $_POST['identificador'] ?? '',
                'historial' => $_POST['historial'] ?? ''
            ];

            if ($this->clienteModel->create($data)) {
                // Redirigir a clientes
                header('Location: ' . BASE_PATH . '/clientes');
                exit;
            } else {
                echo "Error al guardar el cliente.";
            }
        } else {
            // Mostrar formulario de creación
            $content = __DIR__ . '/../views/clientes_crear.php';
            require_once __DIR__ . '/../views/layouts/main.php';
        }
    }
}
