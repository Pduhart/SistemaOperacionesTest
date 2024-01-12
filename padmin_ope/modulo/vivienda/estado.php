<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_vivienda_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}

include("../class/vivienda_clase.php");
$vivienda = new vivienda();
$id = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : 0;
$vivienda->vivienda_update_estado($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>