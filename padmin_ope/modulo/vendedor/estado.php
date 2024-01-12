<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_vendedor_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}

include("../class/vendedor_clase.php");
$vendedor = new vendedor();
$id = isset($_POST["valor"]) ? htmlentities(utf8_decode($_POST["valor"])) : 0;
$vendedor->vendedor_update_estado($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>