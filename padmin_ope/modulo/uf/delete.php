<?
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if (!isset($_SESSION["modulo_proyecto_panel"])) {
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
include("../class/proyecto_clase.php");
$proyecto = new proyecto();
$id = $_POST["valor"];
$proyecto->proyecto_delete($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>