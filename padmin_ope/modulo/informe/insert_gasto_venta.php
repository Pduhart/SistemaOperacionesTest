<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$id_ven = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$monto_gas = isset($_POST["monto_gas"]) ? utf8_decode($_POST["monto_gas"]) : 0;
$fecha_gas = isset($_POST["fecha_gas"]) ? utf8_decode($_POST["fecha_gas"]) : null;
$insert = isset($_POST["insert"]) ? utf8_decode($_POST["insert"]) : 0;


if ($fecha_gas<>null) {
	$fecha_gas = date("Y-m-d",strtotime($fecha_gas));
}

if ($insert==0) {
	$consulta = "INSERT INTO venta_gastosop_venta VALUES(?,?,?,?)";
	$conexion->consulta_form($consulta,array(0,$id_ven,$fecha_gas,$monto_gas));
} else {
	$consulta = "UPDATE venta_gastosop_venta SET fecha_gas_ven = ?, monto_gas_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($fecha_gas,$monto_gas,$id_ven));
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>