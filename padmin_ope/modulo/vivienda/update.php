<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_vivienda_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/vivienda_clase.php");
$vivienda = new vivienda();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$torre = isset($_POST["torre"]) ? utf8_decode($_POST["torre"]) : "";
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : "";
$piso = isset($_POST["piso"]) ? utf8_decode($_POST["piso"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";

$valor = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : "";
$metro = isset($_POST["metro"]) ? utf8_decode($_POST["metro"]) : "";
$metro_terraza = isset($_POST["metro_terraza"]) ? utf8_decode($_POST["metro_terraza"]) : "";
$bono = isset($_POST["bono"]) ? utf8_decode($_POST["bono"]) : "";
$prorrateo = isset($_POST["prorrateo"]) ? utf8_decode($_POST["prorrateo"]) : 0;
$orientacion = isset($_POST["orientacion"]) ? utf8_decode($_POST["orientacion"]) : "";

$propietario = isset($_POST["propietario"]) ? utf8_decode($_POST["propietario"]) : "";

$total_metro = $metro + $metro_terraza;

$vivienda->vivienda_crea(1,$torre,$modelo,$orientacion,1,$piso,$nombre,$valor,$metro,$metro_terraza,$total_metro,$bono,$prorrateo);

$vivienda->vivienda_update($id);

$vivienda->vivienda_update_propietario($id, $propietario); 

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>