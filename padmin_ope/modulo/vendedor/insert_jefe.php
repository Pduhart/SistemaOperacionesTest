<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_vendedor_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["jefe"])){
	header("Location: ../../panel.php");
	exit();
}
include("../class/vendedor_clase.php");

$vendedor = new vendedor();

$jefe = isset($_POST["jefe"]) ? utf8_decode($_POST["jefe"]) : "";
$vendedor->vendedor_delete_jefe($jefe);

$modulo_vendedor = $_POST['modulo_vendedor']; 
if (is_array($modulo_vendedor)){
	foreach($modulo_vendedor as $i){ 
		$vendedor->vendedor_insert_jefe($jefe,$i);
	} 
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>