<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if (!isset($_SESSION["modulo_propietario_panel"])) {
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
include("../class/propietario_clase.php");
$propietario = new propietario();
$id = $_POST["valor"];
$idpro = $_POST["idpro"];
$propietario->propietario_delete_obs($id);
$jsondata['envio'] = 1;
$jsondata['propietario'] = $idpro;
echo json_encode($jsondata);
exit();
?>