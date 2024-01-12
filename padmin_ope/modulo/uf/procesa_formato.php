<?php
session_start();                      				
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$pk = $_POST['pk'];


$consulta = "SELECT valor_uf FROM uf_uf WHERE fecha_uf = ?";
$conexion->consulta_form($consulta,array($pk));
$fila = $conexion->extraer_registro_unico();
$valor_uf = number_format($fila["valor_uf"], 2, ',', '.');

$jsondata['envio'] = $valor_uf;
echo json_encode($jsondata);
exit();
?>
