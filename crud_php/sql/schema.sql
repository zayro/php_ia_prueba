CREATE DATABASE IF NOT EXISTS crud_productos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crud_productos;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    categoria VARCHAR(50) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO productos (nombre, descripcion, precio, stock, categoria, estado) VALUES
('Laptop HP 15"', 'Laptop Intel Core i5, 8GB RAM, 512GB SSD', 15999, 25, 'Electrónica', 'activo'),
('Mouse Logitech', 'Mouse inalámbrico ergonómico', 299, 150, 'Accesorios', 'activo'),
('Teclado Mecánico', 'Teclado RGB switches azules', 899, 75, 'Accesorios', 'activo'),
('Monitor Dell 24"', 'Monitor LED Full HD', 4599, 30, 'Electrónica', 'activo'),
('Silla Ergonómica', 'Silla oficina soporte lumbar', 2899, 40, 'Muebles', 'activo'),
('Auriculares Sony', 'Inalámbricos cancelación ruido', 1299, 60, 'Accesorios', 'activo'),
('Disco Duro 1TB', 'Disco externo USB 3.0', 899, 100, 'Almacenamiento', 'activo'),
('Webcam HD', 'Cámara 1080p micrófono', 599, 85, 'Accesorios', 'activo'),
('Cable HDMI', 'Cable HDMI 2.1 2 metros', 149, 200, 'Accesorios', 'activo'),
('Hub USB 7', 'Hub USB 3.0 siete puertos', 349, 90, 'Accesorios', 'activo');
