<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_POST["observacion"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/operacion_clase.php");
$operacion = new operacion();

$id_ven = isset($_POST["id_ven"]) ? utf8_decode($_POST["id_ven"]) : 0;
$observacion = isset($_POST["observacion"]) ? utf8_decode($_POST["observacion"]) : "";

$fecha = date("Y-m-d H:i:s");

$operacion->operacion_insert_observacion($id_ven,$_SESSION["sesion_id_panel"],$_SESSION["sesion_etapa_operacion_panel"],$fecha,$observacion);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>