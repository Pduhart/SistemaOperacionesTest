<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_propietario_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
if(!isset($_POST["valor"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
include("../class/propietario_clase.php");
$propietario = new propietario();
$id_emp = $_POST["valor"];
$cantidad_emp = $_POST["cantidad"];
$id_emp = explode(',',$id_emp);
$cantidad = $cantidad_emp - 1;
$contador = 0;
while($contador <= $cantidad ){
	$propietario->propietario_delete($id_emp[$contador]);
	$contador++;
}
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>