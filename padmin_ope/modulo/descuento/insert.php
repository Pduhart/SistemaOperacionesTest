<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_descuento_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/descuento_clase.php");
$descuento = new descuento();

$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$valor = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : "";


$descuento->descuento_crea($condominio,1,$nombre,$valor);
$descuento->descuento_insert();
$id_descuento = $descuento->recupera_id();


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>