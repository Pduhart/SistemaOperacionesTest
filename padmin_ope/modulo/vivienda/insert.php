<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_vivienda_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}


include("../class/vivienda_clase.php");
$vivienda = new vivienda();

$torre = isset($_POST["torre"]) ? utf8_decode($_POST["torre"]) : "";
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$piso = isset($_POST["piso"]) ? utf8_decode($_POST["piso"]) : "";

$valor = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : "";
$metro = isset($_POST["metro"]) ? utf8_decode($_POST["metro"]) : "";
$metro_terraza = isset($_POST["metro_terraza"]) ? utf8_decode($_POST["metro_terraza"]) : "";
$bono = isset($_POST["bono"]) ? utf8_decode($_POST["bono"]) : "";
$prorrateo = isset($_POST["prorrateo"]) ? utf8_decode($_POST["prorrateo"]) : 0;
$orientacion = isset($_POST["orientacion"]) ? utf8_decode($_POST["orientacion"]) : "";

$propietario = isset($_POST["propietario"]) ? utf8_decode($_POST["propietario"]) : "";
// $valor_alto = isset($_POST["valor_alto"]) ? utf8_decode($_POST["valor_alto"]) : 0;
// $valor_bajo = isset($_POST["valor_bajo"]) ? utf8_decode($_POST["valor_bajo"]) : 0;
// $valor_medio = isset($_POST["valor_medio"]) ? utf8_decode($_POST["valor_medio"]) : 0;

$total_metro = $metro + $metro_terraza;

$vivienda->vivienda_crea(1,$torre,$modelo,$orientacion,1,$piso,$nombre,$valor,$metro,$metro_terraza,$total_metro,$bono,$prorrateo);

$vivienda->vivienda_insert();

$id_vivienda = $vivienda->recupera_id();

if ($propietario > 0) {
	$vivienda->vivienda_insert_propietario($id_vivienda, $propietario); 
}


// if (isset($_POST['servicio_vivienda'])) {
//     if (is_array($_POST['servicio_vivienda'])) {
//         foreach ($_POST['servicio_vivienda'] as $key => $id_ser) {
//         	$vivienda->vivienda_insert_servicio_vivienda($id_vivienda, $id_ser); 
//         }
//     }
// }

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>