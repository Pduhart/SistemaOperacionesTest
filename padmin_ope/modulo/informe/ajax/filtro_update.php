<?php
session_start();
date_default_timezone_set('America/Santiago');

$fecha_desde = $_POST["fecha_desde"];
$fecha_hasta = $_POST["fecha_hasta"];

$estado = isset($_POST["estado"]) ? utf8_decode($_POST["estado"]) : 0;
$estado_cotizacion = isset($_POST["estado_cotizacion"]) ? utf8_decode($_POST["estado_cotizacion"]) : 0;
$vendedor = isset($_POST["vendedor"]) ? utf8_decode($_POST["vendedor"]) : 0;
$periodo = isset($_POST["periodo"]) ? utf8_decode($_POST["periodo"]) : 0;
$cliente = isset($_POST["cliente"]) ? utf8_decode($_POST["cliente"]) : 0;
$forma_pago = isset($_POST["forma_pago"]) ? utf8_decode($_POST["forma_pago"]) : 0;
$torre = isset($_POST["torre"]) ? utf8_decode($_POST["torre"]) : 0;


$filtro_estado = isset($_POST["filtro_estado"]) ? utf8_decode($_POST["filtro_estado"]) : 0;
$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : 0;
$torre = isset($_POST["torre"]) ? utf8_decode($_POST["torre"]) : 0;
$departamento = isset($_POST["departamento"]) ? utf8_decode($_POST["departamento"]) : 0;
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : 0;
$cliente = isset($_POST["cliente"]) ? utf8_decode($_POST["cliente"]) : 0;
$banco = isset($_POST["banco"]) ? utf8_decode($_POST["banco"]) : 0;
$categoria_pago = isset($_POST["categoria_pago"]) ? utf8_decode($_POST["categoria_pago"]) : 0;
$estado_venta = isset($_POST["estado_venta"]) ? utf8_decode($_POST["estado_venta"]) : 0;

$mes_cot = isset($_POST["mes"]) ? $_POST["mes"] : date("m");
// $vendedor = isset($_POST["cliente"]) ? utf8_decode($_POST["cliente"]) : 0;

if(empty($estado)){
	$estado = 0;
}
if(empty($estado_cotizacion)){
	$estado_cotizacion = 0;
}
if(empty($vendedor)){
	$vendedor = 0;
}
if(empty($cliente)){
	$cliente = 0;
}
if(empty($banco)){
	$banco = 0;
}
if(empty($forma_pago)){
	$forma_pago = 0;
}
if(empty($categoria_pago)){
	$categoria_pago = 0;
}

if(empty($estado_venta)){
	$estado_venta = 0;
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
if(empty($modelo)){
	$modelo = 0;
}

if(empty($mes_cot)){
	$mes_cot = date("m");
}

// SESIONES INFORME DE OPERACIONES

if ($estado == 0) {
	unset($_SESSION["id_estado_filtro_panel"]);
}
else{
	$_SESSION["id_estado_filtro_panel"] = $estado;
}

if ($estado_cotizacion == 0) {
	unset($_SESSION["id_estado_cotizacion_filtro_panel"]);
}
else{
	$_SESSION["id_estado_cotizacion_filtro_panel"] = $estado_cotizacion;
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
if ($banco == 0) {
	unset($_SESSION["sesion_filtro_banco_panel"]);
}
else{
	$_SESSION["sesion_filtro_banco_panel"] = $banco;
}
if ($categoria_pago == 0) {
	unset($_SESSION["sesion_filtro_categoria_pago_panel"]);
}
else{
	$_SESSION["sesion_filtro_categoria_pago_panel"] = $categoria_pago;
}

if ($estado_venta == 0) {
	unset($_SESSION["sesion_filtro_estado_venta_panel"]);
}
else{
	$_SESSION["sesion_filtro_estado_venta_panel"] = $estado_venta;
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

if ($periodo == 0) {
	unset($_SESSION["sesion_filtro_periodo_panel"]);
}
else{
	$_SESSION["sesion_filtro_periodo_panel"] = $periodo;
}

if ($torre == 0) {
	unset($_SESSION["sesion_filtro_torre_panel"]);
}
else{
	$_SESSION["sesion_filtro_torre_panel"] = $torre;
}

if ($mes_cot == 0) {
	unset($_SESSION["sesion_filtro_mes_cot_panel"]);
}
else{
	$_SESSION["sesion_filtro_mes_cot_panel"] = $mes_cot;
}

?>