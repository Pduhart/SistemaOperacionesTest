<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_proyecto_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/proyecto_clase.php");
$proyecto = new proyecto();
$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$unidad = isset($_POST["unidad"]) ? utf8_decode($_POST["unidad"]) : 0;
$tipo_documento = isset($_POST["tipo_documento"]) ? utf8_decode($_POST["tipo_documento"]) : 0;
$bono = isset($_POST["bono"]) ? utf8_decode($_POST["bono"]) : 2;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$codigo = isset($_POST["codigo"]) ? utf8_decode($_POST["codigo"]) : "";
$comentario = isset($_POST["comentario"]) ? utf8_decode($_POST["comentario"]) : 2;
$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$nombre_cliente = isset($_POST["nombre_cliente"]) ? utf8_decode($_POST["nombre_cliente"]) : "";
$ciudad = isset($_POST["ciudad"]) ? utf8_decode($_POST["ciudad"]) : "";
$direccion = isset($_POST["direccion"]) ? utf8_decode($_POST["direccion"]) : "";
$giro = isset($_POST["giro"]) ? utf8_decode($_POST["giro"]) : "";

$proyecto->proyecto_crea($unidad,$tipo_documento,$bono,$comentario,1,$nombre,$codigo,$nombre_cliente,$direccion,$ciudad,$rut,$giro);

$proyecto->proyecto_update($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>