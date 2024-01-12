<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_torre_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}

include("../class/torre_clase.php");
$torre = new torre();
$id = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : 0;
$torre->torre_update_estado($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>