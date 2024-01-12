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
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/venta_clase.php");
$venta = new venta();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";

$venta->venta_insert_protestar($id,$descripcion);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>