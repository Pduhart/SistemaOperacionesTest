<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_etapa_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/etapa_clase.php");
$etapa = new etapa();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;
$tipo = isset($_POST["tipo"]) ? utf8_decode($_POST["tipo"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";

$etapa->etapa_update_detalle($id,$tipo,$nombre);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>