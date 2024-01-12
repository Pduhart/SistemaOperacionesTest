<?php 
session_start(); 
require "../../config.php"; 

include _INCLUDE."class/conexion.php";
$conexion = new conexion();


$consulta1 = 
    "
    SELECT
        id_vend,
        rut_pro
    FROM
        vendedor_rutpropietario_vendedor
    ";
$conexion->consulta($consulta1);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
    	$vendedor = $fila['id_vend'];
    	$rut_pro = utf8_encode($fila['rut_pro']);

    	$rut_pro = trim($rut_pro,chr(0xC2).chr(0xA0));

    	$consulta_idpro = 
	        "
	        SELECT
	            id_pro
	        FROM
	            propietario_propietario
	        WHERE
	            rut_pro = '".$rut_pro."'
	        ";
	    $conexion->consulta($consulta_idpro);
	    $filaid = $conexion->extraer_registro_unico();
	    $cantidadid = $conexion->total();
	    $cliente = $filaid['id_pro'];

	    echo "RUT: ".$rut_pro." - ID Cliente:".$cliente." - ID Vend:".$vendedor."<br>";

	    if ($cliente<>'') {
	    	// $consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
			// $conexion->consulta_form($consulta,array(0,$vendedor,$cliente));
	    }
    	
    }
}



?>

<?php 

$array_vends = [10,13,14,15,17];

$consulta1 = 
    "
    SELECT
        id_pro
    FROM
        propietario_propietario
    WHERE NOT EXISTS (
    	SELECT id_pro
        FROM vendedor_propietario_vendedor
        WHERE propietario_propietario.id_pro = vendedor_propietario_vendedor.id_pro
    )
    ";
$conexion->consulta($consulta1);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
$contador_vends = 0;
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
    	if($contador_vends>4){
    		$contador_vends = 0;
    	}
    	$id_pro = $fila['id_pro'];
    	echo $id_pro." --> ".$array_vends[$contador_vends]."<br>";
    	if ($id_pro<>'') {
	    	// $consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
			// $conexion->consulta_form($consulta,array(0,$array_vends[$contador_vends],$id_pro));
	    }
    	$contador_vends++;
    }
}



 ?>