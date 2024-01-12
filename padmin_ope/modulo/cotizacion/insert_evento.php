<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_cotizacion_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["fecha"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/cotizacion_clase.php");
$cotizacion = new cotizacion();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;
$id_pro = isset($_POST["id_pro"]) ? utf8_encode($_POST["id_pro"]) : 0;
$categoria = isset($_POST["categoria"]) ? utf8_encode($_POST["categoria"]) : 0;

$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : "00-00-0000";

$fecha = date("Y-m-d",strtotime($fecha));

$time = isset($_POST["time"]) ? utf8_decode($_POST["time"]) : "00:00:00";
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";


$cotizacion->cotizacion_insert_evento($id,$id_pro,$categoria,$fecha,$time,$descripcion,$nombre,$_SESSION["sesion_id_panel"]);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>