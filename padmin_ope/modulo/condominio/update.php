<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_condominio_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/condominio_clase.php");
$condominio = new condominio();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$alias = isset($_POST["alias"]) ? utf8_decode($_POST["alias"]) : "";
$fecha_venta = isset($_POST["fecha_venta"]) ? utf8_decode($_POST["fecha_venta"]) : "0000-00-00";
$fecha_venta = date("Y-m-d",strtotime($fecha_venta));
$condominio->condominio_crea(1,$nombre,$alias,$fecha_venta);

$condominio->condominio_update($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>