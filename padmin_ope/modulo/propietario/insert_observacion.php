<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_POST["descripcion"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/propietario_clase.php");
$propietario = new propietario();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$fecha = date("Y-m-d H:i:s");

$propietario->propietario_insert_observacion($id,$_SESSION["sesion_id_panel"],$fecha,$descripcion);

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>