<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_estacionamiento_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/estacionamiento_clase.php");
$estacionamiento = new estacionamiento();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;


$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$departamento = isset($_POST["departamento"]) ? utf8_decode($_POST["departamento"]) : 0;
if (empty($_POST["departamento"])) {
	$departamento = 0;
}

$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$valor = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : "";


$estacionamiento->estacionamiento_crea($condominio,$departamento,1,$nombre,$valor);
$estacionamiento->estacionamiento_update($id);


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>