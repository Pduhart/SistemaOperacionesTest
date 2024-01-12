<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}

if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/venta_clase.php");
$venta = new venta();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;
$forma_pago = isset($_POST["forma_pago"]) ? utf8_decode($_POST["forma_pago"]) : "";
$banco = isset($_POST["banco"]) ? utf8_decode($_POST["banco"]) : "";
$categoria = isset($_POST["categoria"]) ? utf8_decode($_POST["categoria"]) : "";
$numero_documento = isset($_POST["numero_documento"]) ? utf8_decode($_POST["numero_documento"]) : "";
$numero_serie = $_POST["numero_serie".$contador];
$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : null;
$fecha = date("Y-m-d",strtotime($fecha));
$fecha_real = isset($_POST["fecha_real"]) ? utf8_decode($_POST["fecha_real"]) : null;
if ($fecha_real<>'') {
	$fecha_real = date("Y-m-d",strtotime($fecha_real));
} else {
	$fecha_real = null;
}
$monto = isset($_POST["monto"]) ? utf8_decode($_POST["monto"]) : "";

$venta->venta_update_pago($id,$forma_pago,$banco,$categoria,$numero_documento,$numero_serie,$fecha,$fecha_real,$monto);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>