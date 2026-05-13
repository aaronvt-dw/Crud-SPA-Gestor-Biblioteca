<?php

// incluimos el fichero de conexión
require('../ficheros/conexion.php');
/** @var mysqli $conexion */ // para que el editor no me de error al escribir $conexion

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = addslashes($_POST['titulo']);
    $autor = addslashes($_POST['autor']);
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $precio = $_POST['precio'];
    $genero = addslashes($_POST['genero']);
    $estado = addslashes($_POST['estado']);
    
    $v6 = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $la_foto = file_get_contents($_FILES['imagen']['tmp_name']);
        // esto es obligatorio -> modifica caracteres de control
        $v6 = addslashes($la_foto);
    }

    $consulta = "INSERT INTO libros (id, titulo, autor, fecha_publicacion, precio, genero, estado, imagen) 
                 VALUES ($id, '$titulo', '$autor', '$fecha_publicacion', $precio, '$genero', '$estado', '$v6')";

    // ejecutamos la consulta SQL
    // IMPORTANTE: Ponemos @ para que si falla (por ejemplo por clave duplicada) no escupa el error de PHP en pantalla y nos rompa el AJAX
    $resultado = @mysqli_query($conexion, $consulta);

    if ($resultado) {
        echo 1; // Éxito
    } else {
        // Obtenemos el error internamente para saber si es un duplicado
        $error = mysqli_error($conexion);
        if (strpos($error, 'Duplicate entry') !== false) {
            echo 2; // Código reservado para indicar "duplicado"
        } else {
            echo 0; // Error general
        }
    }
}

// cerramos la conexion - OBLIGATORIO
mysqli_close($conexion);
?>