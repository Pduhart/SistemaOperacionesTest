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
if(!isset($_POST["interes"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/cotizacion_clase.php");
$cotizacion = new cotizacion();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$interes = isset($_POST["interes"]) ? utf8_decode($_POST["interes"]) : "";
$medio = isset($_POST["medio"]) ? utf8_decode($_POST["medio"]) : "";
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$fecha = date("Y-m-d H:i:s");
$preaprobacion = isset($_POST["preaprobacion"]) ? utf8_decode($_POST["preaprobacion"]) : 0;

$nacionalidad = isset($_POST["nacionalidad"]) ? utf8_decode($_POST["nacionalidad"]) : "";
$region = isset($_POST["region"]) ? utf8_decode($_POST["region"]) : "";
$comuna = isset($_POST["comuna"]) ? utf8_decode($_POST["comuna"]) : "";
$profesion = isset($_POST["profesion"]) ? utf8_decode($_POST["profesion"]) : "";
$direccion_trabajo = isset($_POST["direccion_trabajo"]) ? utf8_decode($_POST["direccion_trabajo"]) : "";
$segundo_nombre = isset($_POST["segundo_nombre"]) ? utf8_decode($_POST["segundo_nombre"]) : "";
$apellido_materno = isset($_POST["apellido_materno"]) ? utf8_decode($_POST["apellido_materno"]) : "";
$id_pro = isset($_POST["id_pro"]) ? utf8_decode($_POST["id_pro"]) : "";

$cotizacion->cotizacion_insert_seguimiento($id,$interes,$medio,$descripcion,$fecha,$preaprobacion,$segundo_nombre,$apellido_materno,$nacionalidad,$region,$comuna,$profesion,$direccion_trabajo,$id_pro);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>