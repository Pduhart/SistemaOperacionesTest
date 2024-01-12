<?php
session_start();

$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : 0;


if(empty($condominio)){
	$condominio = 0;
}


// SESIONES INFORME DE OPERACIONES

if ($condominio == 0) {
	unset($_SESSION["sesion_filtro_condominio_panel"]);
}
else{
	$_SESSION["sesion_filtro_condominio_panel"] = $condominio;
}


?>