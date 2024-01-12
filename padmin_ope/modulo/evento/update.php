<?php
session_start();
date_default_timezone_set('America/Santiago');
require "../../config.php";

if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_evento_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/evento_clase.php");
$evento = new evento();
$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;

$time = isset($_POST["time"]) ? utf8_decode($_POST["time"]) : "00:00:00";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : "0000-00-00";

$fecha = date("Y-m-d",strtotime($fecha));

$evento->evento_crea(1,1,$nombre,$fecha,$descripcion,$time,$_SESSION["sesion_id_panel"]);

$evento->evento_update($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>