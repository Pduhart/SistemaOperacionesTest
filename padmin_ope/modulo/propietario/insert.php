<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_propietario_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/propietario_clase.php");
$propietario = new propietario();

$nacionalidad = isset($_POST["nacionalidad"]) ? utf8_decode($_POST["nacionalidad"]) : "";
$region = isset($_POST["region"]) ? utf8_decode($_POST["region"]) : "";
$comuna = isset($_POST["comuna"]) ? utf8_decode($_POST["comuna"]) : "";
$sexo = isset($_POST["sexo"]) ? utf8_decode($_POST["sexo"]) : "";
$civil = isset($_POST["civil"]) ? utf8_decode($_POST["civil"]) : "";
$estudio = isset($_POST["estudio"]) ? utf8_decode($_POST["estudio"]) : "";
$profesion = isset($_POST["profesion"]) ? utf8_decode($_POST["profesion"]) : "";
$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$nombre2 = isset($_POST["nombre2"]) ? utf8_decode($_POST["nombre2"]) : "";
$profesion_promesa = isset($_POST["profesion_promesa"]) ? utf8_decode($_POST["profesion_promesa"]) : "";
$nombre_cuenta = isset($_POST["nombre_cuenta"]) ? utf8_decode($_POST["nombre_cuenta"]) : "";
$apellido_paterno = isset($_POST["apellido_paterno"]) ? utf8_decode($_POST["apellido_paterno"]) : "";
$apellido_materno = isset($_POST["apellido_materno"]) ? utf8_decode($_POST["apellido_materno"]) : "";
$fono = isset($_POST["fono"]) ? utf8_decode($_POST["fono"]) : 0;
$fono2 = isset($_POST["fono2"]) ? utf8_decode($_POST["fono2"]) : 0;
$correo = isset($_POST["correo"]) ? utf8_decode($_POST["correo"]) : "";
$correo2 = isset($_POST["correo2"]) ? utf8_decode($_POST["correo2"]) : "";
$direccion = isset($_POST["direccion"]) ? utf8_decode($_POST["direccion"]) : "";
$direccion_trabajo = isset($_POST["direccion_trabajo"]) ? utf8_decode($_POST["direccion_trabajo"]) : "";
$fecha_nacimiento = isset($_POST["fecha_nacimiento"]) ? utf8_decode($_POST["fecha_nacimiento"]) : "";
$fecha_nacimiento = date("Y-m-d",strtotime($fecha_nacimiento));


$propietario->propietario_crea($nacionalidad,$region,$sexo,$civil,$estudio,$profesion,1,$rut,'',$nombre,$nombre2,$apellido_paterno,$apellido_materno,$fono,$fono2,$correo,$correo2,$direccion,$direccion_trabajo,$fecha_nacimiento,$comuna,$profesion_promesa);

$propietario->propietario_insert();


// recupera y crea la sesión

$nuevo_cliente = $propietario->recupera_id();

$_SESSION["id_cliente_filtro_ficha_panel"] = $nuevo_cliente;

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>