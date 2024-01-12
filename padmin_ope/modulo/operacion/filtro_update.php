<?php
session_start();

// $fecha_desde = $_POST["fecha_desde"];
// $fecha_hasta = $_POST["fecha_hasta"];

$estado = isset($_POST["estado"]) ? utf8_decode($_POST["estado"]) : 0;
$vendedor = isset($_POST["vendedor"]) ? utf8_decode($_POST["vendedor"]) : 0;
$cliente = isset($_POST["cliente"]) ? utf8_decode($_POST["cliente"]) : 0;
$forma_pago = isset($_POST["forma_pago"]) ? utf8_decode($_POST["forma_pago"]) : 0;


$filtro_estado = isset($_POST["filtro_estado"]) ? utf8_decode($_POST["filtro_estado"]) : 0;
$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : 0;
$torre = isset($_POST["torre"]) ? utf8_decode($_POST["torre"]) : 0;
$departamento = isset($_POST["$departamento"]) ? utf8_decode($_POST["$departamento"]) : 0;
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : 0;
$cliente = isset($_POST["cliente"]) ? utf8_decode($_POST["cliente"]) : 0;

$departamento_venta = isset($_POST["departamento_venta"]) ? utf8_decode($_POST["departamento_venta"]) : 0;

if(empty($estado)){
	$estado = 0;
}
if(empty($vendedor)){
	$vendedor = 0;
}
if(empty($cliente)){
	$cliente = 0;
}
if(empty($forma_pago)){
	$forma_pago = 0;
}


if(empty($filtro_estado)){
	$filtro_estado = 0;
}
if(empty($condominio)){
	$condominio = 0;
}
if(empty($torre)){
	$torre = 0;
}
if(empty($departamento)){
	$departamento = 0;
}
if(empty($departamento_venta)){
	$departamento_venta = 0;
}
if(empty($modelo)){
	$modelo = 0;
}

// SESIONES INFORME DE OPERACIONES

if ($estado == 0) {
	unset($_SESSION["id_estado_filtro_panel"]);
}
else{
	$_SESSION["id_estado_filtro_panel"] = $estado;
}

if ($fecha_desde == "") {
	unset($_SESSION["sesion_filtro_fecha_desde_panel"]);
}
else{
	$_SESSION["sesion_filtro_fecha_desde_panel"] = $fecha_desde;
}

if ($fecha_hasta == "") {
	unset($_SESSION["sesion_filtro_fecha_hasta_panel"]);
}
else{
	$_SESSION["sesion_filtro_fecha_hasta_panel"] = $fecha_hasta;
}

if ($vendedor == 0) {
	unset($_SESSION["sesion_filtro_vendedor_panel"]);
}
else{
	$_SESSION["sesion_filtro_vendedor_panel"] = $vendedor;
}


if ($cliente == 0) {
	unset($_SESSION["sesion_filtro_propietario_panel"]);
}
else{
	$_SESSION["sesion_filtro_propietario_panel"] = $cliente;
}

if ($forma_pago == 0) {
	unset($_SESSION["sesion_filtro_forma_pago_panel"]);
}
else{
	$_SESSION["sesion_filtro_forma_pago_panel"] = $forma_pago;
}

if ($filtro_estado == 0) {
	unset($_SESSION["sesion_filtro_estado_panel"]);
}
else{
	$_SESSION["sesion_filtro_estado_panel"] = $filtro_estado;
}

if ($condominio == 0) {
	unset($_SESSION["sesion_filtro_condominio_panel"]);
}
else{
	$_SESSION["sesion_filtro_condominio_panel"] = $condominio;
}

if ($torre == 0) {
	unset($_SESSION["sesion_filtro_torre_panel"]);
}
else{
	$_SESSION["sesion_filtro_torre_panel"] = $torre;
}

if ($departamento == 0) {
	unset($_SESSION["sesion_filtro_departamento_panel"]);
}
else{
	$_SESSION["sesion_filtro_departamento_panel"] = $departamento;
}

if ($modelo == 0) {
	unset($_SESSION["sesion_filtro_modelo_panel"]);
}
else{
	$_SESSION["sesion_filtro_modelo_panel"] = $modelo;
}


if ($departamento_venta == 0) {
	unset($_SESSION["sesion_filtro_departamento_venta_panel"]);
}
else{
	$_SESSION["sesion_filtro_departamento_venta_panel"] = $departamento_venta;
}

?>