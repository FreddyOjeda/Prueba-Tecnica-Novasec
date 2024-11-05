CREATE TABLE sede (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

ALTER TABLE hallazgo ADD COLUMN sede_id INT;
ALTER TABLE hallazgo 
ADD FOREIGN KEY (sede_id) REFERENCES sede(id);
INSERT INTO sede (nombre) VALUES 
('Sede Central'),
('Planta de Producción A'),
('Planta de Producción B'),
('Centro de Distribución Norte'),
('Centro de Distribución Sur'),
('Oficina Regional Bogotá'),
('Oficina Regional Medellín'),
('Oficina Regional Cali'),
('Centro de Investigación y Desarrollo'),
('Laboratorio de Calidad'),
('Sucursal Norte'),
('Sucursal Sur'),
('Almacén Principal');
