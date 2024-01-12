<?php
session_start();                      				// HTTP/1.0
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

sleep(1); 

$pk = $_POST['pk'];
$value = $_POST['value'];

$valores = explode("-", $pk);
$id_vend = $valores[0];
$id_mes = $valores[1];
$anio_mes = $valores[2];

//$_SESSION["sesion_prueba_meta"] = $id_vend."-".$id_mes."-".$anio_mes;


$consulta = "SELECT * FROM vendedor_meta_vendedor WHERE id_mes = ? AND anio_mes = ? AND id_vend = ?";
$conexion->consulta_form($consulta,array($id_mes,$anio_mes,$id_vend));
$cantidad = $conexion->total();

if($cantidad > 0){
	$consulta = "UPDATE vendedor_meta_vendedor SET valor_met_ven = ? WHERE id_mes = ? AND anio_mes = ? AND id_vend = ?";
	$conexion->consulta_form($consulta,array($value,$id_mes,$anio_mes,$id_vend));
}
else{
	$consulta = "INSERT INTO vendedor_meta_vendedor VALUES(?,?,?,?,?,?)";
	$conexion->consulta_form($consulta,array(0,$id_vend,$id_mes,$anio_mes,$value,$pk));
}
?>
