<?php
session_start();

$sexo = isset($_POST["sexo"]) ? utf8_decode($_POST["sexo"]) : 0;
$profesion = isset($_POST["profesion"]) ? utf8_decode($_POST["profesion"]) : 0;
$condepto = isset($_POST["condepto"]) ? utf8_decode($_POST["condepto"]) : 0;
$region = isset($_POST["region"]) ? utf8_decode($_POST["region"]) : 0;
$comuna = isset($_POST["comuna"]) ? utf8_decode($_POST["comuna"]) : 0;


if(empty($sexo)){
	$sexo = 0;
}

if(empty($profesion)){
	$profesion = 0;
}

if(empty($condepto)){
	$condepto = 0;
}

if(empty($region)){
	$region = 0;
}

if(empty($comuna)){
	$comuna = 0;
}


// SESIONES INFORME DE OPERACIONES

if ($sexo == 0) {
	unset($_SESSION["id_sexo_prop_filtro"]);
}
else{
	$_SESSION["id_sexo_prop_filtro"] = $sexo;
}

if ($profesion == 0) {
	unset($_SESSION["id_prof_prop_filtro"]);
}
else{
	$_SESSION["id_prof_prop_filtro"] = $profesion;
}

if ($condepto == 0) {
	unset($_SESSION["id_condepto_prop_filtro"]);
}
else{
	$_SESSION["id_condepto_prop_filtro"] = $condepto;
}

if ($region == 0) {
	unset($_SESSION["id_reg_prop_filtro"]);
}
else{
	$_SESSION["id_reg_prop_filtro"] = $region;
}

if ($comuna == 0) {
	unset($_SESSION["id_com_prop_filtro"]);
}
else{
	$_SESSION["id_com_prop_filtro"] = $comuna;
}

?>