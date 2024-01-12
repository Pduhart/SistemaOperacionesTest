<?php
include ("../../class/carro.php");
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
require "../../config.php";
include("../../class/conexion.php");
$conexion = new conexion();
$id = $_POST["valor"];
if(!isset($_SESSION["numero_item"])){
	$numero = 1;
}
else{
	$numero = $_SESSION["numero_item"];
	
}
$no_entrar = 0;
for ($i=0;$i<$numero;$i++){
	if($_SESSION["ocarrito_item"]->array_id_ite[$i]!=0){
		if($_SESSION["ocarrito_item"]->array_id_ite[$i] == $id){
				$_SESSION["ocarrito_item"]->array_id_ite[$i] = 0;
				/*$jsondata['ok']=1;
				echo json_encode($jsondata);  
				exit();*/
			
		}
	}
}
for ($i=0;$i<$numero;$i++){
	if($_SESSION["ocarrito_item"]->array_id_ite[$i] != 0){
		$no_entrar = 1;
	}
}
if($no_entrar == 0){
	unset($_SESSION["ocarrito_item"]);
	unset($_SESSION["numero_item"]);
}
$jsondata['envio'] = 1;
echo json_encode($jsondata);  
exit();
?>