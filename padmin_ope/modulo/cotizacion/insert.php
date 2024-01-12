<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
// if(!isset($_SESSION["modulo_cotizacion_panel"])){
//     header("Location: "._ADMIN."panel.php");
//     exit();
// }
// if(!isset($_POST["nombre"])){
// 	header("Location: "._ADMIN."index.php");
// 	exit();
// }


include("../class/cotizacion_clase.php");
$cotizacion = new cotizacion();


$propietario = isset($_POST["propietario"]) ? utf8_decode($_POST["propietario"]) : "";
$nocreapro = isset($_POST["no-crea-pro"]) ? $_POST["no-crea-pro"] : 0;


$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$segundo_nombre = isset($_POST["segundo_nombre"]) ? utf8_decode($_POST["segundo_nombre"]) : "";
$apellido_paterno = isset($_POST["apellido_paterno"]) ? utf8_decode($_POST["apellido_paterno"]) : "";
$apellido_materno = isset($_POST["apellido_materno"]) ? utf8_decode($_POST["apellido_materno"]) : "";
$profesion = isset($_POST["profesion"]) ? utf8_decode($_POST["profesion"]) : "";
$direccion_trabajo = isset($_POST["direccion_trabajo"]) ? utf8_decode($_POST["direccion_trabajo"]) : "";
$sexo = isset($_POST["sexo"]) ? utf8_decode($_POST["sexo"]) : "";
$region = isset($_POST["region"]) ? utf8_decode($_POST["region"]) : "";
$nacionalidad = isset($_POST["nacionalidad"]) ? utf8_decode($_POST["nacionalidad"]) : "";
$comuna = isset($_POST["comuna"]) ? utf8_decode($_POST["comuna"]) : "";
$correo = isset($_POST["correo"]) ? utf8_decode($_POST["correo"]) : "";
$interes = isset($_POST["interes"]) ? utf8_decode($_POST["interes"]) : "";
$porcentaje_credito = isset($_POST["porcentaje_credito"]) ? utf8_decode($_POST["porcentaje_credito"]) : "";
$porcentaje_credito = str_replace(',', '.', $porcentaje_credito);



$fono = isset($_POST["fono"]) ? utf8_decode($_POST["fono"]) : "";
$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$torre = isset($_POST["torre"]) ? utf8_decode($_POST["torre"]) : "";
$vivienda = isset($_POST["vivienda"]) ? utf8_decode($_POST["vivienda"]) : "";
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : "";
$canal = isset($_POST["canal"]) ? utf8_decode($_POST["canal"]) : "";
$fecha = isset($_POST["fecha_cot"]) ? utf8_decode($_POST["fecha_cot"]) : null;
$hora = date(" H:i:s");
$fecha = date("Y-m-d  H:i:s",strtotime($fecha." ".$hora));
$preaprobacion = isset($_POST["preaprobacion"]) ? utf8_decode($_POST["preaprobacion"]) : 0;
$renta = isset($_POST["renta"]) ? utf8_decode($_POST["renta"]) : 0;

// $fecha = date("Y-m-d H:i:s");
$vendedor = $_SESSION["sesion_id_panel"];

if($nocreapro <> 1) {
	$propietario = $cotizacion->cotizacion_update_propietario($rut,$propietario,$nombre,$segundo_nombre,$apellido_paterno,$apellido_materno,$correo,$fono,$profesion,$direccion_trabajo,$sexo,$region,$nacionalidad,$comuna); 
}

// crea el número cot
$numero_cot = $cotizacion->update_numero_cotizacion();



$cotizacion->cotizacion_crea($vivienda,$modelo,$vendedor,$propietario,$canal,1,$fecha,$preaprobacion,$interes,$renta,$porcentaje_credito,$numero_cot);
$cotizacion->cotizacion_insert();

$id_cotizacion = $cotizacion->recupera_id();


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>