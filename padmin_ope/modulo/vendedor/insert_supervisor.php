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
if(!isset($_POST["supervisor"])){
	header("Location: ../../panel.php");
	exit();
}
include("../class/vendedor_clase.php");

$vendedor = new vendedor();

$supervisor = isset($_POST["supervisor"]) ? utf8_decode($_POST["supervisor"]) : "";
$vendedor->vendedor_delete_supervisor($supervisor);

$modulo_vendedor = $_POST['modulo_vendedor']; 
if (is_array($modulo_vendedor)){
	foreach($modulo_vendedor as $i){ 
		$vendedor->vendedor_insert_supervisor($supervisor,$i);
	} 
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>