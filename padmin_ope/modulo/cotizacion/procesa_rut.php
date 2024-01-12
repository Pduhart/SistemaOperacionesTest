<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$rut = $_POST["valor"];

$consulta = 
	"
	SELECT 
		id_pro
	FROM
		propietario_propietario
	WHERE 
		rut_pro = ?
	";
$conexion->consulta_form($consulta,array($rut));
$total_consulta = $conexion->total();

if($total_consulta == 0){ //no existe
	$jsondata['envio'] = 5;
	echo json_encode($jsondata);
	exit();
}
else{
	$fila = $conexion->extraer_registro_unico();
	$id_pro = $fila['id_pro'];
	$consulta = 
		"
		SELECT 
			id_vend
		FROM
			vendedor_propietario_vendedor
		WHERE 
			id_pro = ?
		";
	$conexion->consulta_form($consulta,array($id_pro));
	$total_consulta2 = $conexion->total();

	if($total_consulta2 > 0){ //existe asignado
		$fila = $conexion->extraer_registro_unico();
		if($_SESSION["sesion_perfil_panel"]==4){
			if($fila['id_vend'] != $_SESSION["sesion_id_panel"]){
				// ver quién es
				$consulta = 
					"
					SELECT 
						nombre_vend,
						apellido_paterno_vend
					FROM
						vendedor_vendedor
					WHERE 
						id_vend = ?
					";
				$conexion->consulta_form($consulta,array($fila['id_vend']));
				$fila2 = $conexion->extraer_registro_unico();
	        	$nombre_vend = utf8_encode($fila2['nombre_vend']." ".$fila2['apellido_paterno_vend']);
				$jsondata['envio'] = 7;
				$jsondata['name'] = $nombre_vend;
				echo json_encode($jsondata);
				exit();
			} else {
				$jsondata['envio'] = 8;
				echo json_encode($jsondata);
				exit();
			}
		} else {
			$consulta = 
				"
				SELECT 
					nombre_vend,
					apellido_paterno_vend
				FROM
					vendedor_vendedor
				WHERE 
					id_vend = ?
				";
			$conexion->consulta_form($consulta,array($fila['id_vend']));
			$fila2 = $conexion->extraer_registro_unico();
        	$nombre_vend = utf8_encode($fila2['nombre_vend']." ".$fila2['apellido_paterno_vend']);
			$jsondata['envio'] = 7;
			$jsondata['name'] = $nombre_vend;
			echo json_encode($jsondata);
			exit();
		}
		
	}
	else{ //existe no asignado
		$jsondata['envio'] = 6;
		echo json_encode($jsondata);
		exit();
	}
}
?>
