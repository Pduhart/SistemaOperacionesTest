<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_banco_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/banco_clase.php");
$banco = new banco();
$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$convenio = isset($_POST["convenio"]) ? utf8_decode($_POST["convenio"]) : "";

$banco->banco_crea($nombre,$convenio);

$banco->banco_update($id);


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>