<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if (!isset($_SESSION["modulo_bodega_panel"])) {
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
include("../class/bodega_clase.php");
$bodega = new bodega();
$id = $_POST["valor"];
$bodega->bodega_delete($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>