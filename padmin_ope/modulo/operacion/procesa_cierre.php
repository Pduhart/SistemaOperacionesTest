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


$fecha_cierre = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : 0;
$fecha_cierre_formato = date("Y-m-d",strtotime($fecha_cierre));


$fecha_hoy = date("Y-m-d");
$fecha_mas_mes = date('Y-m-d',strtotime ( '+1 month' , strtotime ( $fecha_hoy)));

if ($fecha_cierre_formato>$fecha_mas_mes) {
	$jsondata['envio'] = 2;
	echo json_encode($jsondata);
	exit();
} else {
	$jsondata['envio'] = 1;
	echo json_encode($jsondata);
	exit();
}
?>