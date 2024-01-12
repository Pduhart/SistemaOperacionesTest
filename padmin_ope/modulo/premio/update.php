<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_premio_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/premio_clase.php");
$premio = new premio();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";

$premio->premio_crea(1,$nombre);

$premio->premio_update($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>