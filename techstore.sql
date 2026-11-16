CREATE DATABASE IF NOT EXISTS techstore
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techstore;

CREATE TABLE IF NOT EXISTS categorias (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS productos (
  id_producto INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(12,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  imagen VARCHAR(255) DEFAULT '💻',
  id_categoria INT NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria)
    REFERENCES categorias(id_categoria),
  CHECK (precio >= 0),
  CHECK (stock >= 0)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS clientes (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pedidos (
  id_pedido INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT NOT NULL,
  fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(12,2) NOT NULL,
  estado ENUM('Pendiente','Preparando','Enviado','Entregado','Cancelado') DEFAULT 'Pendiente',
  CONSTRAINT fk_pedido_cliente FOREIGN KEY (id_cliente)
    REFERENCES clientes(id_cliente)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS detalle_pedido (
  id_detalle INT AUTO_INCREMENT PRIMARY KEY,
  id_pedido INT NOT NULL,
  id_producto INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(12,2) NOT NULL,
  CONSTRAINT fk_detalle_pedido FOREIGN KEY (id_pedido)
    REFERENCES pedidos(id_pedido),
  CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto),
  CHECK (cantidad > 0)
) ENGINE=InnoDB;

INSERT IGNORE INTO categorias (nombre) VALUES
('Computadoras'), ('Procesadores'), ('Placas de video'), ('Monitores'),
('Periféricos'), ('Almacenamiento'), ('Memorias RAM');

INSERT INTO productos (nombre, descripcion, precio, stock, imagen, id_categoria)
SELECT 'PC Gamer Ryzen 5', 'Equipo para gaming y tareas exigentes.', 850000, 5, '🖥️', id_categoria FROM categorias WHERE nombre='Computadoras'
UNION ALL
SELECT 'Procesador Ryzen 5 5600', 'Procesador de 6 núcleos para PC de escritorio.', 185000, 12, '🧠', id_categoria FROM categorias WHERE nombre='Procesadores'
UNION ALL
SELECT 'Placa de video RTX 4060', 'Placa gráfica para juegos y creación de contenido.', 520000, 4, '🎮', id_categoria FROM categorias WHERE nombre='Placas de video'
UNION ALL
SELECT 'Monitor 24 pulgadas', 'Monitor Full HD de 24 pulgadas.', 210000, 8, '🖥️', id_categoria FROM categorias WHERE nombre='Monitores'
UNION ALL
SELECT 'Teclado mecánico RGB', 'Teclado mecánico con iluminación RGB.', 65000, 20, '⌨️', id_categoria FROM categorias WHERE nombre='Periféricos'
UNION ALL
SELECT 'Mouse gamer', 'Mouse óptico con varios niveles de DPI.', 28000, 25, '🖱️', id_categoria FROM categorias WHERE nombre='Periféricos'
UNION ALL
SELECT 'SSD 1 TB', 'Unidad de estado sólido de 1 TB.', 95000, 15, '💾', id_categoria FROM categorias WHERE nombre='Almacenamiento'
UNION ALL
SELECT 'Memoria RAM 16 GB', 'Módulo de memoria DDR4 de 16 GB.', 48000, 18, '🔧', id_categoria FROM categorias WHERE nombre='Memorias RAM';
