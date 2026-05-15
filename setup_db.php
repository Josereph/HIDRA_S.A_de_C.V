<?php
/**
 * setup_db.php — Crea las tablas de hidra_sa_de_cv si no existen.
 * Ejecutar UNA SOLA VEZ desde el navegador: http://localhost/HIDRA_S.A_de_C.V/setup_db.php
 * Borrar o mover este archivo después de usarlo.
 */

define('DB_HOST',    'localhost');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_NAME',    'hidra_sa_de_cv');
define('DB_CHARSET', 'utf8mb4');

try {
    // Conectar sin seleccionar DB para poder crearla si no existe
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET,
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");

    $sql = "
    CREATE TABLE IF NOT EXISTS tipos_usuario (
        id_tipo INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(60) NOT NULL UNIQUE,
        descripcion TEXT NULL
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS sectores (
        id_sector INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        descripcion TEXT NULL
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS operadores (
        id_operador INT AUTO_INCREMENT PRIMARY KEY,
        nombre_completo VARCHAR(150) NOT NULL,
        usuario VARCHAR(80) NOT NULL UNIQUE,
        correo VARCHAR(150) NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        rol ENUM('administrador','operador','cobrador','lector') NOT NULL DEFAULT 'operador',
        telefono VARCHAR(25) NULL,
        estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
        ultimo_acceso DATETIME NULL,
        fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS usuarios (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(150) NOT NULL,
        apellido VARCHAR(150) NOT NULL,
        dui VARCHAR(10) NULL UNIQUE,
        correo VARCHAR(150) NULL,
        telefono VARCHAR(25) NULL,
        direccion TEXT NULL,
        id_sector INT NULL,
        estado ENUM('activo','inactivo','moroso') NOT NULL DEFAULT 'activo',
        fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_usuarios_sector FOREIGN KEY (id_sector) REFERENCES sectores(id_sector)
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS medidores (
        id_medidor INT AUTO_INCREMENT PRIMARY KEY,
        codigo VARCHAR(60) NOT NULL UNIQUE,
        id_usuario INT NULL,
        marca VARCHAR(80) NULL,
        estado ENUM('activo','inactivo','dañado') NOT NULL DEFAULT 'activo',
        fecha_instalacion DATE NULL,
        CONSTRAINT fk_medidores_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS tarifas (
        id_tarifa INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        precio_m3 DECIMAL(8,4) NOT NULL,
        cargo_fijo DECIMAL(8,2) NOT NULL DEFAULT 0,
        vigente TINYINT(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS lecturas (
        id_lectura INT AUTO_INCREMENT PRIMARY KEY,
        id_medidor INT NOT NULL,
        id_operador_registra INT NULL,
        lectura_anterior DECIMAL(10,2) NOT NULL DEFAULT 0,
        lectura_actual DECIMAL(10,2) NOT NULL,
        consumo_m3 DECIMAL(10,2) GENERATED ALWAYS AS (lectura_actual - lectura_anterior) STORED,
        fecha_lectura DATE NOT NULL,
        observaciones TEXT NULL,
        CONSTRAINT fk_lecturas_medidor FOREIGN KEY (id_medidor) REFERENCES medidores(id_medidor),
        CONSTRAINT fk_lecturas_operador FOREIGN KEY (id_operador_registra) REFERENCES operadores(id_operador)
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS moras (
        id_mora INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        monto DECIMAL(10,2) NOT NULL,
        fecha_mora DATE NOT NULL,
        pagada TINYINT(1) NOT NULL DEFAULT 0,
        CONSTRAINT fk_moras_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS facturas (
        id_factura INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_lectura INT NULL,
        id_tarifa INT NULL,
        id_operador_emite INT NULL,
        monto_total DECIMAL(10,2) NOT NULL,
        estado ENUM('pendiente','pagada','anulada') NOT NULL DEFAULT 'pendiente',
        fecha_emision DATE NOT NULL,
        fecha_vencimiento DATE NULL,
        CONSTRAINT fk_facturas_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
        CONSTRAINT fk_facturas_lectura FOREIGN KEY (id_lectura) REFERENCES lecturas(id_lectura),
        CONSTRAINT fk_facturas_tarifa FOREIGN KEY (id_tarifa) REFERENCES tarifas(id_tarifa),
        CONSTRAINT fk_facturas_operador FOREIGN KEY (id_operador_emite) REFERENCES operadores(id_operador)
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS pagos (
        id_pago INT AUTO_INCREMENT PRIMARY KEY,
        id_factura INT NOT NULL,
        id_operador_registra INT NULL,
        monto_pagado DECIMAL(10,2) NOT NULL,
        fecha_pago DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        metodo_pago ENUM('efectivo','transferencia','cheque') NOT NULL DEFAULT 'efectivo',
        observaciones TEXT NULL,
        CONSTRAINT fk_pagos_factura FOREIGN KEY (id_factura) REFERENCES facturas(id_factura),
        CONSTRAINT fk_pagos_operador FOREIGN KEY (id_operador_registra) REFERENCES operadores(id_operador)
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS configuracion_sistema (
        id_config INT AUTO_INCREMENT PRIMARY KEY,
        clave VARCHAR(100) NOT NULL UNIQUE,
        valor TEXT NULL,
        descripcion TEXT NULL
    ) ENGINE=InnoDB;
    ";

    // Execute each statement
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
        if ($stmt) $pdo->exec($stmt);
    }

    // Seed admin user if table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM operadores")->fetchColumn();
    if ($count == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO operadores (nombre_completo, usuario, correo, password_hash, rol)
                    VALUES ('Administrador', 'admin', 'admin@hidra.sv', '$hash', 'administrador')");
        echo "<p>✅ Usuario admin creado (usuario: <b>admin</b> / contraseña: <b>admin123</b>)</p>";
    } else {
        echo "<p>ℹ️ La tabla operadores ya tiene datos.</p>";
    }

    echo "<p>✅ Base de datos <b>" . DB_NAME . "</b> configurada correctamente.</p>";
    echo "<p>⚠️ <b>Elimina este archivo (setup_db.php) después de usarlo.</b></p>";

} catch (PDOException $e) {
    echo "<p>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
