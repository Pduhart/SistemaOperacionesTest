<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_banco_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/banco_clase.php");
$banco = new banco();

$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$convenio = isset($_POST["convenio"]) ? utf8_decode($_POST["convenio"]) : "";

$banco->banco_crea($nombre,$convenio);

$banco->banco_insert();
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>