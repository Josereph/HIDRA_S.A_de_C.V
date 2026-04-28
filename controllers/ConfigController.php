<?php
// controllers/ConfigController.php

class ConfigController {
    
    private function render($view, $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
    }

    public function index() {
        $tariffModel = new Tariff();
        $userModel = new User();
        
        $tarifas = $tariffModel->getAll();
        $usuarios = $userModel->getAll();

        // Datos mock de usuarios si está vacío
        $db = Database::getInstance();
        $checkUsers = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        if ($checkUsers == 0) {
            $db->exec("INSERT INTO usuarios (nombre, email, password, rol) VALUES 
                ('Admin General', 'admin@hidra.com', '123456', 'Admin'),
                ('Operador Juan', 'juan@hidra.com', '123456', 'Operador')
            ");
            $usuarios = $userModel->getAll();
        }

        $this->render('config/index', [
            'title' => 'Configuración del Sistema',
            'tarifas' => $tarifas,
            'usuarios' => $usuarios
        ]);
    }
}
