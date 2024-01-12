<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_profesion_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/profesion_clase.php");
$profesion = new profesion();
$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";


$profesion->profesion_crea($nombre);

$profesion->profesion_update($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>