<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}

if(!isset($_SESSION["modulo_usuario_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["usuario_usu"])){
	header("Location: ../../index.php");
	exit();
}
include("../class/usuario_clase.php");
$usuario = new usuario();

$id_usu = isset($_POST["usuario_usu"]) ? htmlentities(utf8_decode($_POST["usuario_usu"])) : 0;
$id_uni = isset($_POST["unidad_usu"]) ? htmlentities(utf8_decode($_POST["unidad_usu"])) : 0;
$id_pro = isset($_POST["proyecto_usu"]) ? htmlentities(utf8_decode($_POST["proyecto_usu"])) : 0;
$opcion = isset($_POST["seleccion_usu"]) ? htmlentities(utf8_decode($_POST["seleccion_usu"])) : 0;




if ($opcion == 1){
	$usuario->usuario_insert_proyecto($id_usu, $id_uni, $id_pro);
}
else{
	$usuario->usuario_delete_proyecto($id_usu, $id_uni, $id_pro);
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>