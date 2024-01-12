<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/operacion_clase.php");
$operacion = new operacion();

$id = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : 0;

$operacion->operacion_delete_rechazo($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>