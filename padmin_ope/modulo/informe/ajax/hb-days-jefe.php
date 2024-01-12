<?php
session_start();
date_default_timezone_set('America/Santiago');
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
		(id_cat_eve = 1 or id_cat_eve = 2) AND
		MONTH(fecha_eve) = ".$mes."
	";

	if($_SESSION["sesion_filtro_vendedor_panel"]<>''){
		$consulta .= " AND id_usu = ".$_SESSION["sesion_filtro_vendedor_panel"]."";
	}

	$consulta .= " ORDER BY
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