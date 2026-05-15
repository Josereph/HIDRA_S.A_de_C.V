-- ==========================================================
-- BASE DE DATOS: HIDRA S.A. DE C.V.
-- Sistema de facturación y administración de servicio de agua
-- Motor recomendado: MySQL / MariaDB
-- Charset: UTF8MB4
-- ==========================================================

DROP DATABASE IF EXISTS hidra_sa_de_cv;
CREATE DATABASE hidra_sa_de_cv
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE hidra_sa_de_cv;

-- ==========================================================
-- 1. TABLA: tipos_usuario
-- Define las categorías de clientes del sistema.
-- Ejemplos: Natural, Jurídico, Residencial, Comercial.
-- ==========================================================
CREATE TABLE tipos_usuario (
    id_tipo_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================================
-- 2. TABLA: sectores
-- Registra las zonas, barrios, colonias o comunidades donde
-- la empresa presta el servicio de agua.
-- ==========================================================
CREATE TABLE sectores (
    id_sector INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sector VARCHAR(150) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================================
-- 3. TABLA: operadores
-- Usuarios internos que administran el sistema.
-- Ejemplos: administrador, operador, cobrador, lector.
-- ==========================================================
CREATE TABLE operadores (
    id_operador INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    usuario VARCHAR(80) NOT NULL UNIQUE,
    correo VARCHAR(150) NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'operador', 'cobrador', 'lector') NOT NULL DEFAULT 'operador',
    telefono VARCHAR(25) NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    ultimo_acceso DATETIME NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================================
-- 4. TABLA: usuarios
-- Clientes/abonados del servicio de agua.
-- Puede representar persona natural o jurídica.
-- ==========================================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    codigo_usuario VARCHAR(30) NOT NULL UNIQUE,
    id_tipo_usuario INT NOT NULL,
    id_sector INT NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NULL,
    nombre_comercial VARCHAR(150) NULL,
    dui VARCHAR(20) NULL UNIQUE,
    nit VARCHAR(25) NULL UNIQUE,
    nrc VARCHAR(25) NULL,
    telefono VARCHAR(25) NULL,
    correo VARCHAR(150) NULL,
    direccion TEXT NOT NULL,
    referencia_ubicacion TEXT NULL,
    estado ENUM('activo', 'inactivo', 'suspendido') NOT NULL DEFAULT 'activo',
    fecha_registro DATE NOT NULL,
    id_operador_creacion INT NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuarios_tipo_usuario
        FOREIGN KEY (id_tipo_usuario) REFERENCES tipos_usuario(id_tipo_usuario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_usuarios_sector
        FOREIGN KEY (id_sector) REFERENCES sectores(id_sector)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_usuarios_operador_creacion
        FOREIGN KEY (id_operador_creacion) REFERENCES operadores(id_operador)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- Índices recomendados para búsquedas frecuentes en usuarios
CREATE INDEX idx_usuarios_nombre ON usuarios(nombres, apellidos);
CREATE INDEX idx_usuarios_estado ON usuarios(estado);
CREATE INDEX idx_usuarios_sector ON usuarios(id_sector);

-- ==========================================================
-- 5. TABLA: medidores
-- Medidores instalados y vinculados a los usuarios.
-- Un usuario puede tener uno o varios medidores.
-- ==========================================================
CREATE TABLE medidores (
    id_medidor INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    numero_medidor VARCHAR(50) NOT NULL UNIQUE,
    marca VARCHAR(100) NULL,
    modelo VARCHAR(100) NULL,
    ubicacion TEXT NULL,
    fecha_instalacion DATE NOT NULL,
    lectura_inicial DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('activo', 'inactivo', 'dañado', 'retirado') NOT NULL DEFAULT 'activo',
    observacion TEXT NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_medidores_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE INDEX idx_medidores_usuario ON medidores(id_usuario);
CREATE INDEX idx_medidores_estado ON medidores(estado);

-- ==========================================================
-- 6. TABLA: tarifas
-- Reglas de cobro del servicio de agua.
-- Pueden configurarse por tipo de usuario.
-- ==========================================================
CREATE TABLE tarifas (
    id_tarifa INT AUTO_INCREMENT PRIMARY KEY,
    id_tipo_usuario INT NOT NULL,
    nombre_tarifa VARCHAR(120) NOT NULL,
    descripcion TEXT NULL,
    precio_m3 DECIMAL(10,4) NOT NULL DEFAULT 0.0000,
    cargo_fijo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    consumo_minimo_m3 DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('activa', 'inactiva') NOT NULL DEFAULT 'activa',
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_tarifas_tipo_usuario
        FOREIGN KEY (id_tipo_usuario) REFERENCES tipos_usuario(id_tipo_usuario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE INDEX idx_tarifas_tipo_usuario ON tarifas(id_tipo_usuario);
CREATE INDEX idx_tarifas_estado ON tarifas(estado);

-- ==========================================================
-- 7. TABLA: lecturas
-- Lecturas mensuales de los medidores.
-- De estas lecturas se calcula el consumo en metros cúbicos.
-- ==========================================================
CREATE TABLE lecturas (
    id_lectura INT AUTO_INCREMENT PRIMARY KEY,
    id_medidor INT NOT NULL,
    id_operador_registra INT NULL,
    mes TINYINT NOT NULL,
    anio SMALLINT NOT NULL,
    lectura_anterior DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    lectura_actual DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    consumo_m3 DECIMAL(10,2) GENERATED ALWAYS AS (lectura_actual - lectura_anterior) STORED,
    fecha_lectura DATE NOT NULL,
    observacion TEXT NULL,
    estado ENUM('registrada', 'corregida', 'anulada') NOT NULL DEFAULT 'registrada',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_lecturas_medidor
        FOREIGN KEY (id_medidor) REFERENCES medidores(id_medidor)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_lecturas_operador
        FOREIGN KEY (id_operador_registra) REFERENCES operadores(id_operador)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT chk_lecturas_mes CHECK (mes BETWEEN 1 AND 12),
    CONSTRAINT chk_lecturas_anio CHECK (anio >= 2000),
    CONSTRAINT chk_lecturas_valores CHECK (lectura_actual >= lectura_anterior),

    CONSTRAINT uq_lectura_medidor_periodo UNIQUE (id_medidor, mes, anio)
) ENGINE=InnoDB;

CREATE INDEX idx_lecturas_periodo ON lecturas(mes, anio);
CREATE INDEX idx_lecturas_medidor ON lecturas(id_medidor);

-- ==========================================================
-- 8. TABLA: moras
-- Configuración o registro de penalizaciones por atraso.
-- Esta tabla permite definir reglas generales de mora.
-- ==========================================================
CREATE TABLE moras (
    id_mora INT AUTO_INCREMENT PRIMARY KEY,
    nombre_mora VARCHAR(120) NOT NULL,
    descripcion TEXT NULL,
    tipo_mora ENUM('monto_fijo', 'porcentaje') NOT NULL DEFAULT 'monto_fijo',
    monto_fijo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    porcentaje DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    dias_gracia INT NOT NULL DEFAULT 0,
    estado ENUM('activa', 'inactiva') NOT NULL DEFAULT 'activa',
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_moras_dias_gracia CHECK (dias_gracia >= 0),
    CONSTRAINT chk_moras_porcentaje CHECK (porcentaje >= 0),
    CONSTRAINT chk_moras_monto CHECK (monto_fijo >= 0)
) ENGINE=InnoDB;

CREATE INDEX idx_moras_estado ON moras(estado);

-- ==========================================================
-- 9. TABLA: facturas
-- Documento de cobro generado a partir de una lectura.
-- Registra deuda, vencimiento, mora, total y estado.
-- ==========================================================
CREATE TABLE facturas (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    numero_factura VARCHAR(40) NOT NULL UNIQUE,
    id_usuario INT NOT NULL,
    id_lectura INT NOT NULL UNIQUE,
    id_tarifa INT NOT NULL,
    id_mora INT NULL,
    id_operador_emite INT NULL,
    mes TINYINT NOT NULL,
    anio SMALLINT NOT NULL,
    fecha_emision DATE NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    consumo_m3 DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    cargo_fijo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    precio_m3 DECIMAL(10,4) NOT NULL DEFAULT 0.0000,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    monto_mora DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    descuento DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    saldo_pendiente DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('pendiente', 'pagada', 'vencida', 'anulada') NOT NULL DEFAULT 'pendiente',
    observacion TEXT NULL,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_facturas_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_facturas_lectura
        FOREIGN KEY (id_lectura) REFERENCES lecturas(id_lectura)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_facturas_tarifa
        FOREIGN KEY (id_tarifa) REFERENCES tarifas(id_tarifa)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_facturas_mora
        FOREIGN KEY (id_mora) REFERENCES moras(id_mora)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_facturas_operador
        FOREIGN KEY (id_operador_emite) REFERENCES operadores(id_operador)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT chk_facturas_mes CHECK (mes BETWEEN 1 AND 12),
    CONSTRAINT chk_facturas_anio CHECK (anio >= 2000),
    CONSTRAINT chk_facturas_montos CHECK (
        consumo_m3 >= 0 AND
        cargo_fijo >= 0 AND
        precio_m3 >= 0 AND
        subtotal >= 0 AND
        monto_mora >= 0 AND
        descuento >= 0 AND
        total >= 0 AND
        saldo_pendiente >= 0
    ),

    CONSTRAINT uq_factura_usuario_periodo UNIQUE (id_usuario, mes, anio)
) ENGINE=InnoDB;

CREATE INDEX idx_facturas_usuario ON facturas(id_usuario);
CREATE INDEX idx_facturas_periodo ON facturas(mes, anio);
CREATE INDEX idx_facturas_estado ON facturas(estado);
CREATE INDEX idx_facturas_vencimiento ON facturas(fecha_vencimiento);

-- ==========================================================
-- 10. TABLA: pagos
-- Registra los pagos realizados por los clientes.
-- Una factura puede tener uno o varios pagos.
-- ==========================================================
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_factura INT NOT NULL,
    id_operador_registra INT NULL,
    fecha_pago DATETIME NOT NULL,
    monto_pagado DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('efectivo', 'transferencia', 'cheque', 'otro') NOT NULL DEFAULT 'efectivo',
    referencia VARCHAR(100) NULL,
    observacion TEXT NULL,
    estado ENUM('registrado', 'anulado') NOT NULL DEFAULT 'registrado',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_pagos_factura
        FOREIGN KEY (id_factura) REFERENCES facturas(id_factura)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_pagos_operador
        FOREIGN KEY (id_operador_registra) REFERENCES operadores(id_operador)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT chk_pagos_monto CHECK (monto_pagado > 0)
) ENGINE=InnoDB;

CREATE INDEX idx_pagos_factura ON pagos(id_factura);
CREATE INDEX idx_pagos_fecha ON pagos(fecha_pago);
CREATE INDEX idx_pagos_estado ON pagos(estado);

-- ==========================================================
-- 11. TABLA: configuracion_sistema
-- Guarda datos generales de la empresa y reglas globales.
-- ==========================================================
CREATE TABLE configuracion_sistema (
    id_configuracion INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(150) NOT NULL,
    nit_empresa VARCHAR(25) NULL,
    nrc_empresa VARCHAR(25) NULL,
    direccion_empresa TEXT NULL,
    telefono_empresa VARCHAR(25) NULL,
    correo_empresa VARCHAR(150) NULL,
    dias_vencimiento_factura INT NOT NULL DEFAULT 30,
    moneda VARCHAR(10) NOT NULL DEFAULT 'USD',
    simbolo_moneda VARCHAR(5) NOT NULL DEFAULT '$',
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_config_dias_vencimiento CHECK (dias_vencimiento_factura > 0)
) ENGINE=InnoDB;

-- ==========================================================
-- TRIGGERS RECOMENDADOS
-- Automatizan cálculos básicos en facturas y pagos.
-- ==========================================================

DELIMITER $$

-- Calcula subtotal, total y saldo pendiente antes de insertar factura.
CREATE TRIGGER trg_facturas_before_insert
BEFORE INSERT ON facturas
FOR EACH ROW
BEGIN
    SET NEW.subtotal = ROUND((NEW.consumo_m3 * NEW.precio_m3) + NEW.cargo_fijo, 2);
    SET NEW.total = ROUND((NEW.subtotal + NEW.monto_mora) - NEW.descuento, 2);
    SET NEW.saldo_pendiente = NEW.total;
END$$

-- Recalcula subtotal, total y saldo pendiente antes de actualizar factura.
CREATE TRIGGER trg_facturas_before_update
BEFORE UPDATE ON facturas
FOR EACH ROW
BEGIN
    SET NEW.subtotal = ROUND((NEW.consumo_m3 * NEW.precio_m3) + NEW.cargo_fijo, 2);
    SET NEW.total = ROUND((NEW.subtotal + NEW.monto_mora) - NEW.descuento, 2);

    IF NEW.estado = 'anulada' THEN
        SET NEW.saldo_pendiente = 0.00;
    END IF;
END$$

-- Actualiza saldo y estado de factura después de registrar un pago.
CREATE TRIGGER trg_pagos_after_insert
AFTER INSERT ON pagos
FOR EACH ROW
BEGIN
    IF NEW.estado = 'registrado' THEN
        UPDATE facturas
        SET saldo_pendiente = GREATEST(saldo_pendiente - NEW.monto_pagado, 0),
            estado = CASE
                WHEN GREATEST(saldo_pendiente - NEW.monto_pagado, 0) = 0 THEN 'pagada'
                ELSE estado
            END
        WHERE id_factura = NEW.id_factura;
    END IF;
END$$

-- Si se anula un pago, devuelve el monto al saldo pendiente.
CREATE TRIGGER trg_pagos_after_update
AFTER UPDATE ON pagos
FOR EACH ROW
BEGIN
    IF OLD.estado = 'registrado' AND NEW.estado = 'anulado' THEN
        UPDATE facturas
        SET saldo_pendiente = saldo_pendiente + OLD.monto_pagado,
            estado = CASE
                WHEN estado = 'pagada' THEN 'pendiente'
                ELSE estado
            END
        WHERE id_factura = OLD.id_factura;
    END IF;
END$$

DELIMITER ;

-- ==========================================================
-- DATOS INICIALES RECOMENDADOS
-- ==========================================================

INSERT INTO tipos_usuario (nombre_tipo, descripcion) VALUES
('Natural', 'Cliente individual o persona natural.'),
('Jurídico', 'Cliente registrado como empresa, institución o persona jurídica.'),
('Residencial', 'Cliente de uso doméstico.'),
('Comercial', 'Cliente de uso comercial o negocio.'),
('Institucional', 'Cliente perteneciente a una institución pública o privada.');

INSERT INTO sectores (nombre_sector, descripcion) VALUES
('Sector Centro', 'Zona central del área de servicio.'),
('Sector Norte', 'Zona norte del área de servicio.'),
('Sector Sur', 'Zona sur del área de servicio.'),
('Sector Oriente', 'Zona oriental del área de servicio.'),
('Sector Poniente', 'Zona occidental del área de servicio.');

-- Contraseña temporal solo como ejemplo. En producción debe usarse password_hash desde PHP.
INSERT INTO operadores (nombre_completo, usuario, correo, password_hash, rol, telefono) VALUES
('Administrador HIDRA', 'admin', 'admin', '$2y$10$v/MwAJ7rhILZo3QMYuNnvev.sErAkh94yfyw5vBCHkEVnwi4PREzu', 'administrador', NULL);

INSERT INTO tarifas (id_tipo_usuario, nombre_tarifa, descripcion, precio_m3, cargo_fijo, consumo_minimo_m3, fecha_inicio) VALUES
(1, 'Tarifa Natural', 'Tarifa base para persona natural.', 0.5000, 2.00, 0.00, CURDATE()),
(2, 'Tarifa Jurídica', 'Tarifa base para persona jurídica.', 0.7500, 5.00, 0.00, CURDATE()),
(3, 'Tarifa Residencial', 'Tarifa para consumo doméstico.', 0.4500, 2.00, 0.00, CURDATE()),
(4, 'Tarifa Comercial', 'Tarifa para negocios y comercios.', 0.8000, 5.00, 0.00, CURDATE()),
(5, 'Tarifa Institucional', 'Tarifa para instituciones.', 0.7000, 4.00, 0.00, CURDATE());

INSERT INTO moras (nombre_mora, descripcion, tipo_mora, monto_fijo, porcentaje, dias_gracia, fecha_inicio) VALUES
('Mora fija mensual', 'Penalización fija aplicada después del vencimiento.', 'monto_fijo', 1.00, 0.00, 5, CURDATE()),
('Mora porcentual mensual', 'Penalización porcentual aplicada sobre el saldo pendiente.', 'porcentaje', 0.00, 5.00, 5, CURDATE());

INSERT INTO configuracion_sistema (
    nombre_empresa,
    nit_empresa,
    nrc_empresa,
    direccion_empresa,
    telefono_empresa,
    correo_empresa,
    dias_vencimiento_factura,
    moneda,
    simbolo_moneda
) VALUES (
    'HIDRA S.A. de C.V.',
    NULL,
    NULL,
    'Dirección pendiente de configurar',
    NULL,
    NULL,
    30,
    'USD',
    '$'
);

-- ==========================================================
-- VISTAS ÚTILES PARA CONSULTAS Y REPORTES
-- ==========================================================

CREATE VIEW vista_clientes_medidores AS
SELECT
    u.id_usuario,
    u.codigo_usuario,
    CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente,
    tu.nombre_tipo AS tipo_usuario,
    s.nombre_sector AS sector,
    m.id_medidor,
    m.numero_medidor,
    m.estado AS estado_medidor,
    u.estado AS estado_usuario
FROM usuarios u
INNER JOIN tipos_usuario tu ON u.id_tipo_usuario = tu.id_tipo_usuario
INNER JOIN sectores s ON u.id_sector = s.id_sector
LEFT JOIN medidores m ON u.id_usuario = m.id_usuario;

CREATE VIEW vista_facturas_pendientes AS
SELECT
    f.id_factura,
    f.numero_factura,
    u.codigo_usuario,
    CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente,
    f.mes,
    f.anio,
    f.fecha_emision,
    f.fecha_vencimiento,
    f.total,
    f.saldo_pendiente,
    f.estado
FROM facturas f
INNER JOIN usuarios u ON f.id_usuario = u.id_usuario
WHERE f.estado IN ('pendiente', 'vencida');

CREATE VIEW vista_pagos_realizados AS
SELECT
    p.id_pago,
    p.fecha_pago,
    p.monto_pagado,
    p.metodo_pago,
    f.numero_factura,
    u.codigo_usuario,
    CONCAT(u.nombres, ' ', IFNULL(u.apellidos, '')) AS cliente,
    o.nombre_completo AS operador
FROM pagos p
INNER JOIN facturas f ON p.id_factura = f.id_factura
INNER JOIN usuarios u ON f.id_usuario = u.id_usuario
LEFT JOIN operadores o ON p.id_operador_registra = o.id_operador
WHERE p.estado = 'registrado';

-- ==========================================================
-- FIN DEL SCRIPT
-- ==========================================================
