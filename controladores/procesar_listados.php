<?php
// incluimos el fichero de conexión
require('../ficheros/conexion.php');
/** @var mysqli $conexion */ // para que el editor no me de error al escribir $conexion

$filtro_genero = $_GET['filtro_genero'] ?? '';
$filtro_estado = $_GET['filtro_estado'] ?? '';

$consulta = "SELECT id, titulo, autor, genero, fecha_publicacion, estado, precio, imagen FROM libros WHERE 1=1";

if ($filtro_genero != "") {
    $consulta .= " AND genero = '" . $filtro_genero . "'";
}

if ($filtro_estado != "") {
    $consulta .= " AND estado = '" . $filtro_estado . "'";
}

$consulta .= " ORDER BY id ASC";

// ejecutamos la consulta SQL
$resultado = mysqli_query($conexion, $consulta);

$data_libro = array();
$data_libros = array();

// obtengo el nº de registros devueltos
if (isset($resultado) && $resultado !== false)
{
	$nregistros = mysqli_num_rows($resultado);
}
else
{
	$nregistros = 0;
}

if ($nregistros > 0)
{
    // me posiciono sobre el primer registro
    $registro = mysqli_fetch_array($resultado);
    while ($registro)
    {
        // borramos lo que hubiese antes
        $data_libro = array();
        
        $data_libro["id"] = $registro["id"];
        $data_libro["titulo"] = $registro["titulo"];
        $data_libro["autor"] = $registro["autor"];
        $data_libro["genero"] = $registro["genero"];
        $data_libro["fecha_publicacion"] = $registro["fecha_publicacion"];
        $data_libro["estado"] = $registro["estado"];
        $data_libro["precio"] = $registro["precio"];
        
        $imagen = 'ficheros/pila-de-libros.png';
        if (!empty($registro['imagen'])) {
            $imagen = 'data:image/jpeg;base64,' . base64_encode($registro['imagen']);
        }
        $data_libro["imagen"] = $imagen;

        // meto el registro en el array donde vamos almacenando todos
        $data_libros[] = $data_libro;

        // este fetch_array me mueve el puntero al siguiente
        $registro = mysqli_fetch_array($resultado);
    }
    
    // configuramos cabecera
    header("Content-type:application/json; charset=utf-8"); 
    // devolvemos los datos en formato json
    echo json_encode($data_libros);		
}
else
{
    echo 0;
}

// cerramos la conexion - OBLIGATORIO
mysqli_close($conexion);
?>


    