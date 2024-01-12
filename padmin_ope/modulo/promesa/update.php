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
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/cotizacion_clase.php");
$cotizacion = new cotizacion();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$propietario = isset($_POST["propietario"]) ? utf8_decode($_POST["propietario"]) : "";
$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$apellido_paterno = isset($_POST["apellido_paterno"]) ? utf8_decode($_POST["apellido_paterno"]) : "";
$apellido_materno = isset($_POST["apellido_materno"]) ? utf8_decode($_POST["apellido_materno"]) : "";
$correo = isset($_POST["correo"]) ? utf8_decode($_POST["correo"]) : "";
$fono = isset($_POST["fono"]) ? utf8_decode($_POST["fono"]) : "";
$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$torre = isset($_POST["torre"]) ? utf8_decode($_POST["torre"]) : "";
$vivienda = isset($_POST["vivienda"]) ? utf8_decode($_POST["vivienda"]) : "";
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : "";
$canal = isset($_POST["canal"]) ? utf8_decode($_POST["canal"]) : "";
/*$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : "0000-00-00 00:00:00";
$hora = date(" H:i:s");
$fecha = date("Y-m-d  H:i:s",strtotime($fecha." ".$hora));*/
$fecha = date("Y-m-d H:i:s");
$vendedor = $_SESSION["sesion_id_panel"];

$cotizacion->cotizacion_crea($vivienda,$modelo,$vendedor,$propietario,$canal,1,$fecha);
$cotizacion->cotizacion_update($id);

$cotizacion->cotizacion_update_propietario($propietario,$nombre,$apellido_paterno,$apellido_materno,$correo,$fono);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>