<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_bodega_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/bodega_clase.php");
$bodega = new bodega();

$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";

$departamento = isset($_POST["departamento"]) ? utf8_decode($_POST["departamento"]) : 0;
if (empty($_POST["departamento"])) {
	$departamento = 0;
}

$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$valor = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : "";


$bodega->bodega_crea($condominio,$departamento,1,$nombre,$valor,$rol);
$bodega->bodega_insert();
$id_bodega = $bodega->recupera_id();


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>