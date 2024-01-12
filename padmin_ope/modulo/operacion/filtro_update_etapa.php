<?php
session_start();


$etapa = isset($_POST["etapa"]) ? utf8_decode($_POST["etapa"]) : 0;


if(empty($etapa)){
	$etapa = 0;
}


if ($etapa == 0) {
	unset($_SESSION["sesion_etapa_filtro_operacion_panel"]);
}
else{
	$_SESSION["sesion_etapa_filtro_operacion_panel"] = $etapa;
}



?>