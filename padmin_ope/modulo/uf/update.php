<?php
session_start();                      				// HTTP/1.0
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

sleep(1); 
$pk = $_POST['pk'];
$name_not = $_POST['name'];
$value = $_POST['value'];

$consulta = "SELECT * FROM uf_uf WHERE fecha_uf = ?";
$conexion->consulta_form($consulta,array($pk));
$cantidad = $conexion->total();
if($cantidad > 0){
	$consulta = "UPDATE uf_uf SET valor_uf = ? WHERE fecha_uf = ?";
	$conexion->consulta_form($consulta,array($value,$pk));
}
else{
	$consulta = "INSERT INTO uf_uf VALUES(?,?,?)";
	$conexion->consulta_form($consulta,array(0,$pk,$value));
}
?>
