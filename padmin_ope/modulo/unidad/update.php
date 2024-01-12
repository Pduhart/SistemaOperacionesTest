<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_unidad_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/unidad_clase.php");
$unidad = new unidad();
$id = isset($_POST["id"]) ? htmlentities(utf8_decode($_POST["id"])) : 0;
$nombre = isset($_POST["nombre"]) ? htmlentities(utf8_decode($_POST["nombre"])) : "";
$codigo = isset($_POST["codigo"]) ? htmlentities(utf8_decode($_POST["codigo"])) : "";


$unidad->unidad_crea($nombre,$codigo);

$unidad->unidad_update($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>