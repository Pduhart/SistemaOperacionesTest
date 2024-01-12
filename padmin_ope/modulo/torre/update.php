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
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/torre_clase.php");
$torre = new torre();
$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";

$torre->torre_crea($condominio,1,$nombre);

$torre->torre_update($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>