
DROP DATABASE IF EXISTS aarondev_base;  
CREATE DATABASE aarondev_base;
USE aarondev_base;
DROP TABLE IF EXISTS libros;
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    fecha_publicacion DATE NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    genero VARCHAR(50) NOT NULL,
    estado VARCHAR(20) NOT NULL,
    imagen LONGBLOB
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO libros (titulo, autor, fecha_publicacion, precio, genero, estado) VALUES
('El Quijote', 'Miguel de Cervantes', '1605-01-16', 25.50, 'Novela', 'Nuevo'),
('Cien Años de Soledad', 'Gabriel García Márquez', '1967-05-30', 18.90, 'Novela', 'Nuevo'),
('1984', 'George Orwell', '1949-06-08', 15.99, 'Ciencia Ficción', 'Usado'),
('El Hobbit', 'J.R.R. Tolkien', '1937-09-21', 22.00, 'Fantasía', 'Nuevo'),
('Drácula', 'Bram Stoker', '1897-05-26', 12.50, 'Terror', 'Usado');

DELIMITER //

DROP PROCEDURE IF EXISTS ConsultarLibro //
CREATE PROCEDURE ConsultarLibro(IN libro_id INT)
BEGIN
    SELECT * FROM libros WHERE id = libro_id;
END //

DROP FUNCTION IF EXISTS BorrarLibro //
CREATE FUNCTION BorrarLibro(libro_id INT) RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE v_count INT;
    SELECT COUNT(*) INTO v_count FROM libros WHERE id = libro_id;
    
    IF v_count > 0 THEN
        DELETE FROM libros WHERE id = libro_id;
        RETURN 1;
    ELSE
        RETURN 0;
    END IF;
END //

DELIMITER ;