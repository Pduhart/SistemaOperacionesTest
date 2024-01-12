<?php
session_start();
date_default_timezone_set('America/Santiago');
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_evento_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}

include("../class/evento_clase.php");
$evento = new evento();

$categoria = isset($_POST["categoria"]) ? utf8_decode($_POST["categoria"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : "00-00-0000";
$time = isset($_POST["time"]) ? utf8_decode($_POST["time"]) : "00:00:00";

$fecha = date("Y-m-d",strtotime($fecha));

$evento->evento_crea($categoria,1,$nombre,$fecha,$descripcion,$time,$_SESSION["sesion_id_panel"]);

$evento->evento_insert();
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>