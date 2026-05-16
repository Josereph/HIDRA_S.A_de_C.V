CREATE TABLE IF NOT EXISTS viviendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sector_id INT NOT NULL,
    cliente_id INT NULL,
    direccion VARCHAR(255) NOT NULL,
    lat DECIMAL(10,6) NULL,
    lng DECIMAL(10,6) NULL,
    estado ENUM('Activa', 'Suspendida', 'En revisión') DEFAULT 'En revisión',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sector_id) REFERENCES sectores(id_sector) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);
