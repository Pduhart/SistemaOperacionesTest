<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_venta_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["interes"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/venta_clase.php");
$venta = new venta();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$interes = isset($_POST["interes"]) ? utf8_decode($_POST["interes"]) : "";
$medio = isset($_POST["medio"]) ? utf8_decode($_POST["medio"]) : "";
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$fecha = date("Y-m-d H:i:s");

$cotizacion->cotizacion_insert_seguimiento($id,$interes,$medio,$descripcion,$fecha);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>