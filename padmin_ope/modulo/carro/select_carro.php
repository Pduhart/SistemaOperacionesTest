<?php
include("../../class/carro.php");
require "../../config.php";
include("../../class/conexion.php");

session_start();
if (!isset($_SESSION["ocarrito_item"])){
	$_SESSION["ocarrito_item"] = new carrito();
}

$conexion = new conexion();
$detalle = $_POST["detalle"];
$valor = $_POST["valor"];


//-------------------------------------------------------------
if(!isset($_SESSION["numero_item"])){
	$numero = 1;
}
else{
	$numero = $_SESSION["numero_item"];
}

for ($i=0;$i<$numero;$i++){
	if($_SESSION["ocarrito_item"]->array_id_ite[$i] != 0){
		if($_SESSION["ocarrito_item"]->array_detalle_ite[$i] == $detalle){
			
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);  
			exit();		
		}
	}
}

$numero_nuevo = $numero + 1;

$_SESSION["ocarrito_item"]->introduce_item(1,$detalle,$valor);
$jsondata['envio'] = 1;
echo json_encode($jsondata);  
exit();
?>