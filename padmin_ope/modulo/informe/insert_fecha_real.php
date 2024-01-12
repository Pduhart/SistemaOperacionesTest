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
$fecha_cli = isset($_POST["fecha_cli"]) ? utf8_decode($_POST["fecha_cli"]) : null;
$fecha_adm = isset($_POST["fecha_adm"]) ? utf8_decode($_POST["fecha_adm"]) : null;
$monto_cli = isset($_POST["monto_cli"]) ? utf8_decode($_POST["monto_cli"]) : null;
$monto_adm = isset($_POST["monto_adm"]) ? utf8_decode($_POST["monto_adm"]) : null;
$id_for_pag = isset($_POST["id_for_pag"]) ? utf8_decode($_POST["id_for_pag"]) : 0;

if ($fecha_cli<>null) {
	$fecha_cli = date("Y-m-d",strtotime($fecha_cli));

	// $consulta .= "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ?";
	// if ($id_for_pag==1) {
	// 	$consulta .= " AND id_cam_eta = 56";
	// } else {
	// 	$consulta .= " AND id_cam_eta = 55";
	// }

	$consulta = "UPDATE venta_campo_venta SET fecha_pago_cliente_fondo_expotacion = ? WHERE id_ven = ?";

	$conexion->consulta_form($consulta,array($fecha_cli,$id_ven));
}

if ($fecha_adm<>null) {
	$fecha_adm = date("Y-m-d",strtotime($fecha_adm));

	// $consulta .= "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ?";
	// if ($id_for_pag==1) {
	// 	$consulta .= " AND id_cam_eta = 56";
	// } else {
	// 	$consulta .= " AND id_cam_eta = 55";
	// }

	$consulta = "UPDATE venta_campo_venta SET fecha_pago_fondo_expotacion = ? WHERE id_ven = ?";

	$conexion->consulta_form($consulta,array($fecha_adm,$id_ven));
}

if ($monto_cli<>null) {

	$consulta = "UPDATE venta_campo_venta SET monto_pago_fpm_cliente_ven = ? WHERE id_ven = ?";

	$conexion->consulta_form($consulta,array($monto_cli,$id_ven));
}

if ($monto_adm<>null) {

	$consulta = "UPDATE venta_campo_venta SET monto_pago_fpm_adm_ven = ? WHERE id_ven = ?";

	$conexion->consulta_form($consulta,array($monto_adm,$id_ven));
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>