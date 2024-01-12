<?php
session_start();
require "../../config.php";
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$usuario_usu = isset($_POST["usuario_usu"]) ? htmlentities(utf8_decode($_POST["usuario_usu"])) : "";
$contrasena_usu = isset($_POST["contrasena_usu"]) ? htmlentities(utf8_decode($_POST["contrasena_usu"])) : "";
$nombre_usuario_sesion = '';
$id_usuario='';
$id_perfil='';
$nombre_per='';
$consulta = 
	"
	SELECT 
		usuario_usuario.nombre_usu,
		usuario_usuario.apellido1_usu,
		usuario_usuario.id_usu,
		usuario_usuario.id_per,
		usuario_perfil.nombre_per
	FROM 
		usuario_usuario,
		usuario_perfil
	WHERE 
		usuario_usuario.usuario_usu = ? AND
		usuario_usuario.contrasena_usu = ? AND
		usuario_usuario.id_est_usu = 1 AND
		usuario_usuario.id_per = usuario_perfil.id_per
	";
$conexion->consulta_form($consulta,array($usuario_usu,$contrasena_usu));
$cantidad = $conexion->total();
if($cantidad > 0){
	$fila_consulta = $conexion->extraer_registro();	
	foreach ($fila_consulta as $fila) {
		$id_usuario = $fila["id_usu"];
		$id_perfil = $fila["id_per"];
		$nombre_per = utf8_encode($fila["nombre_per"]);
		
		if ($id_perfil == 1) {
			$nombre_usuario_sesion = utf8_encode($fila["nombre_usu"]);
		}
    	else{
    		$nombre_usuario_sesion = utf8_encode($fila["nombre_usu"]." ".$fila["apellido1_usu"]);
    	}    	
	}
	$_SESSION["sesion_usuario_panel"] = $nombre_usuario_sesion;
	$_SESSION["sesion_id_panel"] = $id_usuario;
	
	$_SESSION["sesion_perfil_panel"] = $id_perfil;
	$_SESSION["sesion_nombre_perfil_panel"] = $nombre_per;

	if ($_SESSION["sesion_perfil_panel"]==4) {
		$consulta = "SELECT id_vend FROM vendedor_vendedor WHERE id_usu = ".$id_usuario."";
        $conexion->consulta($consulta);
        $fila = $conexion->extraer_registro_unico();
        $id_vend = $fila["id_vend"];
        $_SESSION["sesion_id_vend"] = $id_vend;
	}
	$jsondata['title'] = "Excelente!";
	$jsondata['message'] = "Usuario ingresado con éxito!";
	$jsondata['icon'] = "success";
	$jsondata['action'] = "panel.php";
	echo json_encode($jsondata);
	exit();
}else{
	$jsondata['title'] = "Atención!";
	$jsondata['message'] = "Usuario no reconocido o clave inválida";
	$jsondata['icon'] = "warning";
	$jsondata['action'] = "#";
	echo json_encode($jsondata);
	exit();
}
?>