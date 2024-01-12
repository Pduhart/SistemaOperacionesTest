<?php
session_start();
require "../../../config.php";
include '../../../class/conexion.php';
$conexion = new conexion(); 
$mes = $_POST['currentMonth'];
$consulta = 
	"
	SELECT 
		fecha_eve
	FROM 
		evento_evento
	WHERE
		id_cat_eve = 1 AND
		id_usu = ".$_SESSION["sesion_id_panel"]." AND
		MONTH(fecha_eve) = ".$mes."
	ORDER BY
		id_eve DESC
	";
	$conexion->consulta($consulta);
    $cantidad = $conexion->total();
	$conta = 0;
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
			$fecha_eve = $fila["fecha_eve"];
			$dia_fecha_eve = date("d",strtotime($fecha_eve));
			if($conta<>$cantidad){
				echo $dia_fecha_eve.",";
				$conta++;	
			} else{
				echo $dia_fecha_eve;	
			}
		}
	}
?>