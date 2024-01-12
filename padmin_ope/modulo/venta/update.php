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

$premio = isset($_POST["premio"]) ? utf8_decode($_POST["premio"]) : 0;
$forma_pago = isset($_POST["forma_pago"]) ? utf8_decode($_POST["forma_pago"]) : "";
$total_vivienda = isset($_POST["total_vivienda"]) ? utf8_decode($_POST["total_vivienda"]) : "";
$banco = isset($_POST["banco"]) ? utf8_decode($_POST["banco"]) : 0;
$tipo_pago = isset($_POST["tipo_pago"]) ? utf8_decode($_POST["tipo_pago"]) : 0;
$credito_real = isset($_POST["credito_real"]) ? utf8_decode($_POST["credito_real"]) : 0;
$aplica_pie = isset($_POST["aplica_pie"]) ? utf8_decode($_POST["aplica_pie"]) : 0;
$propietario = isset($_POST["propietario"]) ? utf8_decode($_POST["propietario"]) : 0;
$fecha_ven = isset($_POST["fecha_ven"]) ? utf8_decode($_POST["fecha_ven"]) : null;
$hora = date(" H:i:s");
$fecha_ven = date("Y-m-d  H:i:s",strtotime($fecha_ven." ".$hora));
// echo $credito_real."---";
$venta->venta_update_sin_clase($id,$premio,$forma_pago,$total_vivienda,$banco,$tipo_pago,$credito_real,$aplica_pie,$propietario,$fecha_ven);


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>