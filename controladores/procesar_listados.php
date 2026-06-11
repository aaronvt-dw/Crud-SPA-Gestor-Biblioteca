<?php
require('../ficheros/conexion.php');
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

$resultado = mysqli_query($conexion, $consulta);

$data_libro = array();
$data_libros = array();

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
    
    $registro = mysqli_fetch_array($resultado);
    while ($registro)
    {
       
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

       
        $data_libros[] = $data_libro;

        
        $registro = mysqli_fetch_array($resultado);
    }
    header("Content-type:application/json; charset=utf-8");
    
    echo json_encode($data_libros);
}
else
{
    echo 0;
}
mysqli_close($conexion);
?>
