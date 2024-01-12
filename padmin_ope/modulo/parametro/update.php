<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_parametro_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
// if(!isset($_POST["parametro1"])){
// 	header("Location: "._ADMIN."index.php");
// 	exit();
// }


include("../../class/conexion.php");
$conexion = new conexion();
$condominio = $_POST["condominio"];
if($condominio<>'') {
	// echo "entro al condo".$condominio;
	$contador = 1;
	while($contador <= 28){
		$valor_par = $_POST["parametro".$contador];
		$consulta = "UPDATE parametro_parametro SET valor_par = ? WHERE valor2_par = ? AND id_con = ? ";
		$conexion->consulta_form($consulta,array($valor_par,$contador,$condominio));

		$contador++;
	}
}


$contador = 14;
while($contador <= 17){
	$valor_par = $_POST["parametro_fijo".$contador];
	$consulta = "UPDATE parametro_parametro SET valor_par = ? WHERE id_par = ? AND id_con = ? ";
	$conexion->consulta_form($consulta,array($valor_par,$contador,0));

	$contador++;
}

$contador = 22;
$valor_par = $_POST["parametro_fijo".$contador];
$consulta = "UPDATE parametro_parametro SET valor_par = ? WHERE id_par = ? AND id_con = ? ";
$conexion->consulta_form($consulta,array($valor_par,$contador,0));

$contador = 109;
$valor_par = $_POST["parametro_fijo".$contador];
$consulta = "UPDATE parametro_parametro SET valor_par = ? WHERE id_par = ? AND id_con = ? ";
$conexion->consulta_form($consulta,array($valor_par,$contador,0));

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>