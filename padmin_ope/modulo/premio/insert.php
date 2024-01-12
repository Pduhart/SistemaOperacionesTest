<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_premio_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/premio_clase.php");
$premio = new premio();

$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$premio->premio_crea(1,$nombre);

$premio->premio_insert();
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>