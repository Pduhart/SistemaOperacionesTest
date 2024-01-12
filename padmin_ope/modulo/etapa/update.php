<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_etapa_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/etapa_clase.php");
$etapa = new etapa();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

$categoria = isset($_POST["categoria"]) ? utf8_decode($_POST["categoria"]) : "";
$forma_pago = isset($_POST["forma_pago"]) ? utf8_decode($_POST["forma_pago"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$alias = isset($_POST["alias"]) ? utf8_decode($_POST["alias"]) : "";
$numero = isset($_POST["numero"]) ? utf8_decode($_POST["numero"]) : 0;
$duracion = isset($_POST["duracion"]) ? utf8_decode($_POST["duracion"]) : 0;


$etapa->etapa_crea($categoria,$forma_pago,1,$nombre,$alias,$numero,$duracion);
$etapa->etapa_update($id);

$campo_numero = $_POST["campo_numero"];
foreach ($campo_numero as $key => $valor) {
	$etapa->etapa_campo_insert($id,2,$valor);
}

$campo_fecha = $_POST["campo_fecha"];
foreach ($campo_fecha as $key => $valor) {
	$etapa->etapa_campo_insert($id,3,$valor);
}

$campo_texto = $_POST["campo_texto"];
foreach ($campo_texto as $key => $valor) {
	$etapa->etapa_campo_insert($id,1,$valor);
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>