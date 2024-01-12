<?php 
session_start(); 
require "../../config.php"; 

include _INCLUDE."class/conexion.php";
$conexion = new conexion();


$array_vends = [19,20];

$consulta1 = 
    "
    SELECT
        id_pro
    FROM
        vendedor_propietario_vendedor
    WHERE
    	id_vend = 18
    ";
$conexion->consulta($consulta1);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
$contador_vends = 0;
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
    	if($contador_vends>1){
    		$contador_vends = 0;
    	}
    	$id_pro = $fila['id_pro'];
    	echo $id_pro." --> ".$array_vends[$contador_vends]."<br>";
    	if ($id_pro<>'') {
	    	$consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
			$conexion->consulta_form($consulta,array(0,$array_vends[$contador_vends],$id_pro));

			$consulta="DELETE FROM vendedor_propietario_vendedor WHERE id_vend = 18 AND id_pro = ".$id_pro."";
			$conexion->consulta($consulta);

	    }
    	$contador_vends++;
    }
}



 ?>