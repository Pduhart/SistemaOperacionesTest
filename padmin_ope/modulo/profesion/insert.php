<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_profesion_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/profesion_clase.php");
$profesion = new profesion();

$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";

$profesion->profesion_crea($nombre);

$profesion->profesion_insert();
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>