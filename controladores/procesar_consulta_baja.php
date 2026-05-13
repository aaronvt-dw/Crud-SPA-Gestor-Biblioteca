<?php
// incluimos el fichero de conexión
require('../ficheros/conexion.php');
/** @var mysqli $conexion */ // para que el editor no me de error al escribir $conexion

$accion = $_POST['accion'] ?? '';
$id = $_POST['id'] ?? 0;

if ($accion === 'consultar') {
    // llamamos al procedimiento almacenado
    $consulta = "CALL ConsultarLibro($id)";
    $resultado = @mysqli_query($conexion, $consulta);
    
    $data_libro = array();
    
    if (isset($resultado) && $resultado !== false) {
        $nregistros = mysqli_num_rows($resultado);
    } else {
        $nregistros = 0;
    }
    
    if ($nregistros > 0) {
        $registro = mysqli_fetch_array($resultado);
        
        $data_libro["id"] = $registro["id"];
        $data_libro["titulo"] = $registro["titulo"];
        $data_libro["autor"] = $registro["autor"];
        $data_libro["genero"] = $registro["genero"];
        $data_libro["fecha_publicacion"] = ""; // El procedimiento no devolvía la fecha originalmente, pero lo ponemos por compatibilidad
        $data_libro["estado"] = $registro["estado"];
        $data_libro["precio"] = $registro["precio"];
        
        $imagen = 'ficheros/pila-de-libros.png';
        if (!empty($registro['imagen'])) {
            $imagen = 'data:image/jpeg;base64,' . base64_encode($registro['imagen']);
        }
        $data_libro["imagen"] = $imagen;
        
        // configuramos cabecera y devolvemos JSON
        header("Content-type:application/json; charset=utf-8"); 
        echo json_encode($data_libro);
    } else {
        echo 0;
    }
} elseif ($accion === 'baja') {
    // usamos la función almacenada
    $consulta = "SELECT BorrarLibro($id) AS resultado";
    $resultado = @mysqli_query($conexion, $consulta);
    
    if (isset($resultado) && $resultado !== false) {
        $registro = mysqli_fetch_array($resultado);
        if ($registro["resultado"] == 1) {
            echo 1; // Éxito borrando
        } else {
            echo 0; // No se borró nada (no existe)
        }
    } else {
        echo 0;
    }
}

// cerramos la conexion - OBLIGATORIO
mysqli_close($conexion);
?>
