<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_reserva_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/reserva_clase.php");
$reserva = new reserva();

$id = isset($_POST["id"]) ? htmlentities(utf8_decode($_POST["id"])) : 0;
$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : 0;
$vivienda = isset($_POST["vivienda"]) ? utf8_decode($_POST["vivienda"]) : 0;
$tipo = isset($_POST["tipo"]) ? utf8_decode($_POST["tipo"]) : 0;
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : 0;
$programa = isset($_POST["programa"]) ? utf8_decode($_POST["programa"]) : 0;
$fecha = isset($_POST["fecha"]) ? utf8_decode($_POST["fecha"]) : "0000-00-00";
$cantidad = isset($_POST["cantidad"]) ? utf8_decode($_POST["cantidad"]) : 0;
$procedencia = isset($_POST["procedencia"]) ? utf8_decode($_POST["procedencia"]) : 0;
$arrendatario = isset($_POST["arrendatario"]) ? utf8_decode($_POST["arrendatario"]) : 0;
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";

$fecha_separada = explode(" / ", $fecha);

$fecha_separada_inicio = explode(" ", $fecha_separada[0]);
$fecha_separada_fin = explode(" ", $fecha_separada[1]);

$fecha_desde = $fecha_separada_inicio[0];
$fecha_hasta = $fecha_separada_fin[0];


$fecha_desde = date("Y-m-d",strtotime($fecha_desde));
$fecha_hasta = date("Y-m-d",strtotime($fecha_hasta));

$dias= (strtotime($fecha_hasta)-strtotime($fecha_desde))/86400;
$dias = abs($dias); 
$dias = floor($dias);

$reserva->reserva_crea($vivienda,$programa,$tipo,$arrendatario,$procedencia,$_SESSION["sesion_id_panel"],2,2,date("Y-m-d"),$fecha_desde,$fecha_hasta,'','','','',0,0,0,0,0,$cantidad,$dias,'','',$descripcion);

$reserva->reserva_update($id);

if (isset($_POST['servicio'])) {
    if (is_array($_POST['servicio'])) {
        foreach ($_POST['servicio'] as $key => $id_ser) {
        	$reserva->reserva_insert_servicio($id, $id_ser); 
        }
    }
}

//reserva_update_monto
$reserva->reserva_update_monto($id);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>