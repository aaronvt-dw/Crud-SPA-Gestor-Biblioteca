<?php
require('../ficheros/conexion.php');
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
       
        $v6 = addslashes($la_foto);
    }

    $consulta = "INSERT INTO libros (id, titulo, autor, fecha_publicacion, precio, genero, estado, imagen) 
                 VALUES ($id, '$titulo', '$autor', '$fecha_publicacion', $precio, '$genero', '$estado', '$v6')";


    $resultado = @mysqli_query($conexion, $consulta);

    if ($resultado) {
        echo 1;
    } else {
       
        $error = mysqli_error($conexion);
        if (strpos($error, 'Duplicate entry') !== false) {
            echo 2;//id duplicado
        } else {
            echo 0;
        }
    }
}
mysqli_close($conexion);
?>