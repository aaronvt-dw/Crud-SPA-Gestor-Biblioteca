<?php
require('../ficheros/conexion.php');
$accion = $_POST['accion'] ?? '';
$id = $_POST['id'] ?? 0;

if ($accion === 'consultar') {
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
        $data_libro["fecha_publicacion"] = ""; // El procedimiento no devolvía la fecha originalmente, pero lo pongo que si no,no va
        $data_libro["estado"] = $registro["estado"];
        $data_libro["precio"] = $registro["precio"];
        
        $imagen = 'ficheros/pila-de-libros.png';
        if (!empty($registro['imagen'])) {
            $imagen = 'data:image/jpeg;base64,' . base64_encode($registro['imagen']);
        }
        $data_libro["imagen"] = $imagen;
        
      
        header("Content-type:application/json; charset=utf-8");
        echo json_encode($data_libro);
    } else {
        echo 0;
    }
} elseif ($accion === 'baja') {
    
    $consulta = "SELECT BorrarLibro($id) AS resultado";
    $resultado = @mysqli_query($conexion, $consulta);
    
    if (isset($resultado) && $resultado !== false) {
        $registro = mysqli_fetch_array($resultado);
        if ($registro["resultado"] == 1) {
            echo 1;
        } else {
            echo 0; 
        }
    } else {
        echo 0;
    }
}
mysqli_close($conexion);
?>
