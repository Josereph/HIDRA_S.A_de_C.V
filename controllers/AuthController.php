<?php
require_once __DIR__ . '/../models/Operador.php';

class AuthController {
    private $operadorModel;

    public function __construct() {
        $this->operadorModel = new Operador();
    }

    public function login() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $identificador = $input['identificador'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($identificador) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Faltan credenciales']);
            return;
        }

        $usuario = $this->operadorModel->findByEmailOrUsername($identificador);

        if (!$usuario) {
            // Si no existe, revisar si la base de datos de operadores está vacía para auto-sembrar
            $stmt = (new Operador())->findByEmailOrUsername('admin');
            $pdo = Database::getInstance();
            $count = $pdo->query("SELECT COUNT(*) FROM operadores")->fetchColumn();
            if ($count == 0) {
                // Seed admin user
                $hash = password_hash('admin123', PASSWORD_DEFAULT);
                $pdo->exec("INSERT INTO operadores (nombre_completo, usuario, correo, password_hash, rol) VALUES ('Administrador', 'admin', 'admin@hidra.sv', '$hash', 'administrador')");
                $usuario = $this->operadorModel->findByEmailOrUsername($identificador);
            }
        }

        if ($usuario) {
            // Verificar contraseña usando password_verify
            if (password_verify($password, $usuario['password_hash'])) {
                if ($usuario['estado'] !== 'activo') {
                    echo json_encode(['success' => false, 'message' => 'Usuario inactivo']);
                    return;
                }
                
                // Iniciar sesión
                session_start();
                $_SESSION['operador_id'] = $usuario['id_operador'];
                $_SESSION['operador_nombre'] = $usuario['nombre_completo'];
                $_SESSION['operador_rol'] = $usuario['rol'];
                
                echo json_encode(['success' => true, 'message' => 'Login exitoso']);
                return;
            }
        }
        
        // Credenciales inválidas
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
}
