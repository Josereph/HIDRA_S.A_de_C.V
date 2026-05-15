CREATE TABLE IF NOT EXISTS viviendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sector_id INT NOT NULL,
    cliente_id INT NULL,
    direccion VARCHAR(255) NOT NULL,
    lat DECIMAL(10,6) NULL,
    lng DECIMAL(10,6) NULL,
    estado ENUM('Activa', 'Suspendida', 'En revisión') NOT NULL DEFAULT 'En revisión',
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_viviendas_sector
        FOREIGN KEY (sector_id) REFERENCES sectores(id_sector)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_viviendas_cliente
        FOREIGN KEY (cliente_id) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_viviendas_sector ON viviendas(sector_id);
CREATE INDEX idx_viviendas_cliente ON viviendas(cliente_id);
CREATE INDEX idx_viviendas_estado ON viviendas(estado);

-- Datos opcionales de prueba. Ejecuta esto solo si quieres casas demo.
-- INSERT INTO viviendas (sector_id, cliente_id, direccion, estado) VALUES
-- (1, NULL, 'Barrio El Centro, casa #12', 'Activa'),
-- (1, NULL, 'Calle Principal, casa #8', 'En revisión'),
-- (2, NULL, 'Colonia El Norte, lote #5', 'Activa');
