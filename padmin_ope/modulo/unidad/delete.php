<?
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if (!isset($_SESSION["modulo_unidad_panel"])) {
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
include("../class/unidad_clase.php");
$unidad = new unidad();
$id = $_POST["valor"];
$unidad->unidad_delete($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>