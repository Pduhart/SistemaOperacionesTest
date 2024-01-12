<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_descuento_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/descuento_clase.php");
$descuento = new descuento();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$valor = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : "";

$descuento->descuento_crea($condominio,1,$nombre,$valor);
$descuento->descuento_update($id);


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>