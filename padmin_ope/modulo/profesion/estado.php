<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_profesion_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}

include("../class/seguro_clase.php");
$seguro = new seguro();
$id = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : 0;
$seguro->seguro_update_estado($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>