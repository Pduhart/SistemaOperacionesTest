<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_POST["fecha"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/operacion_clase.php");
$operacion = new operacion();

// include("../../class/conexion.php");
$conexion = new conexion();

$id_ven = isset($_POST["id_ven"]) ? utf8_decode($_POST["id_ven"]) : 0;
$id_etapa = isset($_POST["id_etapa"]) ? utf8_decode($_POST["id_etapa"]) : 0;
$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : "0000-00-00 00:00:00";
$hora = date(" H:i:s");
$fecha = date("Y-m-d  H:i:s",strtotime($fecha." ".$hora));

// validar si la fecha es mayor al cierre etapa anterior
$consulta_ultimo_cierre = "SELECT fecha_hasta_eta_ven FROM venta_etapa_venta WHERE id_ven = ? AND id_est_eta_ven = 1 ORDER BY id_eta_ven DESC LIMIT 0,1";
$conexion->consulta_form($consulta_ultimo_cierre,array($id_ven));
$fila = $conexion->extraer_registro_unico();
$fecha_hasta_eta_ven = date("Y-m-d",strtotime($fila["fecha_hasta_eta_ven"]));

$fecha_compara = date("Y-m-d",strtotime($fecha));

// if ($fecha_compara<$fecha_hasta_eta_ven) {
// 	$jsondata['envio'] = 5;
// 	echo json_encode($jsondata);
// 	exit();
// }

$operacion->operacion_insert_fecha($id_ven,$fecha,$id_etapa);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>