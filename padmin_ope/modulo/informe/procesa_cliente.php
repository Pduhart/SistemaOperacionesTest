<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
require "../../config.php";
include("../../class/conexion.php");
$conexion = new conexion();

$cliente = utf8_decode($_POST["valor"]);


$_SESSION["id_cliente_filtro_ficha_panel"] = $cliente;


// $usuario = $nombre_fam." ".$apellido_paterno_fam." ".$apellido_materno_fam;
// $jsondata['cliente_nombre'] = $usuario;
$jsondata['cliente_id'] = $cliente;
echo json_encode($jsondata);
exit();
?>