<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
require "../../config.php";
include("../../class/conexion.php");
$conexion = new conexion();

$id = utf8_decode($_POST["valor"]);

$_SESSION["id_proyecto_filtro_panel"] = $id;

?>