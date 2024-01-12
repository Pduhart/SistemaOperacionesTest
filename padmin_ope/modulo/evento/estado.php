<?php
include("../class/evento_clase.php");
// session_start();
if(!isset($_SESSION["sesion_usuario_panel"]) || !isset($_POST["valor"])){
    header("Location: "._ADMIN."index.php");
    exit();
}

$evento = new evento();
$id = isset($_POST["valor"]) ? htmlentities(utf8_decode($_POST["valor"])) : 0;
$evento->evento_update_estado($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
?>