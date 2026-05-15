<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../config/database.php';

class ClientController {
    private $clienteModel;
    private $pdo;

    public function __construct() {
        $this->clienteModel = new Cliente();
        $this->pdo = Database::getInstance();
    }

    public function index() {
        $clientes = $this->clienteModel->getAll();
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
                header('Location: ' . BASE_PATH . '/clientes');
                exit;
            } else {
                echo "Error al guardar el cliente.";
            }
        } else {
            $content = __DIR__ . '/../views/clientes.php';
            require_once __DIR__ . '/../views/layouts/main.php';
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? null,
                'nombres' => $_POST['nombres'] ?? '',
                'apellidos' => $_POST['apellidos'] ?? '',
                'dui' => $_POST['dui'] ?? null,
                'nit' => $_POST['nit'] ?? null,
                'correo' => $_POST['correo'] ?? null,
                'telefono' => $_POST['telefono'] ?? null,
                'direccion' => $_POST['direccion'] ?? null,
            ];

            if ($data['id']) {
                $stmt = $this->pdo->prepare("
                    UPDATE usuarios
                    SET nombres = :nombres, apellidos = :apellidos, dui = :dui, nit = :nit,
                        correo = :correo, telefono = :telefono, direccion = :direccion
                    WHERE id_usuario = :id
                ");
                if ($stmt->execute($data)) {
                    header('Location: ' . BASE_PATH . '/clientes');
                    exit;
                }
            }
            echo "Error al actualizar el cliente.";
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
                if ($stmt->execute([$id])) {
                    header('Location: ' . BASE_PATH . '/clientes');
                    exit;
                }
            }
            echo "Error al eliminar el cliente.";
        }
    }
}
