<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        // Obtenemos los clientes de la tabla usuarios.
        // Mapeamos para que la vista clientes.php pueda leerlos (id, tipo_persona, nombre, identificador, created_at)
        $stmt = $this->pdo->query("
            SELECT 
                u.id_usuario as id, 
                tu.nombre_tipo as tipo_persona, 
                CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) as nombre, 
                COALESCE(u.dui, u.nit) as identificador, 
                u.fecha_creacion as created_at
            FROM usuarios u
            LEFT JOIN tipos_usuario tu ON u.id_tipo_usuario = tu.id_tipo_usuario
            ORDER BY u.id_usuario DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // $data tiene: tipo_persona, nombre, identificador, historial
        $tipo_persona = $data['tipo_persona'] ?? 'Natural';
        $nombre = $data['nombre'] ?? '';
        $identificador = $data['identificador'] ?? '';
        $historial = $data['historial'] ?? '';

        // Buscamos el id_tipo_usuario
        $stmtTipo = $this->pdo->prepare("SELECT id_tipo_usuario FROM tipos_usuario WHERE nombre_tipo = :tipo LIMIT 1");
        $stmtTipo->execute(['tipo' => $tipo_persona]);
        $tipo = $stmtTipo->fetch(PDO::FETCH_ASSOC);
        $id_tipo_usuario = $tipo ? $tipo['id_tipo_usuario'] : 1; // Default 1

        // Default sector (Asumimos el 1)
        $id_sector = 1;

        // Generamos un código de usuario
        $codigo_usuario = 'CLI-' . time() . '-' . rand(100, 999);

        // Preparamos dui / nit
        $dui = null;
        $nit = null;
        if ($tipo_persona === 'Natural') {
            $dui = $identificador;
        } else {
            $nit = $identificador;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (
                codigo_usuario, id_tipo_usuario, id_sector, nombres, 
                dui, nit, direccion, fecha_registro, referencia_ubicacion
            ) VALUES (
                :codigo, :id_tipo, :id_sector, :nombres, 
                :dui, :nit, :direccion, CURDATE(), :historial
            )
        ");

        return $stmt->execute([
            'codigo' => $codigo_usuario,
            'id_tipo' => $id_tipo_usuario,
            'id_sector' => $id_sector,
            'nombres' => $nombre,
            'dui' => $dui,
            'nit' => $nit,
            'direccion' => 'Sin dirección', // Valor por defecto ya que el form no lo pide
            'historial' => $historial
        ]);
    }
}
