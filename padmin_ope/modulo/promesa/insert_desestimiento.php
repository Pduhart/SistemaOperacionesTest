<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_promesa_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/cotizacion_clase.php");
$cotizacion = new cotizacion();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : "0000-00-00 00:00:00";
$hora = date(" H:i:s");
$fecha = date("Y-m-d  H:i:s",strtotime($fecha." ".$hora));

$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";

$cotizacion->cotizacion_insert_desistimiento($id,$fecha,$descripcion);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>